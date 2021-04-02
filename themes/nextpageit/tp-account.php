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
    <div class="account-info-container">
        <p>First Name: <span id="account_first_name"></span></p>
        <p>Last Name: <span id="account_last_name"></span></p>
        <p>User ID: <span id="account_response_id"></span></p>
        <p>Password: <span id="account_password"></span></p>
        <p>Email: <span id="account_email"></span></p>
        <p>Phone Number: <span id="account_phone_number"></span></p>
        <p>User Points: <span id="account_user_points"></span></p>
    </div>
</div>
<?php get_footer();?>