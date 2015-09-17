<?php
/**
* Archice Tempalate
* @package Wordpress
* @subpackage one-theme
* @since 1.4
* @author Matthew Hansen
*/

get_header();
?>

<section>
	<div class="container">
		<div class="page-header">
			<?php
		$post = $posts[0];

		if (is_category()): ?>
			<h1>Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h1>
		<?php
		elseif( is_tag() ): ?>
			<h1>Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h1>
		<?php
		elseif (is_day()): ?>
			<h1>Archive for <?php the_time('F jS, Y'); ?></h1>
		<?php
		elseif (is_month()): ?>
			<h1>Archive for <?php the_time('F, Y'); ?></h1>
		<?php
		elseif (is_year()): ?>
			<h1>Archive for <?php the_time('Y'); ?></h1>
		<?php
		elseif (is_author()): ?>
			<h1>Author Archive</h1>
		<?php
		elseif (isset($_GET['paged']) && !empty($_GET['paged'])): ?>
			<h1>Blog Archives</h1>
		<?php
		endif; ?>
		</div>
	</div>
</section>

<section id="archive">
	<div class="container">

	</div>
</section>

<section id="archive" class="container-fluid">
	<div class="row">
		<div class="container">


	<?php
	if (have_posts()) : ?>

		<?php
		while (have_posts()) : the_post(); ?>
		<div <?php post_class('row') ?> id="post-<?php the_ID(); ?>" style="margin-bottom:25px;">
			<?php $feat = wp_get_attachment_url(get_post_thumbnail_id()); ?>
			<div class="col-md-12" style="margin-bottom:15px;">
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<small><?php the_time( 'F jS, Y' ) ?> by <?php the_author() ?> </small>
			</div>
			<?php if (isset($feat) && !empty($feat)): ?>
				<div class="col-md-4">
					<img class="img-responsive center-block" src="<?= ($feat ? $feat : $no_image) ?>" alt="<?= get_alt(get_post_thumbnail_id()) ?>" />
				</div>
				<div class="col-md-8">
					<?php the_excerpt( 'Read the rest of this entry &raquo;' ); ?>
					<a class="btn btn-default" href="<?= the_permalink() ?>" style="margin-top:15px;">Read More</a>
					<small><p class="postmetadata" style="margin-top:35px;"><?php the_tags( 'Tags: ', ', ', '<br />' ); ?> Posted in <?php the_category( ', ' ) ?> | <?php edit_post_link( 'Edit', '', ' | ' ); ?></p></small>
				</div>
			<?php else: ?>
				<div class="col-md-12">
					<?php the_excerpt( 'Read the rest of this entry &raquo;' ); ?>
					<a class="btn btn-default" href="<?= the_permalink() ?>" style="margin-top:15px;">Read More</a>
					<small><p class="postmetadata" style="margin-top:35px;"><?php the_tags( 'Tags: ', ', ', '<br />' ); ?> Posted in <?php the_category( ', ' ) ?> | <?php edit_post_link( 'Edit', '', ' | ' ); ?></p></small>
				</div>
			<?php endif; ?>

		</div>

		<?php
		endwhile; ?>
		<div class="row">
			<div class="col-md-12">
			<div class="navigation">
					<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
					<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
				</div>
			</div>
		</div>
	<?php
	else :
		echo '<div class="row" style="margin-top: 75px;"><div class="col-md-12 text-center">';
		if ( is_category() ) { // If this is a category archive
			printf("<h2>Sorry, but there aren't any posts in the %s category yet.</h2>", single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo("<h2>Sorry, but there aren't any posts with this date.</h2>");
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h2>Sorry, but there aren't any posts by %s yet.</h2>", $userdata->display_name);
		} else {
			echo("<h2 class='center'>No posts found.</h2>");
		}
		echo '</div></div>';
		echo '<div class="row" style="margin-top: 35px; margin-bottom:250px;"><div class="col-md-8 col-md-offset-2">';
		get_search_form();
		echo '</div></div>';

	endif; ?>

	</div>
	</div>
</section>

<?php
get_footer(); ?>
