<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

$args = array(
	'paged' => $paged,
	'max_num_pages' => $wp_query->max_num_pages
);

$description = get_the_archive_description();
?>
<div class="page-container container">
	<?php if(have_posts()){ 
		  $intCount = 0;
		?>
		<header class="page-header alignwide">
			<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			<?php if($description){ ?>
				<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
			<?php } ?>
		</header>

		<div class="row">
        	<div class="col-md-9">
            	<div class="row posts-row">
					<?php while (have_posts()){ 
						$intCount++;
						the_post(); 
						get_template_part('nextpage-templates/allposts');
						echo ($intCount % 2 == 0) ? '</div><div class="row posts-row">' : "";
					}?>
				</div>
			</div>
			<div class="col-md-3 sidbar-container">
            	<?php get_template_part( 'nextpage-templates/nextpagesidebar' ); ?>
        	</div>   
		</div
		>
		<div class="row">
        	<div class="col-md-12 pagination-container">
            	<?php get_template_part( 'nextpage-templates/nextpage','custom_pagination',$args); ?>
        	</div>
    	</div>
	<?php } else { ?>
		<div class="col-md-12">
			<p><?php _e( 'There no posts to display.' ); ?></p>
		</div>
	<?php } ?>
</div>
<?php get_footer(); ?>
