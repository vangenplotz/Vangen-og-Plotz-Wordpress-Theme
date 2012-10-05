<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

// Position
$position = simple_fields_get_post_value( get_the_ID(), array( 3,1 ), $single = true);

// Email
$email = simple_fields_get_post_value( get_the_ID(), array( 3,2 ), $single = true);
if( strlen( trim( $email ) ) > 0 ) {
	$email = '<a href="mailto:' . $email . '">' . $email . '</a>';
}

// Telephone
$telephone = simple_fields_get_post_value( get_the_ID(), array( 3,3 ), $single = true);

// Linkedin
$linkedin = simple_fields_get_post_value( get_the_ID(), array( 3,4 ), $single = true);
if( strlen( trim( $linkedin ) ) > 0 ) {
	$linkedin = '<a href="http://www.linkedin.com/in/' . $linkedin . '" target="_blank">linkedin.com/in/' . $linkedin . '</a>';
}

// Twitter
$twitter = simple_fields_get_post_value( get_the_ID(), array( 3,5 ), $single = true);
if( strlen( trim( $twitter ) ) > 0 ) {
	$twitter = '<a href="https://twitter.com/' . $twitter . '" target="_blank">twitter.com/' . $twitter . '</a>';
}

// Create array to loop through and output details list, key is used as label
$project_details_list_items_setup = array(
	'E-post:' => $email,
	'Telefonnummer:' => $telephone,
	'Linkedin:' => $linkedin,
	'Twitter:' => $twitter
);
$project_details_list_items = Array();
foreach( $project_details_list_items_setup as $label => $description ) {
	if( strlen(trim( $description )) > 0 ) {
		$project_details_list_items[$label] = $description;
	}
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="slider">
		<?php echo do_shortcode('[vp-orbit-slider]'); ?>
	</div>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php twentyeleven_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
		<?php if( trim( strlen( $position ) ) > 0 ) : ?>
			<em class="sub-heading">
				<?php echo( $position ); ?>
			</em>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if( count( $project_details_list_items ) > 0 ) : ?>
	<div id="project-details">
		<?php if( has_post_thumbnail() ) : ?>
		<div class="project-details-thumb-wrapper">
			<?php the_post_thumbnail( 'thumbnail' ); ?>
		</div>
		<?php endif; ?>
		<h2 class="assistive-text">Prosjektdetaljer</h2>
		<dl class="project-details-list">
			<?php
			foreach( $project_details_list_items as $project_details_label => $project_details_content ) {
				if( strlen(trim( $project_details_content )) ) {
					echo('<dt>' . $project_details_label . '</dt>');
					echo('<dd>' . $project_details_content . '</dd>');
				}
			}
			?>
		</dl>
	</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php echo( do_shortcode( '[ansattliste show_all="true" title="Andre ansatte"]' ) ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
