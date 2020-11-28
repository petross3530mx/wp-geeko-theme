
<?php global $user_role;  ?>

<?php if($user_role == 'job_owner') :?>
    <button type="reset" onclick="location.href='/board'" class="btn btn-green btn-rounded mr10">Talent Board</button>
     
    <?php if(!isset($_GET['edit'])){?>
    <button id="but-pop" class="btn btn-blue btn-rounded mr10" onclick="showEditPopup()">Edit</button>
    <?php }
    else{ ?>
    <button class="btn btn-blue btn-rounded mr10">Save</button>
    <?php } ?>
    
    <div class="status-container">
    <?php $options = array(
        'field_groups' => array('job_options'),
        'fields' => array('status'),
        'return' =>  '%post_url%',
        'form' => true
     ); ?>
    <?php acf_form($options); ?>
    </div>
    <div class="status-container status-container-2">
        <label class="featured-label">Featured</label>
        <label for=""> <div><?php $options = array(
        'field_groups' => array('job_options'),
        'fields' => array('urgent'),
        'form' => true
     ); ?>
    <?php acf_form($options); ?></div></label>
    
    </div>
<?php endif; ?>

<?php if($user_role == 'job_viewer') :?>
    <button class="btn btn-blue btn-rounded mr10">Split request</button>
    <div class="btn btn-bordered"><?php the_field('status'); ?></div>
<?php endif; ?>

<?php if($user_role == 'job_splitter') :?>
    <button class="btn btn-blue btn-rounded mr10">Split request</button>
    <div class="btn btn-bordered"><?php the_field('status'); ?></div>
<?php endif; ?>