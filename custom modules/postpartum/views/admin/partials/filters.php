<fieldset id="filters">

	<legend><?php echo lang('global:filters'); ?></legend>
	
	<?php echo form_open(''); ?>
	<?php echo form_hidden('f_module', $module_details['slug']); ?>
	<ul>
		<li>
				<?php echo lang('clients.gender', 'f_gender'); ?>
				<?php echo form_dropdown('f_gender', array(0 => lang('global:select-all'), 'male' => 'Male', 'female' => 'Female' ), array(2)); ?>
			</li>
		<li>
				<?php echo lang('clients.age', 'f_age'); ?>
				<?php echo form_input('f_age'); ?>
			</li>
		<li><?php echo form_input('f_keywords'); ?></li>
		<li><?php echo anchor(current_url(), lang('buttons.cancel'), 'class="cancel"'); ?></li>
	</ul>
	<?php echo form_close(); ?>
</fieldset>