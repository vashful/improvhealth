<fieldset id="filters">

	<legend><?php echo lang('global:filters'); ?></legend>
	
	<?php echo form_open(''); ?>
	<?php echo form_hidden('f_module', $module_details['slug']); ?>
	<ul>
			    <li>				
    <label for="f_method_id">Diagnosis:
    </label>			
    <?php echo form_dropdown('f_diagnosis',  array(0 =>'-- All --') + $list_diagnosis, 0, 'id="f_diagnosis" class="chzn"'); ?>			
    </li>
		<li>
				<label for="f_gender">Gender: </label>
				<?php echo form_dropdown('f_gender', array(0 => lang('global:select-all'), 'male' => 'Male', 'female' => 'Female' ), array(2),'class="chznsm"'); ?>
			</li>
		<li>
				<label for="f_age">Age: </label>
				<?php echo form_input('f_age','','class="age_box"'); ?>
			</li>
		<li><label for="f_gender">Keywords: </label> <?php echo form_input('f_keywords'); ?></li>
		<li><?php echo anchor(current_url(), lang('buttons.cancel'), 'class="cancel"'); ?></li>
	</ul>
	<?php echo form_close(); ?>
</fieldset>