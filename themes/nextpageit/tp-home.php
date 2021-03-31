<?php
/**
 * Template Name: Home
 */
get_header();
if(class_exists('Theme_Controller')){
    $paged = Theme_Controller::getPagedQuery();
}
?>

<div class="page-container">
    <?php 
        // Include Entry Header
        get_template_part( 'template-parts/header/entry-header' ); 
        // Include Page Content
        get_template_part( 'template-parts/content/content-page' ); 
        // Get Posts Query
        $allPostsWPQuery = new WP_Query(
            array(
                'post_type'     =>'post', 
                'post_status'   =>'publish', 
                'posts_per_page'=> 6,
                'paged'         => $paged,
                'orderby'       => 'date',
                'order'         => 'DESC',
            )
        );
        // Set Args for Pagination
        $args = array(
            'paged' => $paged,
            'max_num_pages' => $allPostsWPQuery->max_num_pages
        );
    ?>
    
    <div class="row">
        <div class="col-md-9">
            <div class="row posts-row">
                <?php if ($allPostsWPQuery->have_posts()){
                    $intCount = 0;
                    while ($allPostsWPQuery->have_posts() ) : $allPostsWPQuery->the_post();
                        $intCount++;
                        get_template_part( 'nextpage-templates/allposts' );
                        echo ($intCount % 2 == 0) ? '</div><div class="row posts-row">' : "";
                     endwhile;
                     wp_reset_postdata(); 
                 } else { ?>
                    <div class="col-md-12">
                        <p><?php _e( 'There no posts to display.' ); ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="col-md-3 sidbar-container">
            <!-------INLUDE SIDE BAR------->
            <?php  get_template_part('nextpage-templates/nextpagesidebar');?>
        </div>    
    </div>

    <div class="row">
        <!-------INLUDE PAGINATION------->
        <div class="col-md-12 pagination-container">
            <?php get_template_part( 'nextpage-templates/nextpage','custom_pagination',$args); ?>
        </div>
    </div>
</div>

<?php get_footer();?>