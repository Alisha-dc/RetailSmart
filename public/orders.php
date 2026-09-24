<?php

require_once __DIR__ .
    '/../app/config/helpers.php';

require_once __DIR__ .
    '/../app/models/Order.php';

require_login();

$user = current_user();
$orders = Order::forUser((int) $user['id']);

$pageTitle = 'My Orders';

require_once
    __DIR__ .
    '/../app/views/partials/header.php';
?>

<h1>My Orders</h1>

<?php if (empty($orders)): ?>
    <div class="empty-state">
        <p>You have not placed any orders yet.</p>
        <a class="button" href="index.php">Continue Shopping</a>
    </div>
<?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= e($order['id']) ?></td>
                        <td><?= e($order['created_at']) ?></td>
                        <td>$<?= number_format((float) $order['total'], 2) ?></td>
                        <td><strong><?= e($order['status']) ?></strong></td>
                        <td>
                            <a class="button small" href="order.php?id=<?= (int) $order['id'] ?>">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php

require_once
    __DIR__ .
    '/../app/views/partials/footer.php';

?>