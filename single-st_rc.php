<?php get_header(); ?>

<?php 
// Get position and location of sidebar
$st_rc_sidebar_location = of_get_option('st_rc_sidebar_location');

if (($st_rc_sidebar_location['single'] == '1') && (is_active_sidebar( 'st_sidebar_rc' ))) {
	$st_rc_sidebar_position = of_get_option('st_rc_sidebar');
} else {
	$st_rc_sidebar_position = 'off';
}
?>

<?php get_template_part( 'page-header', 'rc' ); 	?>

<!-- #primary -->
<div id="primary" class="sidebar-<?php echo $st_rc_sidebar_position; ?> clearfix"> 
<div class="container">
  <!-- #content -->
  <section id="content" role="main">
    <?php while ( have_posts() ) : the_post(); ?>
	<?php st_set_post_views(get_the_ID()); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>> 
      

<!-- .entry-header -->
<div class="entry-header">
      <h1 class="entry-title">
        <?php the_title(); ?>
      </h1>
      
<?php get_template_part( 'content-rc', 'meta' ); ?>

</div>
<!-- /.entry-header --> 

<!-- .entry-content -->
<div class="entry-content clearfix">
	<?php the_content(); ?>
    <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'framework' ), 'after' => '</div>' ) ); ?>
</div>
<!-- ./entry-content -->

</article>
    
<?php
// If comments are open or we have at least one comment, load up the comment template
if ( comments_open() || '0' != get_comments_number() )
comments_template( '', true );
?>

<?php endwhile; // end of the loop. ?>

</section>
<!-- #content -->

<?php if ($st_rc_sidebar_position != 'off') {
  get_sidebar('rc');
  } ?>

</div>
</div>
<!-- /#primary -->

<?php get_footer(); ?>