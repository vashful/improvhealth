<section class="title">
	<h4><?php echo lang('clients.title'); ?></h4>
</section>

<section class="item">
	
	<?php template_partial('filters'); ?>

	<?php echo form_open('admin/clients/action'); ?>
	  <?php if (!empty($clients)): ?>
		<div id="filter-stage">
			<?php template_partial('diagnose-list'); ?>
		</div>

		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
		</div>
		<?php endif; ?>

	<?php echo form_close(); ?>

</section>
