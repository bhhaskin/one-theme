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

<section class="container">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<article <?php post_class('row') ?> id="post-<?php the_ID(); ?>">
				<?php $feat = wp_get_attachment_url(get_post_thumbnail_id()); ?>
				<div class="col-md-10 col-md-offset-1">
					<header>
						<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
						<small><?php the_time( 'F jS, Y' ) ?> by <?php the_author_posts_link() ?> </small>
					</header>
					<?php if (isset($feat) && !empty($feat)): ?>
						<div class="row">
							<div class="col-md-4">
								<a href="<?= the_permalink() ?>"><img class="img-responsive center-block thumbnail" src="<?= $feat ?>" alt="<?= get_alt(get_post_thumbnail_id()) ?>" /></a>
							</div>
							<div class="col-md-8">
								<?php the_excerpt( 'Read the rest of this entry &raquo;' ); ?>
							</div>
						</div>
					<?php else: ?>
							<?php the_excerpt( 'Read the rest of this entry &raquo;' ); ?>
					<?php endif; ?>

					<footer>
						<div class="postmetadata">
							<?php the_tags( '<ul class="list-inline"><li>Tags:</li><li class="label label-primary">', '</li><li class="label label-primary">', '</li></ul>' ); ?>
							<small> Posted in <?php the_category( ', ' ) ?>  <?php edit_post_link( 'Edit', '| ' ); ?></small></p>
						</div>
						<a class="btn btn-default" href="<?= the_permalink() ?>">Read More</a>
					</footer>
				</div>
			</article>

		<?php endwhile; ?>
		<nav>
			<ul class="pager">
				<li class="previous"><?php next_posts_link('<span aria-hidden="true">&larr;</span> Older Entries') ?></li>
				<li class="next"><?php previous_posts_link('Newer Entries <span aria-hidden="true">&rarr;</span>') ?></li>
			</ul>
		</nav>
		<?php else : ?>
			<h2 class="center">Not Found</h2>
			<p class="center">Sorry, but you are looking for something that isn't here.</p>
			<?php get_search_form(); ?>
		<?php endif; ?>
</section>

<?php get_footer(); ?>
