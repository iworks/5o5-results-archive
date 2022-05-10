<?php get_header(); ?>
<div class="content-wrapper has-sidebar">
<?php
get_template_part( 'template-parts/5o5-archive/header' );
get_template_part( 'template-parts/5o5', 'archive' );
get_template_part( 'template-parts/5o5-archive/manufacturers' );
?>
</div>
<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>
<?php
get_footer();
