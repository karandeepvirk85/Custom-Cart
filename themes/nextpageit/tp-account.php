<?php 
/**
 * Template Name: Account 
 */
get_header();
?>
<div class="page-container">
    <div class="spinner-container">
        <div class="account-spinner"><i class="fa-spin fa-3x fa fa-cog" aria-hidden="true"></i></div>
    </div>
    <div class="account-info-main">
            <h2>User Details</h2>
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
                    <p>Country: <span id="account_country"></span></p>
                    <p>State: <span id="account_state"></span></p>
                    <p>City: <span id="account_city"></span></p>
                    <p>Town: <span id="account_town"></span></p>
                    <p>PinCode: <span id="account_pinCode"></span></p>
                    <p>Address1: <span id="account_address1"></span></p>
                    <p>Address2: <span id="account_address2"></span></p>
                </div>
            </div>
        </div>
</div>
<?php get_footer();?>