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
    <div class="checkout-user-details">
        <div class="checkout-info"> 
            <div class="checkout-user-left">
                <p><span>First Name:</span> Cynthia</p>
                <p><span>Last Name:</span> Acosta</p>
                <p><span>Email:</span> ampleuser@yopmail.com</p>
                <p><span>Phone Number:</span> 918295458574</p>
                <p><span>Type:</span> User</p>
            </div>
            <div class="checkout-user-right">
                <p><span>Address:</span> 74 Clarendon Extension</p>
                <p><span>Location:</span> Ea id facilis odit q</p>
                <p><span>Country:</span> Similique consequatu</p>
                <p><span>State:</span> Non fugit sint dol</p>
                <p><span>City:</span> TemTempor impedit ut lpor</p>
                <p><span>Town:</span> lorem ipsum</p>
                <p><span>Pincode:</span> 173</p>
            </div>
        </div>
    </div>

    <div class="cart-bottom-part">
        <div class="cart-summary">
            Total Products: <?php echo Products_Controller::getCartTotalPoints(); ?>
            Total Points: <?php echo Products_Controller::getCartTotalProducts();?>
        </div>
    </div>
    <div class="cart-checkout-container">
        <button class="checkout-and-reedem">Reedem and Checkout</button>
    </div>
</div>
<?php get_footer(); ?>