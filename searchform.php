<?php
/**
* Searchform Include
* @package Wordpress
* @subpackage one-theme
* @since 1.0
* @author Matthew Hansen
*/
?>

<form action="<?php bloginfo('url'); ?>" method="get">
	<div class="input-group">
		<input type="text" class="form-control" name="s" id="search" value="<?php the_search_query(); ?>" placeholder="Search">
		<span class="input-group-btn">
			<button class="btn btn-info" type="submit"><span class="fa fa-search"></span></button>
		</span>
	</div><!-- /input-group -->
</form>
