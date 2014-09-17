<?php 
// get the id of the posts page
$st_index_id = get_option('page_for_posts');
$st_page_sidebar_pos = get_post_meta( $st_index_id, '_st_page_sidebar', true );
?>

<?php if ( is_category() ) { ?>

<!-- #page-header -->
<div id="page-header" class="clearfix">
<div class="container">
<h1>Agency Academy</h1>
</div>
</div>
<!-- /#page-header -->

<?php } elseif ( is_tag() ) { ?>

<!-- #page-header -->
<div id="page-header" class="clearfix">
<div class="container">
<h1>Agency Academy</h1>
</div>
</div>
<!-- /#page-header -->

<?php } elseif ( is_archive() ) { ?>

<!-- #page-header -->
<div id="page-header" class="clearfix">
<div class="container">
<h1>Agency Academy</h1>
</div>
</div>
<!-- /#page-header -->

<?php } else { ?>

<!-- #page-header -->
<div id="page-header" class="clearfix">
<div class="container">
<h1>Agency Academy</h1>
</div>
</div>
<!-- /#page-header -->

<?php } ?>

<?php if (!get_post_meta( $st_index_id, '_st_page_breadcrumbs', true )) { ?>
<!-- #breadcrumbs -->
<div id="page-subnav" class="clearfix">
<div class="container">
<?php st_breadcrumb(); ?>
</div>
</div>
<!-- /#breadcrumbs -->
<?php } ?>