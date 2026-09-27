<?php

require_once __DIR__ .
    '/../../app/config/helpers.php';

require_once __DIR__ .
    '/../../app/models/Product.php';

require_admin();


$result =
    Product::getProducts(
        '',
        '',
        1,
        100
    );


$products =
    $result['products'];


$pageTitle =
    'Manage Products';

require_once
    __DIR__ .
    '/../../app/views/partials/header.php';
?>


<h1>
    Manage Products
</h1>


<?php if (!empty($_SESSION['flash'])): ?>

    <div class="alert success">

        <?= e($_SESSION['flash']) ?>

    </div>

    <?php unset($_SESSION['flash']); ?>

<?php endif; ?>


<!-- Add product -->

<section class="admin-form">

    <h2>
        Add New Product
    </h2>


    <form
        method="POST"
        action="../action.php"
    >

        <input
            type="hidden"
            name="action"
            value="create_product"
        >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= csrf_token() ?>"
        >


        <label>
            Product Name

            <input
                type="text"
                name="name"
                required
            >

        </label>


        <label>
            Description

            <textarea
                name="description"
                rows="3"
            ></textarea>

        </label>


        <label>
            Category

            <input
                type="text"
                name="category"
                required
            >

        </label>


        <label>
            Price

            <input
                type="number"
                name="price"
                step="0.01"
                min="0"
                required
            >

        </label>

        <label>
            Image URL

            <input
                type="text"
                name="image_url"
                value="/RetailSmart/public/assets/images/product-placeholder.svg"
                placeholder="/RetailSmart/public/assets/images/product-placeholder.svg"
            >

        </label>


        <label>
            Original Stock

            <input
                type="number"
                name="original_stock"
                min="0"
                required
            >

        </label>

        <label>
            Remaining Stock

            <input
                type="number"
                name="remaining_stock"
                min="0"
                required
            >

        </label>


        <button type="submit">
            Add Product
        </button>

    </form>

</section>


<!-- Product table -->

<h2>
    Existing Products
</h2>


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
                    Category
                </th>

                <th>
                    Price
                </th>

                <th>
                    Original Stock
                </th>

                <th>
                    Remaining Stock
                </th>

                <th>
                    Actions
                </th>

            </tr>

        </thead>


        <tbody>

            <?php foreach ($products as $product): ?>

                <tr>

                    <td>
                        <?= e($product['id']) ?>
                    </td>

                    <td>
                        <?= e($product['name']) ?>
                    </td>

                    <td>
                        <?= e(
                            $product['category']
                        ) ?>
                    </td>

                    <td>
                        $<?= number_format(
                            $product['price'],
                            2
                        ) ?>
                    </td>

                    <td>
                        <?= e($product['original_stock'] ?? $product['stock']) ?>
                    </td>

                    <td>
                        <?= e($product['remaining_stock'] ?? $product['stock']) ?>
                    </td>

                    <td>

                        <a
                            class="button small"
                            href="product_edit.php?id=<?= $product['id'] ?>"
                        >
                            Edit
                        </a>


                        <form
                            method="POST"
                            action="../action.php"
                            class="inline-form"
                        >

                            <input
                                type="hidden"
                                name="action"
                                value="delete_product"
                            >

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= csrf_token() ?>"
                            >

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $product['id'] ?>"
                            >

                            <button
                                type="submit"
                                class="danger small"
                                onclick="return confirm('Delete this product?')"
                            >
                                Delete
                            </button>

                        </form>

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