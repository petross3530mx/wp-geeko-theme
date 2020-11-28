<?php

$hide_title = get_post_meta(get_proper_ID(), 'gecko-page-hide-title', true);
$post_class = '';
$post_date = get_the_date( 'l F j, Y' );
$post_tags = get_the_tag_list( '', __( ', ', 'gecko' ) );
$settings = GeckoConfigSettings::get_instance();

if (! has_post_thumbnail() ) {
	$post_class = 'post--noimage';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="entry-image">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_post_thumbnail(); ?></a>
	</div>
	<?php endif; ?>

	<?php if(!$hide_title) : ?>
	<header class="entry-header">
		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
		?>
		<?php edit_post_link( '<i class="gcis gci-pen gc-tip" arialabel="'.__( 'Edit', 'gecko' ).'"></i>', '<span class="edit-link">', '</span>' ); ?>
	</header><!-- .entry-header -->
	<?php endif; ?>

	<?php do_action('gecko_after_page_header'); ?>

	<div class="entry-date entry-date--static">
		<i class="gcir gci-calendar-alt"></i> <?php gc_post_date() ?> <?php if (is_blog()) : ?><?php _e( 'Published by', 'gecko' ); ?> <a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php echo get_the_author(); ?></a><?php endif; ?>
	</div>

	<div class="entry-content">
		<?php
			the_excerpt();
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<div class="entry-author"><i class="gcis gci-user-edit"></i><span><?php _e( 'Published by', 'gecko' ); ?></span> <a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php echo get_the_author(); ?></a></div>

		<?php if ((get_comments_number($post->ID) > 0)) : ?><div class="entry-comments" aria-label="<?php echo _e( 'Comments', 'gecko' ); ?>"><i class="gcis gci-comment-alt"></i><?php echo '<a href="' . get_comments_link( $post->ID ) . '">' . get_comments_number($post->ID) . '</a>'; ?></div><?php endif; ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
