<?php

require_once __DIR__ . '/../app/config/helpers.php';
require_once __DIR__ . '/../app/models/Product.php';

$pageTitle = 'Products';


// Get search/filter values
$search =
    trim($_GET['search'] ?? '');

$category =
    trim($_GET['category'] ?? '');

$page =
    (int)($_GET['page'] ?? 1);


// Get products
$result =
    Product::getProducts(
        $search,
        $category,
        $page,
        6
    );


$products =
    $result['products'];

$categories =
    Product::categories();


require_once
    __DIR__ .
    '/../app/views/partials/header.php';
?>


<section class="hero">

    <h1>
        RetailSmart
    </h1>

    <p>
        Simple and secure retail management.
    </p>

</section>


<section>

    <h2>
        Product Catalogue
    </h2>


    <!-- Search and filter -->

    <form
        method="GET"
        class="search-form"
    >

        <label for="search">
            Search products
        </label>

        <input
            type="search"
            id="search"
            name="search"
            value="<?= e($search) ?>"
            placeholder="Search by product name..."
        >


        <label for="category">
            Category
        </label>

        <select
            id="category"
            name="category"
        >

            <option value="">
                All categories
            </option>


            <?php foreach ($categories as $cat): ?>

                <option
                    value="<?= e($cat) ?>"
                    <?= $category === $cat
                        ? 'selected'
                        : '' ?>
                >
                    <?= e($cat) ?>
                </option>

            <?php endforeach; ?>

        </select>


        <button type="submit">
            Search
        </button>

    </form>


    <div class="product-grid">

        <?php if (empty($products)): ?>

            <p>
                No products found.
            </p>

        <?php endif; ?>


        <?php foreach ($products as $product): ?>

            <article class="product-card">

                <div class="product-image-wrap">
                    <img
                        src="<?= e($product['image_url'] ?? '/RetailSmart/public/assets/images/product-placeholder.svg') ?>"
                        alt="<?= e($product['name']) ?>"
                        class="product-image"
                    >
                </div>


                <h3>
                    <?= e($product['name']) ?>
                </h3>


                <p>
                    <?= e($product['description']) ?>
                </p>


                <p class="category">
                    <?= e($product['category']) ?>
                </p>


                <strong class="price">
                    $<?= number_format(
                        $product['price'],
                        2
                    ) ?>
                </strong>


                <p>
                    Remaining stock:
                    <?= e($product['remaining_stock'] ?? $product['stock']) ?>
                </p>


                <?php $availableStock = (int) ($product['remaining_stock'] ?? $product['stock'] ?? 0); ?>
                <?php if ($availableStock > 0): ?>

                    <?php if ($availableStock <= 2): ?>
                        <span class="out-stock">
                            Only <?= $availableStock ?> left in stock.
                        </span>
                    <?php endif; ?>

                    <?php if (is_logged_in()): ?>

                        <form
                            method="POST"
                            action="action.php"
                        >

                            <input
                                type="hidden"
                                name="action"
                                value="add_cart"
                            >

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= csrf_token() ?>"
                            >

                            <input
                                type="hidden"
                                name="product_id"
                                value="<?= $product['id'] ?>"
                            >


                            <label>
                                Quantity

                                <input
                                    type="number"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    max="<?= $availableStock ?>"
                                >

                            </label>


                            <button
                                type="submit"
                            >
                                Add to Cart
                            </button>

                        </form>

                    <?php else: ?>

                        <a
                            class="button"
                            href="login.php"
                        >
                            Login to Buy
                        </a>

                    <?php endif; ?>

                <?php else: ?>

                    <span class="out-stock">
                        Out of stock / unavailable
                    </span>

                <?php endif; ?>

            </article>

        <?php endforeach; ?>

    </div>


    <!-- Page -->

    <?php if ($result['pages'] > 1): ?>

        <nav
            class="pagination"
            aria-label="Product pages"
        >

            <?php for (
                $i = 1;
                $i <= $result['pages'];
                $i++
            ): ?>

                <a
                    href="?search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>&page=<?= $i ?>"
                    class="<?= $i === $result['page']
                        ? 'active'
                        : '' ?>"
                >
                    <?= $i ?>
                </a>

            <?php endfor; ?>

        </nav>

    <?php endif; ?>

</section>


<!-- Smart Help -->

<button
    class="smart-help-button"
    onclick="toggleSmartHelp()"
    aria-label="Open Smart Help"
>
    💬
</button>


<div
    id="smartHelp"
    class="smart-help"
    hidden
>

    <h2>
        Smart Help
    </h2>

    <p>
        Ask a question about products,
        orders or shopping.
    </p>


    <input
        type="text"
        id="helpQuestion"
        placeholder="Example: How can I return an order?"
    >


    <button onclick="askSmartHelp()">
        Ask
    </button>


    <div
        id="helpAnswer"
        class="help-answer"
    ></div>

</div>


<?php

require_once
    __DIR__ .
    '/../app/views/partials/footer.php';

?>