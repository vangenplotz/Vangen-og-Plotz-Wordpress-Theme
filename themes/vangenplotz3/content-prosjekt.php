<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

// Link
$web_address = simple_fields_get_post_value( get_the_ID(), array( 2,1 ), $single = true);
$web_address_short = preg_replace( "#^[^:/.]*[:/]+#i", "", preg_replace( "{/$}", "", $web_address ) );
if( strlen( trim( $web_address ) ) > 0 ) {
	$web_address = '<a href="' . $web_address . '" target="_blank" title="Besøk '. $web_address_short . '. Lenken åpnes i et nytt vindu.">' . $web_address_short . '</a>';
}

// iTunes link
$appstore = simple_fields_get_post_value( get_the_ID(), array( 2,2 ), $single = true);
if( strlen( trim( $appstore ) ) > 0 ) {
	$appstore = '<a href="' . $appstore . '" target="_blank" title="Se appen i AppStore">AppStore</a>';
}

// Google Play link
$google_play = simple_fields_get_post_value( get_the_ID(), array( 2,3 ), $single = true);
if( strlen( trim( $google_play ) ) > 0 ) {
	$google_play = '<a href="' . $google_play . '" target="_blank" title="Se appen på Google Play">Google Play</a>';
}

// Project tags
$tags = get_the_terms( get_the_ID(), 'prosjektstikkord');
$project_tags = '';
if( $tags && !is_wp_error( $tags ) && count( $tags ) > 0 ) {
	$is_first = true;
	if( is_array( $tags ) ) {
		foreach( $tags as $tag ) {
			if( $is_first ) {
				$is_first = false;
			}
			else {
				$project_tags .= ', ';
			}
			if( $tag->count > 1 ) {
				$project_tags .= '<a href="' . get_term_link( $tag ) . '" title="Se alle ' . $tag->count . ' prosjekter vi har hatt med med stikkord ' . $tag->name . '">';
			}
			$project_tags .= $tag->name . '</a>';
			if( $tag->count > 1 ) {
				$project_tags .= '</a>';
			}
		}
	}
	else {
	 	$project_tags = $tags->name;
	}
}

// Customers details
$customers_details = '';
$customer_details = '';
$customers = get_the_terms( get_the_ID(), 'kunde');
if( count( $customers ) > 1 ) {
	$customers_details = '<ul class="customer-list">';
	foreach( $customers as $customer ) {
		$customers_details .= '<li>';
		if( $customer->count > 1 ) {
			$customers_details .= '<a href="' . get_term_link( $customer ) . '" title="Se alle ' . $customer->count . ' prosjekter vi har hatt med ' . $customer->name . '">';
		}
		$customers_details .= $customer->name . '</a>';
		if( $customer->count > 1 ) {
			$customers_details .= '</a>';
		}
		$customers_details .= '</li>';
	}
	$customers_details .= '</ul>';
	$customer_details = '';
}
elseif( $customers && !is_wp_error( $customers ) && count( $customers ) > 0 ) {
	$customer_details = '';
	if( is_array( $customers ) ) {
		foreach( $customers as $customer ) {
			if( $customer->count > 1 ) {
				$customer_details .= '<a href="' . get_term_link( $customer ) . '" title="Se alle ' . $customer->count . ' prosjekter vi har hatt med ' . $customer->name . '">';
			}
			$customer_details .= $customer->name;
			if( $customer->count > 1 ) {
				$customer_details .= '</a>';
			}
		}
	}
	else {
		if( $customers->count > 1 ) {
			$customer_details .= '<a href="' . get_term_link( $customers ) . '" title="Se alle ' . $customers->count . ' prosjekter vi har hatt med ' . $customers->name . '">';
		}
		$customer_details .= $customers->name;
		if( $customers->count > 1 ) {
			$customer_details .= '</a>';
		}
	}
}

// Create array to loop through and output details list, key is used as label
$project_details_list_items_setup = array(
	'Lenke:' => $web_address,
	'Se den på AppStore:' => $appstore,
	'Se den på Google Play:' => $google_play,
	'Kunder:' => $customers_details,
	'Kunde:' => $customer_details,
	'Stikkord:' => $project_tags
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
	<div class="body-content">
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>

			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php twentyeleven_posted_on(); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->
		<?php if( count( $project_details_list_items ) > 0 ) : ?>
		<div id="project-details">
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
	</div><!-- .body-content -->
</article><!-- #post-<?php the_ID(); ?> -->
<aside class="body-content">
	<?php echo do_shortcode('[vp-orbit-related-projects-slider]'); ?>
</aside>
