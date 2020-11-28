<div class="tabs">
	<?php global $user_role;  ?>

	<?php function echo_id_plus_class($id){
		global $user_role;
		echo 'id="'.$id.'"';
	} 

	?>
	<?php

$job_details =  [
	'job_description' => get_field('job_details'),
	'must_have' => get_field('must_have'),
	'bonus_points' => get_field('bonus_points'),
	'notes' => get_field('notes')
];

function echo_detail($detail){
	print_r($detail);
}

function edit_detail($detail){
	echo '<textarea>'.$detail.'</textarea>';
}


?>
	<?php if($user_role == 'job_owner') {?>
		<div class="edit-popup">
			<div >
				<div id="pop-inner">
			<?php $options = array(
					'groups' => 'job-options',
                    'fields' => array('type', 'location', 'salary_r1', 'salary_r2' , 'fee', 'flat_range', 'job_description', 'must_have', 'bonus_points', 'agreement', 'jobs_logo'),
                    ); ?>
			<div><button class="btn btn-rounded mr10 btnbtx acf-description-form-submit">Save</button></div>
			<?php acf_form($options); ?>
			<div><button class="btn btn-rounded mr10 btnbtx acf-description-form-submit">Save</button></div>
			<span class="popclose">X</span>
			</div>
			</div>
		</div>
		<?php if(isset($_GET['edit'])){ ?>
			<div id="job_description_tab" class="job-tab active-tab">

				<?php $options = array(
                    'fields' => array('job_description'),
                    ); ?>
                <?php acf_form($options); ?>
			</div>
			<div id="job_musthave_tab"  class="job-tab">
				<?php $options = array(
                    'field_groups' => array('job_detail'),
                    'fields' => array('must_have'),
                    ); ?>
                <?php acf_form($options); ?>
			</div>
			<div id="job_bonus_tab"   class="job-tab">
				<?php $options = array(
                    'field_groups' => array('job_details'),
                    'fields' => array('bonus_points'),
                    ); ?>
                <?php acf_form($options); ?>
			</div>
			<div id="job_notes_tab"   class="job-tab">
				 <?php $options = array(
                    'field_groups' => array('job_details'),
                    'fields' => array('notes'),
                    ); ?>
                <?php acf_form($options); ?>
			</div>
		<?php }
		else{ ?>
			<div id="job_description_tab"  class="job-tab active-tab">
				 <?php the_field('job_description');  ?>
			</div>
			<div id="job_musthave_tab"  class="job-tab">
				 <?php the_field('must_have');  ?>
			</div>
			<div id="job_bonus_tab"   class="job-tab">
				 <?php the_field('bonus_points');  ?>
			</div>
			<div id="job_notes_tab"   class="job-tab">
				 <?php $options = array(
                    'field_groups' => array('job_details'),
                    'fields' => array('notes'),
                    ); ?>
                <?php acf_form($options); ?>
			</div>
		<?php } ?>
	<?php }
	else{?>
		<div id="job_description_tab"  class="job-tab active-tab">
			 <?php the_field('job_description');  ?>
		</div>
		<div id="job_musthave_tab"   class="job-tab">
			 <?php the_field('must_have');  ?>
		</div>
		<div id="job_bonus_tab"   class="job-tab">
			 <?php the_field('bonus_points');  ?>
		</div>
	<?php } ?>
</div>

