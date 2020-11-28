
<?php $datailinfo = []; ?>

<?php if ( get_option( 'job_manager_enable_types' ) ) { ?>
<?php $types = wpjm_get_the_job_types(); ?>
<?php if ( ! empty( $types ) ) : foreach ( $types as $type ) : ?>
    <?php $datailinfo[$type->taxonomy] = esc_html( $type->name );  ?>
<?php endforeach; endif; ?>
<?php } ?>
<?php 
global $user_role; 
 if($user_role == 'job_owner') {?>	
	<div class="micro-details">
	    <div class="detail-item">
	        <p>Location</p>
	        <small><?php echo the_field('location');?></small>
	    </div>
 
	    <div class="detail-item">
	        <p>Type</p>
	        <small><?php echo the_field('type');?></small>
	    </div>
		<div class="detail-item">
			<p>Salary range</p>
			<?php $salary1 = trim(get_field('salary_r1')); ?>
			<?php $salary2 = trim(get_field('salary_r2')); ?>
			<?php if($salary1 && $salary2){ ?>
				<small><?php echo get_field('salary_r1');?></small>   - 
				<small><?php echo get_field('salary_r2');?></small>
			<?php }
			else{?>
				<?php echo get_field('salary_r1') ?? get_field('salary_r2');?>
			<?php } ?>
		</div>
		<div class="detail-item">
			<p>Fee</p>
			<?php $fee = get_field('fee'); ?>
	        <small><?php echo $fee;?>%</small>
		</div>
		<div class="detail-item">
	        <p>Commision</p>
	        <?php if($salary1 && $salary2){ ?>
				<small>$<?php $val1 = intval(preg_replace('/[^0-9]/', '', $salary1)) * $fee / 100; echo number_format($val1, 0, '', ' '); ?></small>   - 
				<small>$<?php $val2 = intval(preg_replace('/[^0-9]/', '', $salary2)) * $fee / 100; echo number_format($val2, 0, '', ' '); ?></small> 
			<?php }
			else{?>
				<?php echo get_field('salary_r1') ?? get_field('salary_r2');?>
			<?php } ?>
		</div>
		<?php $flat_split = get_field('flat_range'); ?>
		<? if(intval($flat_split)){ ?>
			<div class="detail-item">
				<p>Flat Split</p>
				<small class="small-green">$<?php number_format(intval(the_field('flat_range')), 0, '', ' '); ?></small>
			</div>
		<?php }
		else{ ?>
			<div class="detail-item">
				<p>Split %</p>
				<?php if($val1 && $val2){ ?>
					<small class="small-green">$<?php echo intval($val1/2);  ?> - $<?php echo intval($val2/2);  ?></small>
				<? } ?>
			</div>
		<?php } ?>
	</div>
<?php }
else{ ?>
	<div class="micro-details">
	    <div class="detail-item">
	        <p>Location</p>
	        <small><?php the_job_location(false); ?></small>
	    </div>
	    <?php if(isset($datailinfo['job_listing_type'])) : ?>
	    <div class="detail-item">
	        <p>Type</p>
	        <small><?php echo $datailinfo['job_listing_type']; ?></small>
	    </div>
		<?php endif; ?>
		
		<?php $val1 = intval(preg_replace('/[^0-9]/', '', $salary1)) * $fee / 100; ?> </small>   - 
		<?php $val2 = intval(preg_replace('/[^0-9]/', '', $salary2)) * $fee / 100; ?></small> 


	    <?php $flat_split = get_field('flat_range'); ?>
		<? if(intval($flat_split)){ ?>
			<div class="detail-item">
				<p>Flat Split</p>
				<small class="small-green">$<?php number_format(intval(the_field('flat_range')), 0, '', ' '); ?></small>
			</div>
		<?php }
		else{ ?>
			<div class="detail-item">
				<p>Split %</p>
				<?php if($val1 && $val2){ ?>
					<small class="small-green">$<?php echo intval($val1/2);  ?> - $<?php echo intval($val2/2);  ?></small>
				<? } ?>
			</div>
		<?php } ?>
	</div>
<?php } ?>