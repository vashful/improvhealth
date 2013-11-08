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
						<label for="first_name">Firstname <span>*</span></label>
						<div class="input">
						  <?php echo $client->first_name; ?>
						</div>
					</li>
					
					<li>
						<label for="last_name">Lastname <span>*</span></label>
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
						  <?php echo $client->age; ?>
						</div>
					</li>

          <li class="even">
						<label for="gender">Gender</label>
						<div class="input">
						<?php echo $client->gender; ?>
						</div>
					</li>
					
					<li>
						<label for="dob_day">Birth Date <span>*</span></label>
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
						<label for="facility">Facilty </label>
						<div class="input">
						  <?php echo $client->facility; ?>
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
    				<?php echo $client->history; ?>
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
						<?php echo $client->residence; ?>
						</div>
					</li>
					
          <li>
						<label for="address">Address <span>*</span></label>
						<div class="input">
						<?php echo $client->address; ?>
						</div>
					</li>
					
          <li class="even">
						<label for="barangay_id">Barangay <span>*</span></label>
						<div class="input"> 
						<?php echo $client->barangay_name; ?>
						</div>
					</li>
					
					<li>
						<label for="municipality_id">City/Municipality <span>*</span></label>
						<div class="input">
						<?php echo $client->city_name; ?>
						</div>
					</li>
					
					<li class="even">
					  <label for="province_id">Province <span>*</span></label>
						<div class="input">
						<?php echo $client->province_name; ?>
						</div>
					</li>
					
					<li>
					  <label for="region_id">Region <span>*</span></label>
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