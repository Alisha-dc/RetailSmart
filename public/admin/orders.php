<?php

require_once __DIR__ .
    '/../../app/config/helpers.php';

require_once __DIR__ .
    '/../../app/models/Order.php';

require_admin();


$orders =
    Order::all();


$pageTitle =
    'Manage Orders';

require_once
    __DIR__ .
    '/../../app/views/partials/header.php';
?>


<h1>
    Manage Orders
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
                    Customer
                </th>

                <th>
                    Email
                </th>

                <th>
                    Total
                </th>

                <th>
                    Date
                </th>

                <th>
                    Status
                </th>

                <th>
                    Actions
                </th>

            </tr>

        </thead>


        <tbody>

            <?php foreach ($orders as $order): ?>

                <tr>

                    <td>
                        <?= e($order['id']) ?>
                    </td>

                    <td>
                        <?= e($order['name']) ?>
                    </td>

                    <td>
                        <?= e($order['email']) ?>
                    </td>

                    <td>
                        $<?= number_format(
                            (float) $order['total'],
                            2
                        ) ?>
                    </td>

                    <td>
                        <?= e($order['created_at']) ?>
                    </td>

                    <td>
                        <?= e($order['status']) ?>
                    </td>

                    <td>

                        <form
                            method="POST"
                            action="../action.php"
                            class="inline-form"
                        >

                            <input
                                type="hidden"
                                name="action"
                                value="update_order"
                            >

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= csrf_token() ?>"
                            >

                            <input
                                type="hidden"
                                name="order_id"
                                value="<?= $order['id'] ?>"
                            >


                            <select name="status">

                                <?php foreach (
                                    ['Pending', 'Confirmed', 'Packed', 'Completed', 'Cancelled']
                                    as $status
                                ): ?>

                                    <option
                                        value="<?= $status ?>"
                                        <?= $order['status'] === $status ? 'selected' : '' ?>
                                    >
                                        <?= $status ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>


                            <button
                                type="submit"
                                class="small"
                            >
                                Update
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