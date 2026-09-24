<?php
require_once __DIR__ . '/../models/Users.php';


//REGISTER

function register_user(
    string $name,
    string $email,
    string $password,
    string $confirmPassword
): array {

    $errors = [];

    // Basic validation
    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    // Check duplicate email
    if (empty($errors) && User::findByEmail($email)) {
        $errors[] = 'This email is already registered.';
    }

    // Stop if errors
    if (!empty($errors)) {
        return [
            'success' => false,
            'errors' => $errors,
        ];
    }

    // Create account
    User::create($name, $email, $password);

    log_action(
        'User Registered',
        "New account: $email"
    );

    return [
        'success' => true,
        'errors' => [],
    ];
}

//Login
function login_user(
    string $email,
    string $password
): bool {

$user = User::findByEmail($email);

//Check password

if (!$user || !password_verify($password, $user['password'])) {
    return false;
}

//Prevent session fixation 

session_regenerate_id(true);

//Save safe user information


$_SESSION['user'] = [
    'id' => $user['id'],
    'name' => $user['name'],
    'email' => $user['email'],
    'role' => $user['role'],
];

log_action(
    'User Login',
    "Login:$email"
);
return true;
}

//Logout

function logout_user(): void
{
    log_action(
        'User Logout',
        'User logged out'
    );

    $_SESSION = [];
    session_destroy();
}
