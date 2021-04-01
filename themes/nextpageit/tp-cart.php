<?php 
/**
 * Template Name: Cart
 */
get_header();
    $intPointsCount         = Products_Controller::getCartTotalPoints();
    $intProductsCount       = Products_Controller::getCartTotalProducts();
    $args = array(
        'points-count' => $intPointsCount,
        'products-count' => $intProductsCount
    );
?>

<div class="page-container">
    <!--Get Page Content-->
    <?php get_template_part( 'template-parts/content/content-page');?>
    
    <!--Cart Back Buton To Products-->
    <div class="back-to-products">
        <a href="<?php echo home_url().'/shop';?>">
            <i class="fa fa-arrow-left" aria-hidden="true"> Back to Products </i>
        </a>
    </div>

    <!--If cart product is greater than zero-->
    <?php if(!is_user_logged_in()){
        echo Theme_Controller::getShakeError(Theme_Controller::$constantUserNotLoggedIn);
     } else{
        if($intProductsCount > 0){?> 
            <!--Get Cart Table-->
            <?php get_template_part('nextpage-templates/cart');?>
            <!--Cart Meta Information-->
            <?php get_template_part( 'nextpage-templates/cart','meta_information',$args);?>
            <!--Cart Proceed To checkout-->
            <div class="proceed-to-checkout">
                <a href="<?php echo home_url().'/checkout';?>">Proceed To Checkout <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </div>
        <?php } else{
            echo Theme_Controller::getShakeError(Theme_Controller::$constantCartEmpty);    
        }?>
     <?php }?>
</div>
<?php get_footer();?>