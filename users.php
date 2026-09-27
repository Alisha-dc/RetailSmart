<?php

require_once __DIR__ .
    '/../../app/config/helpers.php';

require_once __DIR__ .
    '/../../app/models/Users.php';

require_admin();


$users =
    User::all();


$pageTitle =
    'Manage Users';

require_once
    __DIR__ .
    '/../../app/views/partials/header.php';
?>


<h1>
    Manage Users
</h1>


<?php if (!empty($_SESSION['flash'])): ?>

    <div class="alert error">

        <?= e($_SESSION['flash']) ?>

    </div>

    <?php unset($_SESSION['flash']); ?>

<?php endif; ?>


<div class="table-wrapper">

    <table>

        <thead>

            <tr>

                <th>
                    ID
                </th>

                <th>
                    Name
                </th>

                <th>
                    Email
                </th>

                <th>
                    Role
                </th>

                <th>
                    Created
                </th>

                <th>
                    Actions
                </th>

            </tr>

        </thead>


        <tbody>

            <?php foreach ($users as $user): ?>

                <tr>

                    <td>
                        <?= e($user['id']) ?>
                    </td>

                    <td>
                        <?= e($user['name']) ?>
                    </td>

                    <td>
                        <?= e($user['email']) ?>
                    </td>

                    <td>
                        <?= e($user['role']) ?>
                    </td>

                    <td>
                        <?= e($user['created_at']) ?>
                    </td>

                    <td>

                        <!-- Change role -->

                        <form
                            method="POST"
                            action="../action.php"
                            class="inline-form"
                        >

                            <input
                                type="hidden"
                                name="action"
                                value="change_role"
                            >

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= csrf_token() ?>"
                            >

                            <input
                                type="hidden"
                                name="user_id"
                                value="<?= $user['id'] ?>"
                            >


                            <select name="role">

                                <option
                                    value="customer"
                                    <?= $user['role'] === 'customer'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Customer
                                </option>


                                <option
                                    value="admin"
                                    <?= $user['role'] === 'admin'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Admin
                                </option>

                            </select>


                            <button
                                type="submit"
                                class="small"
                            >
                                Update
                            </button>

                        </form>


                        <!-- Delete user -->

                        <?php
                        $current =
                            current_user();
                        ?>

                        <?php if (
                            (int)$user['id']
                            !== (int)$current['id']
                        ): ?>

                            <form
                                method="POST"
                                action="../action.php"
                                class="inline-form"
                            >

                                <input
                                    type="hidden"
                                    name="action"
                                    value="delete_user"
                                >

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= csrf_token() ?>"
                                >

                                <input
                                    type="hidden"
                                    name="user_id"
                                    value="<?= $user['id'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="danger small"
                                    onclick="return confirm('Delete this user?')"
                                >
                                    Delete
                                </button>

                            </form>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>


<?php

require_once
    __DIR__ .
    '/../../app/views/partials/footer.php';

?>