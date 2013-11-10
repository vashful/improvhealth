<section class="title">
	<?php if ($this->method == 'add'): ?>
		<h4><?php echo sprintf('Register Client');?></h4>
		<?php echo form_open(uri_string(), 'class="crud" autocomplete="off"'); ?>
	
	<?php else: ?>
		<h4>Edit <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em></h4>
		<?php echo form_open(uri_string(), 'class="crud"'); ?>
	<?php endif; ?>
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu" style="display:none;">
			<li><a href="#client-details-tab"><span>Client Personal Information</span></a></li>
		</ul>

		<!-- Content tab -->
		<div class="form_inputs" id="client-details-tab">
			<fieldset>
				<ul>
					<li class="even">
						<label for="serial_number">Family Serial Number  <span>*</span></label>
						<div class="input">
							<?php echo form_input('serial_number', $client->serial_number, 'id="serial_number"'); ?>
						</div>
					</li>
				  <!--<li>
						<label for="form_number">Form Number/Code</label>
						<div class="input">
							<?php echo form_input('form_number', $client->form_number, 'id="form_number"'); ?>
						</div>
					</li>-->
					
					<li class="even">
						<label for="first_name">First Name <span>*</span></label>
						<div class="input">
							<?php echo form_input('first_name', $client->first_name, 'id="first_name"'); ?>
						</div>            
					</li>
					
					<li>
						<label for="last_name">Last Name <span>*</span></label>
						<div class="input">
							<?php echo form_input('last_name', $client->last_name, 'id="last_name"'); ?>
						</div>
					</li>
					
					<li class="even">
						<label for="middle_name">Middle Name <span>*</span></label>
						<div class="input">
							<?php echo form_input('middle_name', $client->middle_name, 'id="middle_name"'); ?>
						</div>
					</li> 
          
          <li class="even">
						<label for="email">Email Address </label>
						<div class="input">
							<?php echo form_input('email', $client->email, 'id="email"'); ?>
						</div>
					</li>   
					<!--
					<li>
						<label for="age">Age</label>
						<div class="input">
						<?php echo form_input('age', $client->age, 'id="age"'); ?>
						</div>
					</li>
          -->
          <li class="even">
						<label for="gender">Gender</label>
						<div class="input">
						<? $options = array(
                  'male'  => 'Male',
                  'female'    => 'Female'
                );?>
						<?php echo form_dropdown('gender', $options, $client->gender, 'id="gender" class="chznsm"'); ?>
						</div>
					</li>
					
					<li>
						<label for="dob">Date of Birth <span>*</span></label>
						<div class="fields">
  					<div>
              <?php echo form_input('dob', $client->dob ? $client->dob: '', 'maxlength="10" class="datepickerdob" class="text width-20"'); ?> (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					
          <li class="even">
						<label for="phonenumber">Phone Number/Mobile Number</label>
						<div class="input">
							<?php echo form_input('phonenumber', $client->phonenumber, 'id="phonenumber"'); ?>
						</div>
					</li>   
					
          <li class="even">
						<label for="gender">Blood Type</label>
						<div class="input">
						<? $options = array(
                  ''  => '--Please Select--',
                  'A'  => 'Type A',
                  'B'    => 'Type B',
                  'AB'    => 'Type AB',
                  'O'    => 'Type O'
                );?>
						<?php echo form_dropdown('bloodtype', $options, $client->bloodtype, 'id="bloodtype" class="chzn"'); ?>
						</div>
					</li>
					
          <li class="even">
						<label for="philhealth">Philhealth Number</label>
						<div class="input">
							<?php echo form_input('philhealth', $client->philhealth, 'id="philhealth"'); ?>
						</div>
					</li>   
					
          <li class="even">
						<label for="philhealth_type">Philhealth Membership Type</label>
						<div class="input">
						<? $options = array(
                  ''  => '--Please Select--',
                  'Member'  => 'Member',
                  'Dependent'    => 'Dependent'
                );?>
						<?php echo form_dropdown('philhealth_type', $options, $client->philhealth_type, 'id="philhealth_type" class="chzn"'); ?>
						</div>
					</li>
					
					<li class="even">
						<label for="philhealth_sponsor">Philhealth Sponsor</label>
						<div class="input">
						<? $options = array(
                  ''  => '--Please Select--',
                  'Congressional'  => 'Congressional',
                  'Province'    => 'Province',
                  'LGU'    => 'LGU',
                  'DOH'    => 'DOH',
                  'Government Employee'    => 'Government Employee',
                  'Private'    => 'Private',
                  'Self-Employed'    => 'Self-Employed'
                );?>
						<?php echo form_dropdown('philhealth_sponsor', $options, $client->philhealth_sponsor, 'id="philhealth_sponsor" class="chzn" style="width:230px"'); ?>
						</div>
					</li>
					
					<li class="even">
						<label for="relation">Relation </label>
						<div class="input">
						<?php echo form_input('relation', $client->relation, 'id="relation"'); ?>   
						</div>
					</li>
          		
    			<li>
						<label for="facility">Facility </label>
						<div class="input">
						<?php echo form_input('facility', $client->facility, 'id="facility"'); ?>
						</div>
					</li>
					<li>
    				<label for="history">Consultation History</label>
    				<div class="input">
    				<?php echo form_input('history', $client->history, 'id="history"'); ?>
    				</div>
    			</li>
						<li class="even">
						<label for="residence">Residence (House Owner)</label>
						<div class="input">
						<?php echo form_input('residence', $client->residence, 'id="residence"'); ?>
						(Please Enter Fullname)
						</div>
					</li>
					
          <li>
						<label for="address">Address <span>*</span></label>
						<div class="input">
						<?php echo form_input('address', $client->address, 'id="address"'); ?>
						(e.g. Purok 1)
						</div>
					</li>
					
          <li class="even">
						<label for="barangay_id">Barangay <span>*</span></label>
						<div class="input">
						<?php echo form_dropdown('barangay_id', $barangays, $client->barangay_id, 'id="barangay_id" class="chzn"'); ?>
						<div class="auto_add">[ <?php echo anchor('admin/clients/barangay/create', 'Add Barangay', 'target="_blank" class="cboxElement"'); ?> ]</div>
						</div>
					</li>
					
					<li>
						<label for="municipality_id">City/Municipality <span>*</span></label>
						<div class="input">
						<?php echo form_dropdown('city_id', $cities, $client->city_id, 'id="city_id" class="chzn"'); ?>
						<div class="auto_add">[ <?php echo anchor('admin/clients/city/create', 'Add City/Municipality', 'target="_blank" class="cboxElement"'); ?> ]</div>
						</div>
					</li>
					
					<li class="even">
					  <label for="province_id">Province <span>*</span></label>
						<div class="input">
						<?php echo form_dropdown('province_id', $provinces, $client->province_id, 'id="province_id" class="chzn"'); ?>
						<div class="auto_add">[ <?php echo anchor('admin/clients/province/create', 'Add Province', 'target="_blank" class="cboxElement"'); ?> ]</div>
						</div>
					</li>
					
					<li>
					  <label for="region_id">Region <span>*</span></label>
						<div class="input">
						<?php echo form_dropdown('region_id', $regions, $client->region_id, 'id="region_id" class="chzn"'); ?>
						<div class="auto_add">[ <?php echo anchor('admin/clients/region/create', 'Add Region', 'target="_blank" class="cboxElement"'); ?> ]</div>
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