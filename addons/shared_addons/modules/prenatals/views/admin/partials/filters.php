<fieldset id="filters">

	<legend><?php echo lang('global:filters'); ?></legend>
	
	<?php echo form_open(''); ?>
	<?php echo form_hidden('f_module', $module_details['slug']); ?>
	<ul>
      	<li>				
    <label for="f_method_id">Barangay:
    </label>			
    <?php echo form_dropdown('f_barangays',  array(0 =>'-- All --') + $barangays, 0, 'id="list_barangays" class="chzn"'); ?>			
    </li>
		<li>
            <label for="f_client_type">By Year:</label>	
            <?php echo form_dropdown('by_year', array(0 =>'-- All --') + $years, '0', 'id="by_year" class="chznsm"') ?>
        </li>
        <li>				
            <label for="f_client_type">By Prenatal Visits:</label>			
            <?php echo form_dropdown('prenatals_filters', array(0 =>'-- All --') + $prenatals_filters, '0', 'id="prenatals_filters" class="chzn"'); ?>			
        </li>
        <li>				
            <label for="f_client_type">By Risk Code:</label>			
        	<?php echo form_dropdown('risk_code', $risk_code, isset($prenatal_client->risk_code) ? $prenatal_client->risk_code : 0, 'class="chzn"') ?>			
        </li>
        <li><label for="f_keywords">Keywords:</label><?php echo form_input('f_keywords'); ?></li>
		<li><?php echo anchor(current_url(), lang('buttons.cancel'), 'class="cancel"'); ?></li>
	</ul>
	<?php echo form_close(); ?>
</fieldset>