
// RETAILSMART SMART HELP ASSISTANT



// Open or close Smart Help
function toggleSmartHelp() {

    const box =
        document.getElementById('smartHelp');

    if (!box) {
        return;
    }


    box.hidden = !box.hidden;
}


// Answer customer question
function askSmartHelp() {

    const input =
        document.getElementById('helpQuestion');

    const answer =
        document.getElementById('helpAnswer');


    if (!input || !answer) {
        return;
    }


    const question =
        input.value
            .toLowerCase()
            .trim();


    if (question === '') {

        answer.textContent =
            'Please type a question.';

        return;
    }


    let response =
        'Sorry, I do not understand that question yet. Please contact our support team.';


    // Product-related questions
    if (
        question.includes('product') ||
        question.includes('catalogue') ||
        question.includes('catalog') ||
        question.includes('item')
    ) {

        response =
            'You can search products by name and filter them by category on the Products page.';
    }


    // Stock questions
    else if (
        question.includes('stock') ||
        question.includes('available')
    ) {

        response =
            'The product page shows the current available stock for each product.';
    }


    // Cart questions
    else if (
        question.includes('cart') ||
        question.includes('shopping')
    ) {

        response =
            'Add products to your cart, review the quantities and then select Place Order.';
    }


    // Order questions
    else if (
        question.includes('order') ||
        question.includes('purchase')
    ) {

        response =
            'After placing an order, you can view its status from My Orders.';
    }


    // Return/refund questions
    else if (
        question.includes('return') ||
        question.includes('refund')
    ) {

        response =
            'For returns or refunds, please contact the store administrator with your order number.';
    }


    // Login questions
    else if (
        question.includes('login') ||
        question.includes('password') ||
        question.includes('account')
    ) {

        response =
            'Use your registered email and password to log in. Passwords are securely hashed in the database.';
    }


    // Payment questions
    else if (
        question.includes('payment') ||
        question.includes('pay')
    ) {

        response =
            'This assessment prototype records orders but does not process real payments.';
    }


    answer.textContent = response;
}