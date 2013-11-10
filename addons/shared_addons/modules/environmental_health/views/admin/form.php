<?php //print_r($postpartum);?>
<section class="title">
    <?php if ($environmental_health->method_action == 'add'): ?>
    		<h4>Add <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> in Environmental Health</h4>
    		<?php echo form_open(uri_string(), 'class="crud" autocomplete="off"'); ?>
       	<?php else: ?>
    		<h4>Edit <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> in Environmental Health</h4>
    		<?php echo form_open(uri_string(), 'class="crud"'); ?>
	 <?php endif; ?>
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
			<li><a href="#environment-health-tab"><span>Environmental Health Care</span></a></li>
			<li><a href="#client-details-tab"><span>Client Detail Information</span></a></li>
		</ul>
    
		<!-- Content tab -->
		
		<div class="form_inputs" id="environment-health-tab">
			<fieldset>
				<ul>
                                        <li class="even">
						<label for="serial_number">Date Conducted</label>
						<div class="input">
                                                        <?php echo form_hidden('client_id', $environmental_health->client_id, 'id="client_id"') ?>
                                                        <?php echo form_input('date_conducted', $environmental_health->date_conducted ? $environmental_health->date_conducted: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>                                                      
						</div>
					</li>
					<li class="section-header">
						<label for="form_number">Household </label>
					</li>
                                        <li class="even-eh">
						<label for="form_number">Access to improved or safe water</label>
						<div class="input">
                                                        <?php echo form_checkbox(array('name' => 'hh_safe_water', 'value' => 'Y', 'checked' => $environmental_health->hh_safe_water=='Y' ? 'checked' : '', 'class' => 'eh-chk-box'));?> 
                                                        <?php echo form_dropdown('hh_safe_water_level', array(NULL => '-- Select a Level --') + $access_level, $environmental_health->hh_safe_water_level, 'id="hh_safe_water_level" class="chzn"'); ?> (Safe Water Access Level)
                                                </div>
					</li>
					<li class="even-eh">
						<label for="form_number">Sanitay Toilet</label>
						<div class="input">
                                                        <?php echo form_checkbox(array('name' => 'hh_sanitary_toilet', 'value' => 'Y', 'checked' => $environmental_health->hh_sanitary_toilet=='Y' ? 'checked' : '', 'class' => 'eh-chk-box'));?>  
                                                        
                                                </div>
					</li>
					<li class="even-eh">
						<label for="form_number">Solid Waste Disposal</label>
						<div class="input">
                                                        <?php echo form_checkbox(array('name' => 'hh_satisfactory_waste_disposal', 'value' => 'Y', 'checked' => $environmental_health->hh_satisfactory_waste_disposal=='Y' ? 'checked' : '', 'class' => 'eh-chk-box'));?>  
                                                        
                                                </div>
					</li>
					<li class="even-eh">
						<label for="form_number">Complete Basic Sanitation Facilities</label>
						<div class="input">
                                                        <?php echo form_checkbox(array('name' => 'hh_complete_sanitation_facility', 'value' => 'Y', 'checked' => $environmental_health->hh_complete_sanitation_facility=='Y' ? 'checked' : '', 'class' => 'eh-chk-box'));?>  
                                                        
                                                </div>
					</li>       
					<li class="section-header">
						<label for="form_number">Food Establishments and Handlers </label>
					</li>
					<li class="even-eh">
						<label for="form_number">Food Establishment</label>
						<div class="input">
                                                        <?php echo form_checkbox(array('name' => 'food_establishment', 'value' =>  'Y', 'checked' => $environmental_health->food_establishment=='Y' ? 'checked' : '', 'class' => 'eh-chk-box'));?>  
                                                        
                                                </div>
					</li>
					<li class="even-eh">
						<label for="form_number">Food Establishment - Health Certificate</label>
						<div class="input">
                                                        <?php echo form_checkbox(array('name' => 'food_establishment_sanitary_permit', 'value' => 'Y', 'checked' => $environmental_health->food_establishment_sanitary_permit=='Y' ? 'checked' : '', 'class' => 'eh-chk-box'));?>  
                                                        
                                                </div>
					</li>
					<li class="even-eh">
						<label for="form_number">Food Handler</label>
						<div class="input">
                                                        <?php echo form_checkbox(array('name' => 'food_handler', 'value' => 'Y', 'checked' => $environmental_health->food_handler=='Y' ? 'checked' : '', 'class' => 'eh-chk-box'));?>  
                                                        
                                                </div>
					</li>
					<li class="even-eh">
						<label for="form_number">Food Handler - Health Certificate</label>
						<div class="input">
                                                        <?php echo form_checkbox(array('name' => 'food_handler_health_certificate', 'value' => 'Y', 'checked' => $environmental_health->food_handler_health_certificate=='Y' ? 'checked' : '', 'class' => 'eh-chk-box'));?>  
                                                        
                                                </div>
					</li>
					<li class="section-header">
						<label for="form_number">Others</label>
					</li>
					<li class="even-eh">
						<label for="form_number">Salt Sample Tested</label>
						<div class="input">
                                                        <?php echo form_checkbox(array('name' => 'salt_sample_tested', 'value' => 'Y', 'checked' => $environmental_health->salt_sample_tested=='Y' ? 'checked' : '', 'class' => 'eh-chk-box'));?>  
                                                        
                                                </div>
					</li>
					<li class="even-eh">
						<label for="form_number">Salt Sample Tested - Positive for Iodine</label>
						<div class="input">
                                                        <?php echo form_checkbox(array('name' => 'salt_sample_iodine', 'value' => 'Y' , 'checked' => $environmental_health->salt_sample_iodine=='Y' ? 'checked' : '', 'class' => 'eh-chk-box'));?>  
                                                        
                                                </div>
					</li>
					<li class="even-eh">
						<label for="serial_number">Remarks</label>
						<div class="input">
							<?php echo form_input('remarks', $environmental_health->remarks, 'id="remarks"'); ?>
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
                                        <li class="even">
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
					<li class="even">
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
					<li class="even">
						<label for="age">Age</label>
						<div class="input">
                                                        <?php echo $client->age; ?>
						</div>
					</li>
                                        <li class="even">
						<label for="gender">Gender</label>
						<div class="input">
                                                        <?php echo $client->gender; ?>
						</div>
					</li>
					<li class="even">
						<label for="dob_day">Birth Date</label>
						<div class="fields">
                                                        <?php echo format_date($client->dob); ?>
                  				</div>                                                        
					</li>
					<li class="even">
						<label for="relation">Relation </label>
						<div class="input">
                                                        <?php echo $client->relation; ?>
						</div>
                                        </li>
                                        <li class="even">
						<label for="facility">Facility </label>
						<div class="input">
                                                        <?php echo $client->facility; ?>
						</div>
					</li>
					<li class="even">
                    				<label for="history">Consultation History</label>
                    				<div class="input">
                                                        <?php echo $client->history; ?>
                                                </div>
    			                </li> 
                                        <li class="even">
						<label for="residence">Residence</label>
						<div class="input">
                                                        <?php echo $client->residence; ?>
						</div>
					</li>		
                                        <li class="even">
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
					<li class="even">
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
					<li class="even">
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
li.section-header {padding: 2px 2px 2px 2px;}
li.even-eh {padding: 2px 2px 2px 20px;}
li.even-eh div.input{padding-top: 10px;}
.section-header label {font-size: 16px; font-weight: normal;}
</style>