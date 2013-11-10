<fieldset id="filters">	
  <legend>
    <?php echo lang('global:filters'); ?>
  </legend>	 	
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
    <label for="f_status">Status:
    </label>	
    <?php echo form_dropdown('f_status', array(0 => lang('global:select-all'), 'normal' => 'On Going', 'completed' => 'Completed', 'drop' => 'Drop-out' ), array(2),'class="chzn"'); ?>			
    </li>				
    <!--
    <li>				
    <?php echo lang('clients.age', 'f_age'); ?>				
    <?php echo form_input('f_age'); ?>			
    </li>		-->						
    <li>				
    <label for="f_method_id">FP Method:
    </label>			
    <?php echo form_dropdown('f_method',  array(0 =>'-- All --') + $methods, 0, 'id="f_method" class="chzn"'); ?>			
    </li>					
    <li>				
    <label for="f_client_type">Client Types:
    </label>			
    <?php echo form_dropdown('f_client_type', array(0 =>'-- All --') + $client_types, '0', 'id="f_client_type" class="chzn"'); ?>			
    </li>		 		
    <li>
    <label for="f_keywords">Keywords:
    </label>			
    <?php echo form_input('f_keywords'); ?>
    </li>
  
    
    <li>
    <?php echo anchor(current_url(), lang('buttons.cancel'), 'class="cancel"'); ?>
    </li>	
  </ul>	
  <?php echo form_close(); ?>
</fieldset>