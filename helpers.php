<?php


require_once __DIR__ .'/database.php';


//Escape HTML
//This protects the application against XSS.
function e($value): string
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}
//Redirect usre to another page 
function redirect(string $url): never
{
    header("Location:$url");
    exit;
}

//Check whether the user is logged in
function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

//Get logged-in user
function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

// Customer/admin must be logged in
function require_login(): void
{
    if (!is_logged_in()) {
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
        if (str_contains($scriptDir, '/admin')) {
            $scriptDir = dirname($scriptDir);
        }

        redirect($scriptDir . '/login.php');
    }
}


// Only admin can access admin page
function require_admin(): void
{
    require_login();

    $user = current_user();
    if (!$user) {
        http_response_code(403);
        die('Access denied');
    }

    if (!isset($user['role']) || $user['role'] !== 'admin') {
        require_once __DIR__ . '/../models/Users.php';
        $freshUser = User::find((int) $user['id']);

        if ($freshUser && $freshUser['role'] === 'admin') {
            $_SESSION['user']['role'] = 'admin';
            return;
        }

        http_response_code(403);
        die('Access denied');
    }
}

//Create CSRF security token

function csrf_token() :string{
    if(empty($_SESSION['csrf_token'])){
        $_SESSION['csrf_token']=
        bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

//Check CSRF token

function verify_csrf(): void
{
    $token =$_POST['csrf_token'] ?? '';
    if(
        empty($_SESSION['csrf_token']) ||
        !hash_equals(
            $_SESSION['csrf_token'],
            $token
        )
    )
    {
        http_response_code(403);
        die('Invalid security token');
    }
}

// Save inmportant actions in audit log

function log_action(
    string $action,
    string $details = ''
): void {
    $user = current_user();
    $userId = $user['id'] ?? null;

    $sql = "
        INSERT INTO audit_logs
        (user_id, action, details)
        VALUES(?,?,?)
    ";

    $stmt = db()->prepare($sql);
    $stmt->execute([
        $userId,
        $action,
        $details,
    ]);
}
?>