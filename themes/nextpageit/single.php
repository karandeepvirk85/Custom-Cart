<?php
/**
 * The template for displaying all single posts
 *
 */

get_header();
?>
<div class="container page-container"> 
<?php 
/* Start the Loop */
while ( have_posts() ) :
	the_post();
	if($post->post_type == 'post'){?>
		<div class="row single-posts-row">
			<div class="col-md-9">
				<?php get_template_part('nextpage-templates/singlepost'); ?>	
			</div>
			<div class="col-md-3 sidbar-container">
				<?php get_template_part('nextpage-templates/nextpagesidebar'); ?>
			</div>
		</div>
	<?php } else{
		get_template_part( 'template-parts/content/content-single' );
	}
	if ( is_attachment() ) {
		// Parent post navigation.
		the_post_navigation(
			array(
				/* translators: %s: Parent post link. */
				'prev_text' => sprintf( __( '<span class="meta-nav">Published in</span><span class="post-title">%s</span>', 'twentytwentyone' ), '%title' ),
			)
		);
	}

	// If comments are open or there is at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}

	// Previous/next post navigation.
	$twentytwentyone_next = is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' );
	$twentytwentyone_prev = is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' );
	
	$strNextLabel = $post->post_type == 'post' ? "Next Post" : "Next Product";
	$strPrevLabel = $post->post_type == 'post' ? "Previous Post" : "Previous Product";
	
	$twentytwentyone_next_label     = esc_html__( $strNextLabel, 'twentytwentyone' );
	$twentytwentyone_previous_label = esc_html__( $strPrevLabel, 'twentytwentyone' );

	the_post_navigation(
		array(
			'next_text' => '<p class="meta-nav">' . $twentytwentyone_next_label . $twentytwentyone_next . '</p><p class="post-title">%title</p>',
			'prev_text' => '<p class="meta-nav">' . $twentytwentyone_prev . $twentytwentyone_previous_label . '</p><p class="post-title">%title</p>',
		)
	);
endwhile; // End of the loop.
?>
</div>
<?php get_footer();?>
