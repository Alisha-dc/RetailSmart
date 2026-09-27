<?php

require_once __DIR__ .
    '/../app/config/helpers.php';

require_once __DIR__ .
    '/../app/controllers/auth.php';

require_once __DIR__ .
    '/../app/controllers/orders.php';

require_once __DIR__ .
    '/../app/controllers/products.php';

require_once __DIR__ .
    '/../app/controllers/users.php';

require_once __DIR__ .
    '/../app/models/Users.php';


$action =
    $_POST['action']
    ?? $_GET['action']
    ?? '';



// LOGOUT


if ($action === 'logout') {

    require_login();

    logout_user();

    redirect('index.php');
}


// Everything below uses POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect('index.php');
}


verify_csrf();


// ADD TO CART


if ($action === 'add_cart') {

    require_login();


    $productId =
        (int)($_POST['product_id'] ?? 0);

    $quantity =
        (int)($_POST['quantity'] ?? 0);


    $errors =
        add_to_cart(
            $productId,
            $quantity
        );


    if (!empty($errors)) {

        $_SESSION['flash'] =
            implode(' ', $errors);

    } else {

        $_SESSION['flash'] =
            'Product added to cart.';
    }


    redirect('index.php');
}



// REMOVE CART ITEM


if ($action === 'remove_cart') {

    require_login();


    $productId =
        (int)($_POST['product_id'] ?? 0);


    remove_from_cart($productId);


    $_SESSION['flash'] =
        'Product removed from cart.';


    redirect('cart.php');
}


// CREATE ORDER


if ($action === 'create_order') {

    require_login();


    $result =
        create_order();


    if ($result['success']) {

        redirect(
            'order.php?id=' .
            $result['order_id']
        );
    }


    $_SESSION['flash'] =
        $result['message'];


    redirect('cart.php');
}



// ADMIN: CREATE PRODUCT


if ($action === 'create_product') {

    require_admin();

    $originalStock = trim($_POST['original_stock'] ?? $_POST['stock'] ?? '');
    $remainingStock = trim($_POST['remaining_stock'] ?? $originalStock);
    $imageUrl = trim($_POST['image_url'] ?? '');

    $errors =
        create_product(
            trim($_POST['name'] ?? ''),
            trim($_POST['description'] ?? ''),
            trim($_POST['category'] ?? ''),
            trim($_POST['price'] ?? ''),
            $originalStock,
            $remainingStock,
            $imageUrl
        );


    if (!empty($errors)) {

        $_SESSION['flash'] =
            implode(' ', $errors);
    }


    redirect('admin/products.php');
}


// ADMIN: UPDATE PRODUCT


if ($action === 'update_product') {

    require_admin();

    $originalStock = trim($_POST['original_stock'] ?? $_POST['stock'] ?? '');
    $remainingStock = trim($_POST['remaining_stock'] ?? $originalStock);
    $imageUrl = trim($_POST['image_url'] ?? '');

    $errors =
        update_product(
            (int)($_POST['id'] ?? 0),
            trim($_POST['name'] ?? ''),
            trim($_POST['description'] ?? ''),
            trim($_POST['category'] ?? ''),
            trim($_POST['price'] ?? ''),
            $originalStock,
            $remainingStock,
            $imageUrl
        );


    if (!empty($errors)) {

        $_SESSION['flash'] =
            implode(' ', $errors);
    }


    redirect('admin/products.php');
}


// ADMIN: DELETE PRODUCT


if ($action === 'delete_product') {

    require_admin();


    $id =
        (int)($_POST['id'] ?? 0);


    if (!delete_product($id)) {

        $_SESSION['flash'] =
            'Product could not be deleted.';
    }


    redirect('admin/products.php');
}



// ADMIN: CHANGE USER ROLE


if ($action === 'change_role') {

    require_admin();


    $userId =
        (int)($_POST['user_id'] ?? 0);

    $role =
        $_POST['role'] ?? 'customer';


    if (!change_user_role(
        $userId,
        $role
    )) {

        $_SESSION['flash'] =
            'Could not update user role.';
    }


    redirect('admin/users.php');
}



// ADMIN: DELETE USER


if ($action === 'delete_user') {

    require_admin();


    $userId =
        (int)($_POST['user_id'] ?? 0);


    if (!delete_user($userId)) {

        $_SESSION['flash'] =
            'User could not be deleted.';
    }


    redirect('admin/users.php');
}



// ADMIN: UPDATE ORDER


if ($action === 'update_order') {

    require_admin();


    $orderId =
        (int)($_POST['order_id'] ?? 0);

    $status =
        $_POST['status'] ?? 'Pending';


    if (
        Order::updateStatus(
            $orderId,
            $status
        )
    ) {

        log_action(
            'Order Status Updated',
            "Order ID: $orderId, Status: $status"
        );

    } else {

        $_SESSION['flash'] =
            'Invalid order status.';
    }


    redirect('admin/orders.php');
}


// Unknown action
redirect('index.php');