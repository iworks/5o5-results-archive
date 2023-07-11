<?php
/**
 * Template part for displaying iworks_fleet_person
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package 5o5-results-archive
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
<?php
$prefix = '<h1 class="entry-title">';
$hash   = get_post_meta( get_the_ID(), 'iworks_fleet_contact_gravatar', true );
if ( empty( $hash ) ) {
	$hash = 'empty';
}
$size   = 128;
$prefix = sprintf(
	'<h1 class="entry-title entry-title-gravatar"><img src="%2$s" class="avatar avatar-%1$d photo" width="%1$d" height="%1$d" loading="lazy" decoding="async" />',
	$size,
	add_query_arg(
		array(
			's' => $size,
			'd' => 'mm',
			'r' => 'g',
		),
		'https://gravatar.com/avatar/' . $hash
	)
);
the_title( $prefix, '</h1>' );
?>
	</header><!-- .entry-header -->
	<div class="entry-content"><?php the_content(); ?></div><!-- .entry-content -->
	<footer class="entry-footer">
		<?php int505_archive_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
