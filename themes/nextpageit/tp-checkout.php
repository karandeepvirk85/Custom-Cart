<?php 
/**
 * Template Name: Checkout
 */
get_header();

// {
//     "data": {
//         "id": 74,
//         "firstName": "Cynthia",
//         "lastName": "Acosta",
//         "password": "",
//         "roleId": 2,
//         "isBlocked": false,
//         "email": "sampleuser@yopmail.com",
//         "gender": "male",
//         "dob": "0001-01-01T00:00:00Z",
//         "phoneNumber": "918295458574",
//         "isPhoneNumberVerified": true,
//         "isEmailVerified": true,
//         "token": "9da12e9d-76a2-4c55-a4ae-ce97fcfda8c1",
//         "addedBy": 0,
//         "lastModBy": 0,
//         "addedOn": "2021-03-19T07:42:41Z",
//         "lastModOn": "2021-03-23T05:54:58Z",
//         "roleName": "USER",
//         "isAdmin": false,
//         "isClient": false,
//         "isUser": true,
//         "location": "Ea id facilis odit q",
//         "parentalStatus": null,
//         "maritalStatus": null,
//         "educationLevel": null,
//         "occupation": null,
//         "country": "Similique consequatu",
//         "state": "Non fugit sint dol",
//         "city": "Tempor impedit ut l",
//         "town": null,
//         "pinCode": "173",
//         "address1": "274 Clarendon Extension",
//         "address2": "Commodi laborum Atq",
//         "fileId": 183,
//         "fileName": null,
//         "fileUrl": null,
//         "hasPaymentMethod": false
//     },
//     "message": "Successfull",
//     "status": 200,
//     "errors": {}
// }
?>
<div class="page-container">
    <?php get_template_part( 'template-parts/header/entry-header');?> 
    <?php get_template_part( 'template-parts/content/content-page');?> 
    
    <div class="checkout-user-details">
        <div class="spinner-container">
            <div class="account-spinner"><i class="fa-spin fa-3x fa fa-cog" aria-hidden="true"></i></div>
        </div>
        <div class="account-info-container">
            <div class="account-info-container-inner">
                <p>First Name: <span id="account_first_name"></span></p>
                <p>Last Name: <span id="account_last_name"></span></p>
                <p>User ID: <span id="account_response_id"></span></p>
                <p>Password: <span id="account_password"></span></p>
                <p>Email: <span id="account_email"></span></p>
                <p>Phone Number: <span id="account_phone_number"></span></p>
                <p>User Points: <span id="account_user_points"></span></p>
            </div>
            <div class="account-info-container-inner">
                <p>Country: <span id="account_country"></span><p>
                <p>State: <span id="account_state"></span><p>
                <p>City: <span id="account_city"></span><p>
                <p>Town: <span id="account_town"></span><p>
                <p>PinCode: <span id="account_pinCode"></span><p>
                <p>Address1: <span id="account_address1"></span><p>
                <p>Address2: <span id="account_address2"></span><p>
            </div>
        </div>
    </div>
    <p class="after-ajax-call-message"></p>
    <div class="cart-bottom-part">
        <div class="cart-summary">
            Total Products: <?php echo Products_Controller::getCartTotalProducts(); ?>
            Total Points: <?php echo Products_Controller::getCartTotalPoints();?>
        </div>
    </div>
    <div class="cart-checkout-container">
        <button class="checkout-and-reedem">Reedem and Checkout</button>
    </div>
</div>
<?php get_footer(); ?>