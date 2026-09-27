<?php

require_once __DIR__ .
    '/../app/config/helpers.php';

require_once __DIR__ .
    '/../app/controllers/auth.php';


if (is_logged_in()) {
    redirect('index.php');
}


$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();


    $email =
        trim($_POST['email'] ?? '');

    $password =
        $_POST['password'] ?? '';


    if (
        login_user(
            $email,
            $password
        )
    ) {

        // Send admin to admin page
        if (
            $_SESSION['user']['role']
            === 'admin'
        ) {

            redirect('admin/dashboard.php');
        }


        redirect('index.php');

    } else {

        $error =
            'Invalid email or password.';
    }
}


$pageTitle = 'Login';

require_once
    __DIR__ .
    '/../app/views/partials/header.php';
?>


<div class="form-container">

    <h1>
        Login
    </h1>


    <?php if (isset($_GET['registered'])): ?>

        <div class="alert success">
            Account created.
            You can now login.
        </div>

    <?php endif; ?>


    <?php if ($error): ?>

        <div class="alert error">
            <?= e($error) ?>
        </div>

    <?php endif; ?>


    <form method="POST">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= csrf_token() ?>"
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
            required
        >


        <button type="submit">
            Login
        </button>

    </form>


    <p>
        Don't have an account?
        <a href="register.php">
            Register
        </a>
    </p>

</div>


<?php

require_once
    __DIR__ .
    '/../app/views/partials/footer.php';

?>