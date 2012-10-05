<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

$options = get_option('vp');
?>

		</div><!-- #main -->
	</div><!-- #page -->
</div><!-- #page-wrapper -->
	
<footer id="colophon" role="contentinfo">

		<?php
			/* A sidebar in the footer? Yep. You can can customize
			 * your footer with three columns of widgets.
			 */
			if ( ! is_404() )
				get_sidebar( 'footer' );
		?>

		<div id="site-generator">
			<?php
			echo( do_shortcode( $options['footer'] ) );
			?>
		</div>
</footer><!-- #colophon -->

<?php wp_footer(); ?>

</body>
</html>