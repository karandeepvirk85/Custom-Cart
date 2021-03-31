<?php 
/**
 * Template Name: Products
 */

// Get Site Header
get_header();
// Set Empty Array
$arrProducts = array();
// Get All Products
if(class_exists('Theme_Controller')){
    $paged = Theme_Controller::getPagedQuery();
}
if(class_exists('Product')){
    $arrProducts = Product::getProducts($paged);
    // Set Args for Pagination
    $args = array(
        'paged' => $paged,
        'max_num_pages' => $arrProducts->max_num_pages
    );
}
$intCount = 0;
?>
<div class="page-container">
    <?php
        get_template_part( 'template-parts/content/content-page');
    ?>
    <div class="products-row">
        <?php if ($arrProducts->have_posts()){
            $intCount = 0;
            while ($arrProducts->have_posts() ) : $arrProducts->the_post();
                get_template_part('nextpage-templates/products');
            endwhile;
            wp_reset_postdata(); 
        }
        ?>
    </div>
    <div class="row">
        <!-------INLUDE PAGINATION------->
        <div class="col-md-12 pagination-container">
            <?php get_template_part( 'nextpage-templates/nextpage','custom_pagination',$args); ?>
        </div>
    </div>
</div>

<?php get_footer();?>