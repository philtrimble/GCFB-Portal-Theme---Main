<?php get_header(); ?>

<?php 
// Get position and location of sidebar
$st_rc_sidebar_location = of_get_option('st_rc_sidebar_location');

if (($st_rc_sidebar_location['category'] == '1') && (is_active_sidebar( 'st_sidebar_rc' ))) {
	$st_rc_sidebar_position = of_get_option('st_rc_sidebar');
} else {
	$st_rc_sidebar_position = 'off';
}

// Get our extra meta
$st_term_data =	$wp_query->queried_object;
$term_slug = get_query_var( 'term' );
$current_term = get_term_by( 'slug', $term_slug, 'st_rc_category' );
$term_id = $current_term->term_id;
?>

<?php get_template_part( 'page-header', 'rc' ); ?>

<!-- #primary -->
<div id="primary" class="sidebar-<?php echo $st_rc_sidebar_position; ?> clearfix">
<div class="container">
<!-- #content -->
<section id="content" role="main">

<?php if( !is_paged() ) { ?>
<?php
// Sub category
$st_subcat_args = array(
  'orderby' => 'name',
  'order' => 'ASC',
  'child_of' => $term_id,
  'parent' => $term_id
);
$st_sub_categories = get_terms('st_rc_category', $st_subcat_args);?> 

<?php if ($st_sub_categories) { // If the category has sub categories ?>

<!-- .rc-category-list -->
<div class="rc-category-list row stacked">

<?php
// Sub category
$st_subcat_args = array(
  'orderby' => 'name',
  'order' => 'ASC',
  'child_of' => $term_id,
  'parent' => $term_id
);
$st_sub_categories = get_terms('st_rc_category', $st_subcat_args); 

$st_sub_categories_count = count($st_sub_categories);

foreach($st_sub_categories as $st_sub_category) { 

if ($st_sub_categories_count == 1) {
echo '<div class="column col-full">';
} else {
echo '<div class="column col-half">';	
}

echo '<h3><span class="count">'. $st_sub_category->count . ' Articles</span><a href="' . get_term_link($st_sub_category->slug, 'st_rc_category') . '" title="' . sprintf( __( 'View all posts in %s', 'framework' ), $st_sub_category->name ) . '" ' . '>' . $st_sub_category->name.' <span>&rarr;</span></a></h3>';  

// Get posts per category
$args = array( 
	'numberposts' => of_get_option('st_rc_category_articles'), 
	'post_type'  => 'st_rc',
	'orderby' => 'date',
	'tax_query' => array(
		array(
			'taxonomy' => 'st_rc_category',
			'field' => 'id',
			'include_children' => false,
			'terms' => $st_sub_category->term_id
		)
	)
);
$st_cat_posts = get_posts( $args );
echo '<ul class="rc-article-list">';
foreach( $st_cat_posts as $post ) : ?>
	<li><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
<?php endforeach; 
echo '</ul></li>';
// End Get posts per Category

echo '</div>';        

} ?>

</div>
<!-- /.rc-category-list -->

<?php } ?>
<?php } ?>


<?php	
$args = array(
				'post_type' => 'st_rc',
				'tax_query' => array(
						array(
							'taxonomy' => 'st_rc_category',
							'field' => 'slug',
							'include_children' => false,
							'terms' => $term_slug
							)
						),
				'paged' => $paged
);
$wp_query = new WP_Query($args);
if($wp_query->have_posts()) :  ?>

<h3 id="category-title"><?php echo $st_term_data->name ?></h3>

<?php while($wp_query->have_posts()) : $wp_query->the_post(); ?>
                
<?php get_template_part( 'content-rc', get_post_format() ); 	?>         
                
<?php endwhile;  ?>

<?php st_content_nav( 'nav-below' ); ?>
  
<?php else : ?>

<?php endif; ?>

<?php wp_reset_postdata(); ?>

</section>
<!-- /#content -->

<?php if ($st_rc_sidebar_position != 'off') {
  get_sidebar('rc');
  } ?>

</div>
</div>
<!-- /#primary -->

<?php get_footer(); ?>