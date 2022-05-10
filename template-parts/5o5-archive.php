<main id="primary" class="site-main">
<?php
$type = '';
if ( is_tax() ) {
	$queried_object = get_queried_object();
	$type           = $queried_object->taxonomy;
} elseif ( is_post_type_archive() ) {
	$queried_object = get_queried_object();
	$type           = $queried_object->name;
}

if ( have_posts() ) {

	if ( is_post_type_archive( 'iworks_fleet_result' ) ) {
		echo do_shortcode(
			sprintf(
				'[fleet_regattas_list_countries title="0" flags="1" year="%s"]',
				get_query_var( 'iworks_fleet_result_year' )
			)
		);
	}

	?>
<article>
	<div class="post-inner thin ">
		<div class="entry-content">
	<?php
	get_template_part( 'template-parts/5o5-archive/table-header', $type );
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/5o5-archive/table-row', $type );
	}
	get_template_part( 'template-parts/5o5-archive/table-footer', $type );
	?>
		</div>
	</div>
</article>
	<?php
} elseif ( is_search() ) {
	?>

		<div class="no-search-results-form section-inner thin">

	<?php
	get_search_form(
		array(
			'label' => __( 'search again', 'twentytwenty' ),
		)
	);
	?>

		</div><!-- .no-search-results -->

	<?php
}
?>

	<?php get_template_part( 'template-parts/pagination' ); ?>
</main>

