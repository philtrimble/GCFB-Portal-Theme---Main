<?php
/*
Template Name: calendar-template-phil
*/

if ( !defined('ABSPATH') ) { die('-1'); } ?>

<?php
/*
Template Name: Calendar
*/

?>
<?php get_header(); ?>

<!-- #page-header -->
<div id="page-header" class="clearfix">
<div class="container">
<h1>Calendar<?php //the_title(); ?></h1>
<?php if (get_post_meta( get_the_ID(), '_st_page_tagline', true )) { ?>
<p><?php echo get_post_meta( get_the_ID(), '_st_page_tagline', true ); ?></p>
<?php } ?>
</div>
</div>
<!-- /#page-header -->

<?php if (!get_post_meta( get_the_ID(), '_st_page_breadcrumbs', true )) { ?>
<!-- #breadcrumbs -->
<div id="page-subnav" class="clearfix">
<div class="container">
<?php st_breadcrumb(); ?>
</div>
</div>
<!-- /#breadcrumbs -->
<?php } ?>

<div id="tribe-events-pg-template">
	<?php tribe_events_before_html(); ?>
	<?php tribe_get_view(); ?>
	<?php tribe_events_after_html(); ?>
</div> <!-- #tribe-events-pg-template -->
<?php get_footer(); ?>