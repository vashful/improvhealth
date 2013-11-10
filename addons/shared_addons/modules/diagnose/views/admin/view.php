<section class="title">
		<h4>Client <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> Information</h4>
		<?php echo form_open(uri_string(), 'class="crud"'); ?>

</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
			<li><a href="#client-details-tab"><span>Client Personal Information</span></a></li>
			<li><a href="#client-history-tab"><span>Client History</span></a></li>
			<li><a href="#client-address-tab"><span>Client Address</span></a></li>
		</ul>

		<!-- Content tab -->
		<div class="form_inputs forms_view" id="client-details-tab">
			<fieldset><div class="rght-corner">

    	 <label for="serial_number"><span>Family Serial Number:</span><?php echo $client->serial_number; ?></label>
    	 <img src="<?php echo base_url();?>system/cms/themes/pyrocms/img/photos/default-image.png" />
       <div class="fam-client-name"><?php echo $client->last_name; ?>, <?php echo $client->first_name; ?> <?php echo $client->middle_name; ?></div>
				  
   </div>
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
						<label for="first_name">Name <span>*</span></label>
						<div class="input">
						  <?php echo $client->first_name; ?>
						  <?php echo $client->last_name; ?>
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
						<label for="facility">Facility </label>
						<div class="input">
						  <?php echo $client->facility; ?>
						</div>
					</li>
					

    			
				</ul>
					
	    		
	 
			</fieldset>
		</div>

		<div class="form_inputs forms_view" id="client-history-tab">
			<fieldset>
				<ul>
					<li>
    				<label for="history">Consultation History</label>
    				<div class="input">
            <?php echo $client->history; ?>
            </div>
    			</li>
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs forms_view" id="client-address-tab">
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
	<a class="btn red" href="admin/diagnose/set/my_list/<?php echo  $client->id; ?>">Diagnose</a>
	<a class="btn blue" href="admin/family/set/my_list/<?php echo  $client->id; ?>">Family Planning</a>
	<?php if($client->gender == 'female'):?>
	<a class="btn green" href="admin/prenatals/set/my_list/<?php echo  $client->id; ?>">Prenatal Care</a>
	<a class="btn green" href="admin/postpartum/set/my_list/<?php echo  $client->id; ?>">Postpartum Care</a>
	<?php endif;?>
	<a class="btn orange" href="admin/children_under_one/set/my_list/<?php echo  $client->id; ?>">Children Under 1 Year Old</a>
	<a class="btn orange" href="admin/sick_children/set/my_list/<?php echo  $client->id; ?>">Sick Children</a>
	
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('cancel') )); ?>
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