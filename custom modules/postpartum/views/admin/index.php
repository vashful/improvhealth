<section class="title">
	<h4><?php echo lang('prenatal.title'); ?></h4>
</section>

<section class="item">
	
	<?php template_partial('filters'); ?>

	<?php echo form_open('admin/prenatal/action'); ?>
	
		<div id="filter-stage">
			<?php template_partial('prenatal-list'); ?>
		</div>
	
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
		</div>

	<?php echo form_close(); ?>

</section>
