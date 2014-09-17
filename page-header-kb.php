<!-- #page-header -->
<div id="page-header">
<div class="container">

<?php $st_kb_data = get_post_type_object('st_kb'); ?>
<h1><?php if (is_search()) {  _e("Search: ", "framework"); } ?><?php echo $st_kb_data->labels->singular_name; ?></h1>
<!-- #live-search -->
<div id="live-search">

      <form role="search" method="get" id="searchform" class="clearfix" action="<?php echo home_url( '/' ); ?>" autocomplete="off">
        <input type="text" onfocus="if (this.value == '<?php _e("Search the knowledge base...", "framework") ?>') {this.value = '';}" onblur="if (this.value == '')  {this.value = '<?php _e("Search the knowledge base...", "framework") ?>';}" value="<?php _e("Search the knowledge base...", "framework") ?>" name="s" id="s" />
        <input type="hidden" name="post_type" value="st_kb" />
      </form>

</div>
<!-- /#live-search -->

</div>
</div>
<!-- #page-header -->

<!-- #breadcrumbs -->
<div id="page-subnav" class="clearfix">
<div class="container">
<?php st_breadcrumb(); ?>
</div>
</div>
<!-- /#breadcrumbs -->