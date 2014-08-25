<?php
/**
  * Child theme
  * @package Wordpress
  * @subpackage one-theme
  * @since 1.0
  * @author Matthew Hansen
  */

// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');

if ( post_password_required() ) { ?>
	<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
<?php
	return;
}
?>

<!-- You can start editing here. -->

<?php
if ( have_comments() ) : ?>

	<h4 id="comments"><?php comments_number('No Responses', 'One Response', '<span class="label label-warning">%</span> Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h4>


	<ol class="commentlist">
		<?php wp_list_comments(array('reply_text' => '<i class="fa fa-reply"></i> Reply')); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

<?php
else:

	if ( comments_open() ) :

		//Open but no comments

	else: ?>

		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php
	endif;

endif;

if ( comments_open() ) : ?>

	<div id="respond" class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h3>
		</div>
		<div class="panel-body">
			<div class="cancel-comment-reply"><small><?php cancel_comment_reply_link(); ?></small></div>

			<?php
			if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>

				<p>You must be <a href="<?php echo wp_login_url( get_permalink() ); ?>">logged in</a> to post a comment.</p>

			<?php
			else : ?>

				<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" role="form">



						<?php
						if ( is_user_logged_in() ) : ?>

							<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>

						<?php
						else : ?>
							<div class="form-group">
							<label for="author">Your Name <?php if ($req) echo "(required)"; ?></label>
							<input type="text" class="form-control" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
							</div>

							<div class="form-group">
							<label for="email">Your E-Mail (will not be published) <?php if ($req) echo "(required)"; ?></label>
							<input type="text" class="form-control" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
							</div>

							<!--
							<div class="form-group">
							<label for="url">Website <?php if ($req) echo "(required)"; ?></label>
							<input type="text" class="form-control" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
							</div>
							-->

						<?php
						endif; ?>

					<div class="form-group">

						<textarea name="comment" class="form-control" id="comment" cols="58" rows="10" tabindex="4" placeholder="Your Comment"></textarea>
					</div>

							<input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" class="btn btn-default" />
							<?php comment_id_fields(); ?>


						<?php do_action('comment_form', $post->ID); ?>

				</form>

			<?php
			endif; // If registration required and not logged in ?>
		</div>
	</div> <!-- respond -->

<?php
endif; // if you delete this the sky will fall on your head ?>
