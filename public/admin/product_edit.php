<?php

require_once __DIR__ .
    '/../../app/config/helpers.php';

require_once __DIR__ .
    '/../../app/models/Product.php';

require_admin();


$id =
    (int)($_GET['id'] ?? 0);


$product =
    Product::find($id);


if (!$product) {

    http_response_code(404);

    die('Product not found.');
}


$pageTitle =
    'Edit Product';

require_once
    __DIR__ .
    '/../../app/views/partials/header.php';
?>


<h1>
    Edit Product
</h1>


<form
    method="POST"
    action="../action.php"
    class="admin-form"
>

    <input
        type="hidden"
        name="action"
        value="update_product"
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


    <label>
        Product Name

        <input
            type="text"
            name="name"
            value="<?= e($product['name']) ?>"
            required
        >

    </label>


    <label>
        Description

        <textarea
            name="description"
            rows="5"
        ><?= e(
            $product['description']
        ) ?></textarea>

    </label>


    <label>
        Category

        <input
            type="text"
            name="category"
            value="<?= e($product['category']) ?>"
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
            value="<?= e($product['price']) ?>"
            required
        >

    </label>

    <label>
        Image URL

        <input
            type="text"
            name="image_url"
            value="<?= e($product['image_url'] ?? '/RetailSmart/public/assets/images/product-placeholder.svg') ?>"
            placeholder="/RetailSmart/public/assets/images/product-placeholder.svg"
        >

    </label>


    <label>
        Original Stock

        <input
            type="number"
            name="original_stock"
            min="0"
            value="<?= e($product['original_stock'] ?? $product['stock']) ?>"
            required
        >

    </label>

    <label>
        Remaining Stock

        <input
            type="number"
            name="remaining_stock"
            min="0"
            value="<?= e($product['remaining_stock'] ?? $product['stock']) ?>"
            required
        >

    </label>


    <button type="submit">
        Save Changes
    </button>


    <a
        class="button secondary"
        href="products.php"
    >
        Cancel
    </a>

</form>


<?php

require_once
    __DIR__ .
    '/../../app/views/partials/footer.php';

?>