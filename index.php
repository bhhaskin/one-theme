<?php
/**
 * Common wordpress Template for blog loop / also final fall back in template
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.4
 * @author Matthew Hansen & Bryan Haskin
 */

get_header();
?>

<section class="container-fluid">
	<div class="row">
	  <div class="container">
	  	<div class="row">
	  	  <div class="col-md-12">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div <?php post_class('row') ?> id="post-<?php the_ID(); ?>" style="margin-bottom:25px;">
						<?php $feat = wp_get_attachment_url(get_post_thumbnail_id()); ?>
						<div class="col-md-12" style="margin-bottom:15px;">
							<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
							<small><?php the_time( 'F jS, Y' ) ?> by <?php the_author() ?> </small>
						</div>
						<?php if (isset($feat) && !empty($feat)): ?>
							<div class="col-md-4">
								<a href="<?= the_permalink() ?>"><img class="img-responsive center-block thumbnail" src="<?= $feat ?>" /></a>
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

				<?php endwhile; ?>
					<div class="row" style="margin-top: 35px; margin-bottom: 35px;">
						<div class="col-md-12 navigation">
							<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
							<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
						</div>
					</div>
				<?php else : ?>
					<div class="row">
						<div class="col-md-12">
							<h2 class="center">Not Found</h2>
							<p class="center">Sorry, but you are looking for something that isn't here.</p>
							<?php get_search_form(); ?>
						</div>
					</div>

				<?php endif; ?>
	  	  </div>
	  	</div>
	  </div>
	</div>
</section>
<?php get_footer(); ?>
