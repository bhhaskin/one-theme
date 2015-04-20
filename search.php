<?php
/**
* Search Template
* @package Wordpress
* @subpackage one-theme
* @since 1.4
* @author Matthew Hansen
*/
get_header(); ?>


<section id="search" class="container-fluid">
	<div class="row">
		<div class="container">
		<?php
		if (have_posts()) : ?>
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center">
				<h1>Search Results</h2>
					<?php get_search_form(); ?>
			</div>
		</div>
				<?php
				while (have_posts()) : the_post(); ?>
				<div class="row">
					<div class="col-md-12">
						<div <?php post_class() ?>>

							<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
							<small><?php the_time('l, F jS, Y') ?></small>

							<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>
						</div>
					</div>
				</div>
		<?php
		endwhile; ?>
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="navigation">
					<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
					<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
				</div>
			</div>
		</div>

		<?php
		else : ?>
		<div class="row" style="margin-top: 35px; margin-bottom:250px;">
			<div class="col-md-8 col-md-offset-2 text-center">
				<h2>No posts found. Try a different search?</h2>
				<?php get_search_form(); ?>
			</div>
		</div>

		<?php
		endif; ?>
	</div>
	</div>
</section>

<?php
get_footer(); ?>
