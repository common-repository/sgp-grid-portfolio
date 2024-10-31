<?php get_header(); ?>

	<?php while( have_posts() ) : the_post(); ?>
	<section class="sgp_section">
		<div class="sgp_content_area">
			<article class="sgp_header">
				<header class="sgp-entry-header">
					<h2><?php the_title(); ?></h2>
				</header>
				<!-- .entry-header -->
			</article>
			<div class="sgp_entry-content">
				<?php 
				if( has_post_thumbnail() )
					the_post_thumbnail(); ?>
				<?php the_content(); ?>
			</div>
		</div>
		<div class="sgp_widgets">
			<?php 
			$sidebar_value = get_post_meta( get_the_id(), '_sgp_widgets_another', true );
			$sidebar_show = get_post_meta( get_the_id(), '_sgp_widgets_show', true );
			$sidebar_v = isset( $sidebar_value ) ? $sidebar_value : '';
			$sidebar_s = isset( $sidebar_show ) ? $sidebar_show : '';
			$show = (int)$sidebar_s;
			if( function_exists('sgp_registered_siderbar')) 
			sgp_registered_siderbar( $sidebar_v, $show);
			?>
		</div>
	</section>
	<?php endwhile;?>

<?php get_footer(); ?>