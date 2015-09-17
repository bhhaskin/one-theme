<?php
/**
 * Common wordpress Template for single blog
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.4
 * @author Matthew Hansen & Bryan Haskin
 */
get_header(); ?>

<section id="single">
	<div class="container">
		<?php
		if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php
				$feat = wp_get_attachment_url(get_post_thumbnail_id());
			?>

			<article <?php post_class('row') ?> id="post-<?php the_ID(); ?>">
				<div class="col-md-10 col-md-offset-1">
					<header>
						<h1><strong><?php the_title(); ?></strong></h1>
						<small><?php the_time( 'F jS, Y' ) ?> by <?php the_author_posts_link() ?> </small>
					</header>
					<?php if (isset($feat) && !empty($feat)): ?>
						<div class="row">
							<div class="col-md-4">
								<img class="img-responsive center-block thumbnail" src="<?= $feat ?>" alt="<?= get_alt(get_post_thumbnail_id()) ?>" />
								<div class="postmetadata">
									<?php the_tags( '<ul class="list-inline"><li>Tags:</li><li class="label label-primary">', '</li><li class="label label-primary">', '</li></ul>' ); ?>
									<small> Posted in <?php the_category( ', ' ) ?>  <?php edit_post_link( 'Edit', '| ' ); ?></small></p>
								</div>
							</div>
							<div class="col-md-8">
								<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
							</div>
						</div>
					<?php else: ?>
						<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
						<div class="postmetadata">
							<?php the_tags( '<ul class="list-inline"><li>Tags:</li><li class="label label-primary">', '</li><li class="label label-primary">', '</li></ul>' ); ?>
							<small> Posted in <?php the_category( ', ' ) ?>  <?php edit_post_link( 'Edit', '| ' ); ?></small></p>
						</div>
					<?php endif; ?>
					<footer>
						<div class="row">
							<div class="col-md-10 col-md-offset-1">
								<?php comments_template(); ?>
							</div>
						</div>
					</footer>
				</div>
			</article>
		<?php
		endwhile; else: ?>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
					<p>Sorry, no posts matched your criteria.</p>
			</div>
		</div>
		<?php
		endif; ?>
	</div>
</section>


<?php
get_footer(); ?>
