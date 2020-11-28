
<?php acf_form_head();get_header(); ?>


<?php 
global $post; 
global $user_role;

add_filter ('show_admin_bar', '__return_false');

while ( have_posts() ){
	the_post();


$current_post_author_id = get_the_author_meta( 'ID' ); 
$current_user_id =  get_current_user_id() ?? false;

if(!$current_user_id){ 
    $user_role = 'job_viewer';
}
else{
    echo '<style>body{margin-top: -40px;}</style>';
    if($current_post_author_id == $current_user_id){ 
        $user_role = 'job_owner';
        
    }
    else{ 
        $user_role = 'job_splitter';
    }
}


?>
<style>.admin-bar .gc-header { top: 0;} </style>
<script>console.info("<?php echo  $current_post_author_id ?>");</script>

<div id="main" class="main main--single <?php echo $main_class; if ($full_width_layout == 1) : echo " main--full"; endif; if ($builder_friendly_layout == 1) : echo " main--builder"; endif; ?>">

  <div class="content">

    <div class="job-grid">
        <div class="job-left">
            <div class="gc-widget">
                <div class="gc-widget__title job-title-container">
                    <?php get_template_part('job_manager/job','titleimage') ; ?>
                </div>
                <div class="job-main-details">
                    <?php get_template_part('job_manager/job','maindetails') ; ?>
                </div>
            </div>
            <?php if($user_role == 'job_owner'){ ?>
            <div class=" gc-widget split-partners-acf">
                <div>
                    <button class="btn-rounded btn-green btn" onclick="jQuery('.select2-search__field').trigger('click')">Invite</button>
                    <button class="btn-rounded btn-transparent btn" >Split Partners</button>
                </div>
                <?php $options = array(
                    'field_groups' => array('job_options'),
                    'fields' => array('partners'),
                    ); ?>
                <?php acf_form($options); ?>
            </div> 
                <? } ?>
        </div>
        <div class="job-right">
            <div class="top-btns">
                <?php get_template_part('job_manager/job','topnav') ; ?>
            </div>
            <div class="gc-widget job-nav-menu">
                <?php get_template_part('job_manager/job','nav') ; ?>
            </div>
            <div class="gc-widget job-nav-description">
                <?php get_template_part('job_manager/job','description') ; ?>
            </div>
        </div>
    </div>
    
  </div>
</div>

<?php } ?>

<?php $e = get_template_directory_uri(); ?>
<script src="<?php echo $e; ?>/job_manager/assets/script.js?t=<?php echo time();?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $e; ?>/job_manager/assets/style.css?t=<?php echo time();?>">
<?php

get_footer();
