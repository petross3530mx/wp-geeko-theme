<?php $logo = get_field('jobs_logo'); ?>
<?php $current_post_author_id = get_the_author_meta( 'ID' );  ?>
<?php $company_name = get_the_author_meta('display_name', $current_post_author_id); ?>

<?php if(!empty($logo)){ ?>

<div class="jt job-imgx">
    <img src="<?php echo $logo; ?>" alt="">
</div>

<? }
else{ ?>
<div class="jt job-imgx name-char">
    
    <?php if (strpos($company_name, ' ') !== false) {
        $parts = explode( ' ', $company_name);
        foreach ($parts as $part){
            echo substr($part, 0, 1);  
        }
    }
    else{
        echo substr($company_name, 0, 1);  
    } ?>
</div>
<? } ?>


<div class="jx">
    <h1><?php the_title();?></h1>
    
    <p><?php echo $company_name; ?></p>
</div>