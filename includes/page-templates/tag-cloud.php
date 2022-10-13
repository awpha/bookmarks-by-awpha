<?php
/**
 * Template for displaying Tag Cloud.
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

<?php if ( is_front_page() && is_home() ) : ?>
	<?php get_template_part( 'global-templates/hero' ); ?>
<?php endif; ?>

<div id="index-wrapper" class="wrapper mt-5 pt-1 pt-lg-2">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check and opens the primary div -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

			<main class="site-main mb-1" id="main">

				<section <?php post_class('p-1 mx-n1 border-bottom border-0'); ?> >

					<div class="row g-0 d-flex align-items-center">

						<header class="entry-header col-12 col-md-1 align-self-start">

							<?php the_title(); ?>

						</header><!-- .entry-header -->

						<div class="entry-content col-12 text-reset">

							<?php 
								//Tag Cloud
								$tag_cloud_args = array(
									'smallest'   => 1,
									'largest'    => 4,
									'unit'       => 'rem',
									'number'     => 100,
									'format'     => 'flat',
									'separator'  => "\n",
									'orderby'    => 'name',
									'order'      => 'ASC',
									'exclude'    => '',
									'include'    => '',
									'link'       => 'view',
									'taxonomy'   => 'post_tag',
									'post_type'  => '',
									'echo'       => true,
									'show_count' => 0,
								);

								wp_tag_cloud( $tag_cloud_args ); 
							?>
							

						</div><!-- .entry-content -->
						
					</div>

				</section><!-- #post-## -->

			</main><!-- #main -->

			<!-- The pagination component -->
			<?php understrap_pagination(); ?>

			<!-- Do the right sidebar check -->
			<?php get_template_part( 'global-templates/right-sidebar-check' ); ?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #index-wrapper -->

<?php
get_footer();