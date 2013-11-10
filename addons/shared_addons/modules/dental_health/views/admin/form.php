<?php //print_r($postpartum);?>
<section class="title">
        <?php if ($dental_health->method_action == 'add'): ?>
    		<h4>Add <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> in Dental Health Care</h4>
    		<?php echo form_open(uri_string(), 'class="crud" autocomplete="off"'); ?>
       	<?php else: ?>
    		<h4>Edit <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> in Dental Health Care</h4>
    		<?php echo form_open(uri_string(), 'class="crud"'); ?>
	 <?php endif; ?>
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
			<li><a href="#client-dental-health-tab"><span>Dental Health Care</span></a></li>		
			<li><a href="#client-details-tab"><span>Client Detail Information</span></a></li>
		</ul>
    
		<!-- Content tab -->
		
		<div class="form_inputs" id="client-dental-health-tab">
			<fieldset>
			<ul>						
            			        <li class="even">
						<label for="serial_number">Orally Fit</label>
						<div class="input">
                                                        <?php echo form_hidden('client_id', $dental_health->client_id, 'id="client_id"') ?>
                                                        <?php echo form_checkbox(array('name' => 'orally_fit', 'value' => 'Y', 'checked' => 'checked', 'class' => 'dh-chk-box'));?>(Uncheck if there is any dental problem)
						</div>
					</li>
                                        <li>
						<label for="form_number">Date Given BOHC</label>
						<div class="input">
						        <?php echo form_input('date_given_bohc', $dental_health->date_given_bohc ? $dental_health->date_given_bohc: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
                                                </div>
					</li>
					<li class="even">
						<label for="serial_number">BOHC - Services</label>
						<div class="input">
							<?php echo form_input('bohc_services', $dental_health->bohc_services, 'id="bohc_services"'); ?>
						</div>
					</li>
					<li class="even">
						<label for="serial_number">Remarks</label>
						<div class="input">
							<?php echo form_input('remarks', $dental_health->remarks, 'id="remarks"'); ?>
						</div>
					</li>
		                                           
				</ul>
			</fieldset>
		</div>
    	
			
		<div class="form_inputs" id="client-details-tab">
			<fieldset>
				<ul>
					<li class="even">
						<label for="serial_number">Family Serial Number</label>
						<div class="input">
							<?php echo $client->serial_number; ?>
						</div>
					</li>
                                        <li>
						<label for="form_number">Form Number/Code</label>
						<div class="input">
						  <?php echo $client->form_number; ?>
						</div>
					</li>
					
					<li class="even">
						<label for="first_name">First Name</label>
						<div class="input">
						  <?php echo $client->first_name; ?>
						</div>
					</li>
					
					<li>
						<label for="last_name">Last Name</label>
						<div class="input">
						  <?php echo $client->last_name; ?>
						</div>
					</li>
					
					<li class="even">
						<label for="middle_name">Middle Name </label>
						<div class="input">
						  <?php echo $client->middle_name; ?>
						</div>
					</li>  
					
					<li>
						<label for="age">Age</label>
						<div class="input">
						  <?php $age = floor((time() - $client->dob)/31556926); ?>
						  <?php echo $age ? $age : ''; ?>
						</div>
					</li>

                                        <li class="even">
						<label for="gender">Gender</label>
						<div class="input">
						<?php echo $client->gender; ?>
						</div>
					</li>
					
					<li>
						<label for="dob_day">Birth Date</label>
						<div class="fields">
                  					<div>
                                                                <?php echo format_date($client->dob); ?>
                  					</div>
                  				</div>                                                        
					</li>
					
					<li class="even">
						<label for="relation">Relation </label>
						<div class="input">
						  <?php echo $client->relation; ?>
						</div>
					</li>
          		
    			                 <li>
						<label for="facility">Facility </label>
						<div class="input">
						  <?php echo $client->facility; ?>
						</div>
					</li>
					<li>
    				<label for="history">Consultation History</label>
    				<?php echo $client->history; ?>
    			</li> 
          	<li class="even">
						<label for="residence">Residence</label>
						<div class="input">
						<?php echo $client->residence; ?>
						</div>
					</li>
					
          <li>
						<label for="address">Address</label>
						<div class="input">
						<?php echo $client->address; ?>
						</div>
					</li>
					
          <li class="even">
						<label for="barangay_id">Barangay</label>
						<div class="input"> 
						<?php echo $client->barangay_name; ?>
						</div>
					</li>
					
					<li>
						<label for="municipality_id">City/Municipality</label>
						<div class="input">
						<?php echo $client->city_name; ?>
						</div>
					</li>
					
					<li class="even">
					  <label for="province_id">Province</label>
						<div class="input">
						<?php echo $client->province_name; ?>
						</div>
					</li>
					
					<li>
					  <label for="region_id">Region</label>
						<div class="input">
						<?php echo $client->region_name; ?>
						</div>
					</li>
    			
				</ul>
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