<?php

require_once __DIR__ .
    '/../app/config/helpers.php';

require_once __DIR__ .
    '/../app/models/Product.php';

require_login();


$cart = $_SESSION['cart'] ?? [];

$cartItems = [];
$total = 0;
$unavailableItems = [];

foreach ($cart as $productId => $quantity) {
    $product = Product::find((int) $productId);

    if (!$product) {
        $unavailableItems[] = 'A product in your cart is no longer available.';
        continue;
    }

    $availableStock = (int) ($product['remaining_stock'] ?? $product['stock'] ?? 0);

    if ($availableStock <= 0) {
        $unavailableItems[] = $product['name'] . ' is out of stock.';
        continue;
    }

    if ($quantity > $availableStock) {
        $unavailableItems[] = $product['name'] . ' only has ' . $availableStock . ' item(s) left.';
        $quantity = $availableStock;
    }

    $subtotal = $product['price'] * $quantity;
    $total += $subtotal;

    $cartItems[] = [
        'product' => $product,
        'quantity' => $quantity,
        'subtotal' => $subtotal,
    ];
}

$pageTitle = 'Shopping Cart';

require_once
    __DIR__ .
    '/../app/views/partials/header.php';
?>


<h1>
    Shopping Cart
</h1>


<?php if (empty($cartItems)): ?>

    <div class="empty-state">

        <p>
            Your cart is empty.
        </p>

        <a
            class="button"
            href="index.php"
        >
            Continue Shopping
        </a>

    </div>

<?php else: ?>

    <?php if (!empty($unavailableItems)): ?>
        <div class="alert error">
            <?php foreach ($unavailableItems as $message): ?>
                <div><?= e($message) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

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

                    <th>
                        Action
                    </th>

                </tr>

            </thead>


            <tbody>

                <?php foreach ($cartItems as $item): ?>

                    <tr>

                        <td>
                            <?= e($item['product']['name']) ?>
                        </td>


                        <td>
                            $<?= number_format(
                                $item['product']['price'],
                                2
                            ) ?>
                        </td>


                        <td>
                            <?= e($item['quantity']) ?>
                        </td>


                        <td>
                            $<?= number_format(
                                $item['subtotal'],
                                2
                            ) ?>
                        </td>


                        <td>

                            <form
                                method="POST"
                                action="action.php"
                            >

                                <input
                                    type="hidden"
                                    name="action"
                                    value="remove_cart"
                                >

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= csrf_token() ?>"
                                >

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?= $item['product']['id'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="danger"
                                >
                                    Remove
                                </button>

                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>


    <div class="cart-total">

        <h2>
            Total:
            $<?= number_format($total, 2) ?>
        </h2>


        <?php if (empty($unavailableItems)): ?>
            <form
                method="POST"
                action="action.php"
            >

                <input
                    type="hidden"
                    name="action"
                    value="create_order"
                >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= csrf_token() ?>"
                >

                <button type="submit">
                    Place Order
                </button>

            </form>
        <?php else: ?>
            <p class="out-stock">
                You cannot place this order until the unavailable items are removed.
            </p>
        <?php endif; ?>

    </div>

<?php endif; ?>


<?php

require_once
    __DIR__ .
    '/../app/views/partials/footer.php';

?>