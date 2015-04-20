<?php
/**
 * Common wordpress Template for single blog
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.4
 * @author Matthew Hansen & Bryan Haskin
 */
get_header(); ?>


<section class="container-fluid">
	<div class="row">
	  <div class="container">
	  	<div class="row">
	  	  <div class="col-md-12">
                <?php
            	if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <?php
                        $feat = wp_get_attachment_url(get_post_thumbnail_id());
                    ?>
                    <div class="row row-pad-sm">
                        <div class="col-md-11 col-md-offset-1" style="margin-bottom: 15px;">
                            <h2><strong><?php the_title(); ?></strong></h2>
                            <small><?php the_time( 'F jS, Y' ) ?> by <?php the_author() ?> </small>
                        </div>
						<?php if (isset($feat) && !empty($feat)): ?>
                        <div class="col-md-3 col-md-offset-1">
								<img class="img-responsive center-block thumbnail" src="<?= $feat ?>" />
                            <small><p class="postmetadata" style="margin-top: 15px;">Posted in <?php the_category( ', ' ) ?><br><?php the_tags( 'Tags: ', ', ', '<br />' ); ?> </p></small>

                            <!-- <div class="well hidden-sm hidden-xs" style="margin-top:20px;">
                                <p class="postmetadata alt">
                                    <small>
                                        This entry was posted
                                        <?php /* This is commented, because it requires a little adjusting sometimes.
                                            You'll need to download this plugin, and follow the instructions:
                                            http://binarybonsai.com/wordpress/time-since/ */
                                            /* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
                                        on <?php the_time('l, F jS, Y') ?> at <?php the_time() ?>
                                        and is filed under <?php the_category(', ') ?>.
                                        You can follow any responses to this entry through the <?php post_comments_feed_link('RSS 2.0'); ?> feed.

                                        <?php if ( comments_open() && pings_open() ) {
                                            // Both Comments and Pings are open ?>
                                            You can <a href="#respond">leave a response</a>, or <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your own site.

                                        <?php } elseif ( !comments_open() && pings_open() ) {
                                            // Only Pings are Open ?>
                                            Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.

                                        <?php } elseif ( comments_open() && !pings_open() ) {
                                            // Comments are open, Pings are not ?>
                                            You can skip to the end and leave a response. Pinging is currently not allowed.

                                        <?php } elseif ( !comments_open() && !pings_open() ) {
                                            // Neither Comments, nor Pings are open ?>
                                            Both comments and pings are currently closed.

                                        <?php } edit_post_link('Edit this entry','','.'); ?>

                                    </small>
                                </p>
                            </div> -->
                        </div>

                        <div <?php post_class('col-md-7') ?> id="post-<?php the_ID(); ?>">

                            <?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>


                		</div> <!-- post -->
						<?php else: ?>
							<div <?php post_class('col-md-11 col-md-offset-1') ?> id="post-<?php the_ID(); ?>">

								<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>


							</div> <!-- post -->
							<div class="col-md-11 col-md-offset-1">
								<small><p class="postmetadata" style="margin-top: 15px;">Posted in <?php the_category( ', ' ) ?><br><?php the_tags( 'Tags: ', ', ', '<br />' ); ?> </p></small>
							</div>
						<?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
            		         <?php comments_template(); ?>
                        </div>
                    </div>
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
	  	</div>
	  </div>
	</div>
</section>

<?php
get_footer(); ?>
