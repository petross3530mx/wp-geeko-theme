<div class="jt job-imgx">
    <?php the_company_logo(); ?>
</div>

<div class="jx">
    <h1><?php the_title();?></h1>
    <?php $current_post_author_id = get_the_author_meta( 'ID' );  ?>
    <p><?php echo get_the_author_meta('display_name', $current_post_author_id);?></p>
</div>