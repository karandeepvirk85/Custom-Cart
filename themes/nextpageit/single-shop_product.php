<?php 
/**
 * Shop 
 */
get_header();
?>
<div class="page-container">
    <div class="single-product-container">
        <div class="single-product-image-container">
            <div class="single-product-image" style="background-image:url(<?php echo Theme_Controller::getPostImage($post->ID,'full');?>);"></div>    
        </div>

        <div class="single-product-cart-container">
            <div class="single-product-title">
                <h2><?php echo $post->post_title;?></h2>
                <p class="animate__fadeInDown animate__animated after-ajax-call-message"></p>
            </div>
            
            <div class="single-product-points">
                <p><span>Points Per Product:</span> <?php echo Products_Controller::getPoints($post->ID)?></p>
            </div>

            <div class="single-product-select">
                <span>Quantity: </span> <input data-product-point="<?php echo Products_Controller::getPoints($post->ID);?>" type="number" id="<?php echo $post->ID;?>-quantity" class="product-quantity">      
                <span id="points-on-selection"></span>
            </div>

            <div class="single-product-available">
                <p><span>Available:</span> <?php echo Products_Controller::getAvailableProducts($post->ID)?></p>
            </div>  
            <div class="single-product-add-to-cart">
                <button class ="add-to-cart" data-id="<?php echo $post->ID;?>">Add To Cart</button>
                <a class="hide-button animate__fadeInUp animate__animated" href="<?php echo home_url()?>/cart">View Cart <i class="fa fa-cart-plus" aria-hidden="true"></i></a>
            </div>
            <div class="product-short-description">
                <?php echo Products_Controller::getShortDescription($post->ID); ?>
            </div>
        </div>
    </div>
    <div class="tab">
        <button class="tablinks" onclick="objTheme.openTab(event, 'full-description')" id="defaultOpen">Description</button>
        <button class="tablinks" onclick="objTheme.openTab(event, 'extra-tab')">Extra</button>
    </div>

    <div id="full-description" class="tabcontent">
        <span onclick="this.parentElement.style.display='none'" class="topright">&times</span>
        <h3>Description</h3>
        <?php echo Theme_Controller::contentFilter($post->post_content,false);?>
    </div>

    <div id="extra-tab" class="tabcontent">
        <span onclick="this.parentElement.style.display='none'" class="topright">&times</span>
        <h3>Extra</h3>
        <p>More Information can be displayed here.</p> 
    </div>
    <?php 
    the_post_navigation(
        array(
            'next_text' => '<p class="meta-nav">Next Product</p>',
            'prev_text' => '<p class="meta-nav">Previous Product</p>',
        )
    );
    ?>
</div>
<?php get_footer();?>