<?php 
/**
 * Template Name: Faq 
 */
get_header();
// Get Paged Query
$paged = Theme_Controller::getPagedQuery();
// Order Meta
$strOrderMeta = '_meta_faq_order';
// Get Posts Query
$arrFaqs = Theme_Controller::getAllPosts($paged,'faq',-1, $strOrderMeta);
?>

<div class="page-container">
    <?php get_template_part('nextpage-templates/entry-header');?> 
    <?php get_template_part('nextpage-templates/content-page');?> 

    <?php if ($arrFaqs->have_posts()){
        while ($arrFaqs->have_posts() ) : $arrFaqs->the_post();?>
            <button class="accordion"><?php echo $post->post_title?></button>
            <div class="panel">
                <p><?php echo $post->post_content;?></p>
            </div>                        
            <?php
        endwhile;
        wp_reset_postdata(); 
    } ?>
</div>

<?php get_footer();?>
