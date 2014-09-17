<?php get_header(); ?>

<?php 
// Get position and location of sidebar
$st_rc_sidebar_location = of_get_option('st_rc_sidebar_location');

if (($st_rc_sidebar_location['index'] == '1') && (is_active_sidebar( 'st_sidebar_rc' ))) {
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

<?php
//list terms in a given taxonomy
$args = array(
    'hide_empty'    => 0,
	'child_of' 		=> 0,
	'pad_counts' 	=> 1,
	'hierarchical'	=> 1
); 
$tax_terms = get_terms('st_rc_category', $args);
$tax_terms = wp_list_filter($tax_terms,array('parent'=>0));
?>

<div class="rc-category-list row stacked">
<?php
foreach ($tax_terms as $tax_term) {

echo '<div class="column col-half">';

echo '<h3><span class="count">'. $tax_term->count .' Articles</span><a href="' . esc_attr(get_term_link($tax_term, 'st_rc_category')) . '" title="' . sprintf( __( 'View all posts in %s', 'framework' ), $tax_term->name ) . '" ' . '>' . $tax_term->name.' <span>&rarr;</span></a></h3>';

// Get posts per category
$args = array( 
	'numberposts' => of_get_option('st_rc_category_articles'), 
	'post_type'  => 'st_rc',
	'orderby' => 'date',
	'tax_query' => array(
		array(
			'taxonomy' => 'st_rc_category',
			'field' => 'id',
			'include_children' => true,
			'terms' => $tax_term->term_id
		)
	)
);
$st_cat_posts = get_posts( $args );
echo '<ul class="rc-article-list">';

foreach( $st_cat_posts as $post ) : ?>
	<li><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
    
<?php endforeach; 
echo '</ul>';
// End Get posts per Category

echo '</div>';
}
// Close list terms in a given taxonomy
?>
</div>
   
</section>
<!-- /#content -->

<?php if ($st_rc_sidebar_position != 'off') {
  get_sidebar('rc');
  } ?>
  
</div>
</div>
<!-- /#primary -->
<?php get_footer(); ?>
