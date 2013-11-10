<?php //print_r($postpartum);?>
<section class="title">
    <?php if ($postpartum->method_action == 'add'): ?>
    		<h4>Add <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> in Postpartum Care</h4>
    		<?php echo form_open(uri_string(), 'class="crud" autocomplete="off"'); ?>
       	<?php else: ?>
    		<h4>Edit <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> in Postpartum Care</h4>
    		<?php echo form_open(uri_string(), 'class="crud"'); ?>
	 <?php endif; ?>
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
			<li><a href="#client-pi-tab"><span>Delivery and Breastfeeding</span></a></li>
			<li><a href="#client-ms-tab"><span>Micronutrient Supplementation</span></a></li>
			<li><a href="#client-remarks-tab"><span>Remarks</span></a></li>
			<li><a href="#client-details-tab"><span>Client Detail Information</span></a></li>
		</ul>
    
		<!-- Content tab -->
		
		<div class="form_inputs" id="client-pi-tab">
			<fieldset>
				<ul>
				  						
    			<li class="date-meta">
    				<label>Date and Time of Delivery</label>
    				
    				<div class="input datetime_input">  
    				<?php echo form_input('delivery', $postpartum->delivery ? date('Y-m-d', $postpartum->delivery) : '', 'maxlength="10" class="datepicker" class="text width-20"'); ?> &nbsp;
    				<?php echo form_dropdown('delivery_on_hour', array(NULL => '-- Hour --') + $hours, $postpartum->delivery ? date('H', $postpartum->delivery) : NULL,'class="chznsm"') ?> 
    				<?php echo form_dropdown('delivery_on_minute', array(NULL => '- Minute -') + $minutes, $postpartum->delivery ? date('i', ltrim($postpartum->delivery, '0')): NULL,'class="chznsm"') ?>
    				<?php echo form_hidden('client_id', $client->id, 'id="client_id"') ?>
    				</div>
    			</li>
    			<li class="even">
						<label for="ms_a_date_given">Date Post-Partum Visit w/in 24 hours after Delivery</label>
						<div class="fields">
  					<div>
              <?php echo form_input('visits_day', $postpartum->visits_day ? $postpartum->visits_day: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					<li class="">
						<label for="ms_a_date_given">Date Post-Partum Visit w/in one week after Delivery</label>
						<div class="fields">
  					<div>
              <?php echo form_input('visits_week', $postpartum->visits_week ? $postpartum->visits_week: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					<li class="date-meta even">
    				<label>Date and Time Initiated Breastfeeding</label>
    				
    				<div class="input datetime_input">
    				<?php echo form_input('breastfeeding', $postpartum->breastfeeding ? date('Y-m-d', $postpartum->breastfeeding) : '', 'maxlength="10" class="datepicker" class="text width-20"'); ?> &nbsp;
    				<?php echo form_dropdown('breastfeeding_on_hour', array(NULL => '-- Hour --') + $hours, $postpartum->breastfeeding ? date('H', $postpartum->breastfeeding): NULL,'class="chznsm"') ?> : 
    				<?php echo form_dropdown('breastfeeding_on_minute', array(NULL => '- Minute -') + $minutes, $postpartum->breastfeeding ? date('i', ltrim($postpartum->breastfeeding, '0')):NULL,'class="chznsm"') ?>
    				
    				</div>
    			</li>
				</ul>
			</fieldset>
		</div>
    	
		<div class="form_inputs" id="client-ms-tab">
		<h3></h3>
			<fieldset>
				<ul>
					<li class="">
						<label for="iron1_date">First Iron Date Given</label>
						<div class="fields">
  					<div>
              <?php echo form_input('iron1_date', $postpartum->iron1_date ? $postpartum->iron1_date: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					 <li>
    				<label for="iron1_tabs">First Iron Number of Tablets Given</label>
    				<div class="input">
						<?php echo form_input('iron1_tabs', $postpartum->iron1_tabs, 'id="iron1_tabs"'); ?>
						</div>
    			</li>	
    			<li class="even">
						<label for="iron2_date">Second Iron Date Given</label>
						<div class="fields">
  					<div>
              <?php echo form_input('iron2_date', $postpartum->iron2_date ? $postpartum->iron2_date: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					<li>
    				<label for="iron2_tabs">Second Iron Number of Tablets Given</label>
    				<div class="input">
						<?php echo form_input('iron2_tabs', $postpartum->iron2_tabs, 'id="iron2_tabs"'); ?>
						</div>
    			</li>	
    			<li class="">
						<label for="iron3_date">Third Iron Date Given</label>
						<div class="fields">
  					<div>
              <?php echo form_input('iron3_date', $postpartum->iron3_date ? $postpartum->iron3_date: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					<li>
    				<label for="iron3_tabs">Third Iron Number of Tablets Given</label>
    				<div class="input">
						<?php echo form_input('iron3_tabs', $postpartum->iron3_tabs, 'id="iron3_tabs"'); ?>
						</div>
    			</li>	
    			
    			<li class="even">
						<label for="vitamin_a">Date Given Vitamin A</label>
						<div class="fields">
  					<div>
              <?php echo form_input('vitamin_a', $postpartum->vitamin_a ? $postpartum->vitamin_a: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="client-remarks-tab">
			<fieldset>
				<ul>
          <li>
    				<label for="remarks">Remarks</label>
    				<div class="input">
						<?php echo form_input('remarks', $postpartum->remarks, 'id="remarks"'); ?>
						</div>
    			</li>	
  				
				</ul>
			</fieldset>
		</div>
	
		<div class="form_inputs" id="client-details-tab">
			<fieldset>
				<div class="left-corner">
              
        	     <img src="<?php echo base_url();?>uploads/<?php echo $client->photo; ?>" width="150px"/>
            </div>
   
   
   <div class="info-mid">
      <div class="fam-client-name">
          <?php echo $client->last_name; ?>, <?php echo $client->first_name; ?> <?php echo $client->middle_name; ?>
          <a href="admin/clients/edit/<?php echo $client->id; ?>" class="btn edit-info">Edit</a>
      </div>
      <div class="date-info"><?php echo format_date($client->dob, 'F j, Y'); ?></div>
        <div class="top-info">
        <?php $age = floor((time() - $client->dob)/31556926); ?>
						  <?php echo $age ? $age.' -' : ''; ?> <?php echo $client->gender; ?> <?php if(!empty($client->relation)): ?>- <?php echo $client->relation; ?><?php endif; ?> <?php if(!empty($client->phonenumber)): ?>- <span class="top-info-mob"><?php echo $client->phonenumber; ?></span><?php endif; ?> <?php if(!empty($client->phonenumber)): ?>- <span class="top-info-email"><?php echo $client->email; ?></span><?php endif; ?>
			  </div>
				
				<div class="mid-bot-client-info">
						<?php if(!empty($client->residence)): ?><?php echo $client->residence; ?>,<?php endif; ?>  <?php echo $client->address; ?> <?php echo $client->barangay_name; ?>, <?php echo $client->city_name; ?>, 
						<?php echo $client->province_name; ?> Region <?php echo $client->region_name; ?>
				</div>
						
					
					
   </div>
   <div class="right-corner">
        <p>Date Registered: <span class="r-info"><?php echo format_date($client->registration_date, 'F j, Y'); ?></span></p>
        <?php if(!empty($client->facility)): ?><p>Facility: <span class="r-info" style="text-transform:uppercase;"><?php echo $client->facility; ?></span></p><?php endif; ?>  
        <p>Family SN: <span class="r-info"><?php echo $client->serial_number; ?></span></p> 
        <p>FORM #: <span class="r-info"><?php echo $client->form_number; ?></span></p> 
        <?php if(!empty($client->bloodtype)): ?> <p>Blood Type: <span class="r-info"><?php echo $client->bloodtype; ?></span></p><?php endif; ?> 
   </div>
	 
			</fieldset>
		</div>


		
	
		
	</div>

	<div class="buttons float-right padding-top">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>

<?php echo form_close(); ?>

</section>

<style type="text/css">
form.crudli.date-meta div.selector {
    float: left;
    width: 30px;
}
form.crud li.date-meta div input#datepicker { width: 8em; }
form.crud li.date-meta div.selector { width: 5em; }
form.crud li.date-meta div.selector span { width: 1em; }
form.crud li.date-meta label.time-meta { min-width: 4em; width:4em; }
</style>