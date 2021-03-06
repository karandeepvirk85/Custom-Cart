<?php 
/**
 * Display Products
 */

?>
<?php if(class_exists('Products_Controller')){
    if(!Products_Controller::isProductDisabled($post->ID)){?>
        <div class="product-main-container" data-permalink ="<?php echo get_permalink($post->ID);?>">
            <div class="image-container-outer">
                <div class="image-container-inner" style="background-image:url(<?php echo Theme_Controller::getPostImage($post->ID,'medium');?>);"></div>
            </div>
            <div class="title-container">
                <h2><?php echo $post->post_title;?></h2>
            </div>
            <div class="point-container">
                <p><span>Points:</span> <strong><?php echo Products_Controller::getPoints($post->ID)?></strong></p>
            </div>
            <div class="point-container">
                <p><span>Available:</span> <strong><?php echo Products_Controller::getAvailableProducts($post->ID)?></strong></p>
            </div>
            <div class="button-container">
                <button>Reedem</button>
            </div>
        </div>
    <?php }?>
<?php }?>
