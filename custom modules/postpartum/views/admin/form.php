<section class="title">
	<?php if ($this->method == 'add'): ?>
		<h4><?php echo sprintf('Add Client');?></h4>
		<?php echo form_open(uri_string(), 'class="crud" autocomplete="off"'); ?>
	
	<?php else: ?>
		<h4><?php echo sprintf('Edit Client "%s"', $client->first_name);?></h4>
		<?php echo form_open(uri_string(), 'class="crud"'); ?>
	<?php endif; ?>
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
			<li><a href="#client-details-tab"><span>Client Personal Information</span></a></li>
			<li><a href="#client-history-tab"><span>Client History</span></a></li>
			<li><a href="#client-address-tab"><span>Client Address</span></a></li>
		</ul>

		<!-- Content tab -->
		<div class="form_inputs" id="client-details-tab">
			<fieldset>
				<ul>
					<li class="even">
						<label for="serial_number">Family Serial Number</label>
						<div class="input">
							<?php echo form_input('serial_number', $client->serial_number, 'id="serial_number"'); ?>
						</div>
					</li>
				  <li>
						<label for="form_number">Form Number/Code</label>
						<div class="input">
							<?php echo form_input('form_number', $client->form_number, 'id="form_number"'); ?>
						</div>
					</li>
					
					<li class="even">
						<label for="first_name">Firstname <span>*</span></label>
						<div class="input">
							<?php echo form_input('first_name', $client->first_name, 'id="first_name"'); ?>
						</div>
					</li>
					
					<li>
						<label for="last_name">Lastname <span>*</span></label>
						<div class="input">
							<?php echo form_input('last_name', $client->last_name, 'id="last_name"'); ?>
						</div>
					</li>
					
					<li class="even">
						<label for="middle_name">Middle Name </label>
						<div class="input">
							<?php echo form_input('middle_name', $client->middle_name, 'id="middle_name"'); ?>
						</div>
					</li>  
					
					<li>
						<label for="age">Age</label>
						<div class="input">
						<?php echo form_input('age', $client->age, 'id="age"'); ?>
						</div>
					</li>

          <li class="even">
						<label for="gender">Gender</label>
						<div class="input">
						<? $options = array(
                  'male'  => 'Male',
                  'female'    => 'Female'
                );?>
						<?php echo form_dropdown('gender', $options, $client->gender, 'male', 'id="gender"'); ?>
						</div>
					</li>
					
					<li>
						<label for="dob_day">Birth Date <span>*</span></label>
						<div class="fields">
  					<div>

  						<?php echo form_dropdown('dob_day', $days, isset($client->dob_day) ? $client->dob_day : 1) ?>

  						<?php echo form_dropdown('dob_month', $months, isset($client->dob_month) ? $client->dob_month : 1) ?>

  						<?php echo form_dropdown('dob_year', $years, isset($client->dob_year) ? $client->dob_year : null) ?>
  					</div>
  				</div>                                                        
					</li>

					<li class="even">
						<label for="relation">Relation </label>
						<div class="input">
						<?php echo form_input('relation', $client->relation, 'id="relation"'); ?>
						</div>
					</li>
          		
    			<li>
						<label for="facility">Facilty </label>
						<div class="input">
						<?php echo form_input('facility', $client->facility, 'id="facility"'); ?>
						</div>
					</li>
					
				</ul>
			</fieldset>
		</div>

		<div class="form_inputs" id="client-history-tab">
			<fieldset>
				<ul>
					<li>
    				<label for="history">Consultation History</label>
    				<br style="clear: both;" />
    				<?php echo form_textarea(array('id' => 'history', 'name' => 'history', 'value' => $client->history, 'rows' => 5)); ?>
    			</li>
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="client-address-tab">
			<fieldset>
				<ul>
					<li class="even">
						<label for="residence">Residence</label>
						<div class="input">
						<?php echo form_input('residence', $client->residence, 'id="residence"'); ?>
						</div>
					</li>
					
          <li>
						<label for="address">Address <span>*</span></label>
						<div class="input">
						<?php echo form_input('address', $client->address, 'id="address"'); ?>
						</div>
					</li>
					
          <li class="even">
						<label for="barangay_id">Barangay <span>*</span></label>
						<div class="input">
						<?php echo form_dropdown('barangay_id', $barangays, $client->barangay_id, 'male', 'id="barangay_id"'); ?>
						[ <?php echo anchor('admin/clients/barangay/create', 'Add Barangay', 'target="_blank" class="cboxElement"'); ?> ]
						</div>
					</li>
					
					<li>
						<label for="municipality_id">City/Municipality <span>*</span></label>
						<div class="input">
						<?php echo form_dropdown('city_id', $cities, $client->city_id, 'male', 'id="city_id"'); ?>
						[ <?php echo anchor('admin/clients/city/create', 'Add City/Municipality', 'target="_blank" class="cboxElement"'); ?> ]
						</div>
					</li>
					
					<li class="even">
					  <label for="province_id">Province <span>*</span></label>
						<div class="input">
						<?php echo form_dropdown('province_id', $provinces, $client->province_id, 'male', 'id="province_id"'); ?>
						[ <?php echo anchor('admin/clients/province/create', 'Add Province', 'target="_blank" class="cboxElement"'); ?> ]
						</div>
					</li>
					
					<li>
					  <label for="region_id">Region <span>*</span></label>
						<div class="input">
						<?php echo form_dropdown('region_id', $regions, $client->region_id, 'male', 'id="region_id"'); ?>
						[ <?php echo anchor('admin/clients/region/create', 'Add Region', 'target="_blank" class="cboxElement"'); ?> ]
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