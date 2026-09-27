<?php

require_once __DIR__ .
    '/../../app/config/helpers.php';

require_once __DIR__ .
    '/../../app/models/AuditLog.php';

require_once __DIR__ .
    '/../../app/models/Product.php';

require_once __DIR__ .
    '/../../app/models/Order.php';

require_once __DIR__ .
    '/../../app/models/Users.php';


require_admin();


// Get dashboard information
$productCount =
    db()->query(
        "SELECT COUNT(*) FROM products"
    )->fetchColumn();


$userCount =
    db()->query(
        "SELECT COUNT(*) FROM users"
    )->fetchColumn();


$orderCount =
    db()->query(
        "SELECT COUNT(*) FROM orders"
    )->fetchColumn();


$totalSales =
    db()->query(
        "SELECT COALESCE(SUM(total),0)
         FROM orders
         WHERE status != 'Cancelled'"
    )->fetchColumn();


$logs =
    AuditLog::recent(10);


$pageTitle = 'Admin Dashboard';

require_once
    __DIR__ .
    '/../../app/views/partials/header.php';
?>


<h1>
    Admin Dashboard
</h1>


<div class="dashboard-grid">

    <div class="dashboard-card">

        <h2>
            Products
        </h2>

        <strong>
            <?= e($productCount) ?>
        </strong>

    </div>


    <div class="dashboard-card">

        <h2>
            Users
        </h2>

        <strong>
            <?= e($userCount) ?>
        </strong>

    </div>


    <div class="dashboard-card">

        <h2>
            Orders
        </h2>

        <strong>
            <?= e($orderCount) ?>
        </strong>

    </div>


    <div class="dashboard-card">

        <h2>
            Sales
        </h2>

        <strong>
            $<?= number_format(
                $totalSales,
                2
            ) ?>
        </strong>

    </div>

</div>


<div class="admin-links">

    <a
        class="button"
        href="products.php"
    >
        Manage Products
    </a>


    <a
        class="button"
        href="users.php"
    >
        Manage Users
    </a>


    <a
        class="button"
        href="orders.php"
    >
        Manage Orders
    </a>

</div>


<h2>
    Recent Activity
</h2>


<div class="table-wrapper">

    <table>

        <thead>

            <tr>

                <th>
                    User
                </th>

                <th>
                    Action
                </th>

                <th>
                    Details
                </th>

                <th>
                    Time
                </th>

            </tr>

        </thead>


        <tbody>

            <?php foreach ($logs as $log): ?>

                <tr>

                    <td>
                        <?= e(
                            $log['name']
                            ?? 'System'
                        ) ?>
                    </td>

                    <td>
                        <?= e(
                            $log['action']
                        ) ?>
                    </td>

                    <td>
                        <?= e(
                            $log['details']
                        ) ?>
                    </td>

                    <td>
                        <?= e(
                            $log['created_at']
                        ) ?>
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