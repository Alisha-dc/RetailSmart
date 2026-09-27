<?php

require_once __DIR__ .
    '/../app/config/helpers.php';

require_once __DIR__ .
    '/../app/models/Order.php';

require_login();


$orderId =
    (int)($_GET['id'] ?? 0);


$order =
    Order::find($orderId);


if (!$order) {

    http_response_code(404);

    die('Order not found.');
}


$user =
    current_user();


// Customer can only view own order
if (
    $user['role'] !== 'admin' &&
    (int)$order['user_id']
        !== (int)$user['id']
) {

    http_response_code(403);

    die('Access denied.');
}


$items =
    Order::items($orderId);


$pageTitle =
    'Order #' . $orderId;

require_once
    __DIR__ .
    '/../app/views/partials/header.php';
?>


<h1>
    Order #<?= e($order['id']) ?>
</h1>


<p>
    Customer:
    <?= e($order['name']) ?>
</p>


<p>
    Status:
    <strong>
        <?= e($order['status']) ?>
    </strong>
</p>


<p>
    Date:
    <?= e($order['created_at']) ?>
</p>


<div class="table-wrapper">

    <table>

        <thead>

            <tr>

                <th>
                    Product
                </th>

                <th>
                    Price
                </th>

                <th>
                    Quantity
                </th>

                <th>
                    Subtotal
                </th>

            </tr>

        </thead>


        <tbody>

            <?php foreach ($items as $item): ?>

                <tr>

                    <td>
                        <?= e($item['name']) ?>
                    </td>


                    <td>
                        $<?= number_format(
                            $item['price'],
                            2
                        ) ?>
                    </td>


                    <td>
                        <?= e(
                            $item['quantity']
                        ) ?>
                    </td>


                    <td>
                        $<?= number_format(
                            $item['price']
                            * $item['quantity'],
                            2
                        ) ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>


<h2>
    Total:
    $<?= number_format(
        $order['total'],
        2
    ) ?>
</h2>


<?php

require_once
    __DIR__ .
    '/../app/views/partials/footer.php';

?>