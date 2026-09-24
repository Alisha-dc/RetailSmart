<?php

require_once __DIR__ .
    '/../app/config/helpers.php';

require_once __DIR__ .
    '/../app/controllers/auth.php';


// If already logged in
if (is_logged_in()) {
    redirect('index.php');
}


$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();


    $name =
        trim($_POST['name'] ?? '');

    $email =
        trim($_POST['email'] ?? '');

    $password =
        $_POST['password'] ?? '';

    $confirmPassword =
        $_POST['confirm_password'] ?? '';


    $result =
        register_user(
            $name,
            $email,
            $password,
            $confirmPassword
        );


    $errors =
        $result['errors'];


    if ($result['success']) {

        redirect('login.php?registered=1');
    }
}


$pageTitle = 'Register';

require_once
    __DIR__ .
    '/../app/views/partials/header.php';
?>


<div class="form-container">

    <h1>
        Create Account
    </h1>


    <?php if (!empty($errors)): ?>

        <div class="alert error">

            <ul>

                <?php foreach ($errors as $error): ?>

                    <li>
                        <?= e($error) ?>
                    </li>

                <?php endforeach; ?>

            </ul>

        </div>

    <?php endif; ?>


    <form
        method="POST"
        novalidate
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= csrf_token() ?>"
        >


        <label for="name">
            Full Name
        </label>

        <input
            type="text"
            id="name"
            name="name"
            required
        >


        <label for="email">
            Email
        </label>

        <input
            type="email"
            id="email"
            name="email"
            required
        >


        <label for="password">
            Password
        </label>

        <input
            type="password"
            id="password"
            name="password"
            minlength="8"
            required
        >


        <label for="confirm_password">
            Confirm Password
        </label>

        <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            minlength="8"
            required
        >


        <button type="submit">
            Create Account
        </button>

    </form>


    <p>
        Already have an account?
        <a href="login.php">
            Login
        </a>
    </p>

</div>


<?php

require_once
    __DIR__ .
    '/../app/views/partials/footer.php';

?>