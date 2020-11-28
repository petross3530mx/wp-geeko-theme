
<ul class="tab-conreols">
    <?php global $user_role;  ?>
    <?php if($user_role == 'job_owner') :?>
        <li ref="#job_description_tab" class="li-active" >Job description</li>
        <li ref="#job_musthave_tab">Must have</li>
        <li ref="#job_bonus_tab">Bonus points</li>
        <li ref="#job_notes_tab">Notes</li>
    <?php endif; ?>
    <?php if($user_role == 'job_viewer' || $user_role == 'job_splitter') :?>
        <li ref="#job_description_tab" class="li-active"i>Job description</li>
        <li ref="#job_musthave_tab">Must have</li>
        <li ref="#job_bonus_tab"i>Bonus points</li>
    <?php endif; ?>
    <?php $agreement = get_field('agreement'); 
    if(isset($agreement) && $user_role == 'job_owner'){ ?>
    <a class="agreement-link" href="<?php echo $agreement; ?>" download >Agreement</a>
    <?php } ?>
</ul>