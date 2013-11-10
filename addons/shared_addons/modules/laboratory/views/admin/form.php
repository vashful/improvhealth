<?php //print_r($sc);?>
<section class="title">
    <?php if ($sc->method_action == 'add'): ?>
    		<!--<h4>Add <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> as a Tuberculoses Client</h4>-->
    		<?php echo form_open(uri_string(), 'class="crud" autocomplete="off"'); ?>
       	<?php else: ?>
    		<h4><?php echo sprintf('Edit Tuberculoses Client "%s"', $client->first_name);?></h4>
    		<?php echo form_open(uri_string(), 'class="crud"'); ?>
	 <?php endif; ?>
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
			<li><a href="#client-info-tab-req"><span>Information</span></a></li>
			<li><a href="#client-urinalysis-tab"><span>Urinalysis</span></a></li>
			<li><a href="#client-clinical-chemistry-tab"><span>CLinical Chemistry</span></a></li>
			<li><a href="#client-remarks-tab-req"><span>Remarks</span></a></li>
		</ul>
    
		<!-- Content tab -->
		<div class="form_inputs" id="client-info-tab-req">
			<fieldset>
				<ul class="common-fields">
					<li>
						<label for="lab_name">Name</label>
						<?php echo form_input('name', '', 'id="lab_name" maxlength="50"'); ?>
					</li>
					<li>
						<label for="lab_age">Age</label>
						<?php echo form_input('age', '', 'id="lab_age" maxlength="2"'); ?>
					</li> 
					<li>
						<label for="lab_sex">Sex</label>
						<?php echo form_input('sex', '', 'id="lab_sex" maxlength="1"'); ?>
					</li>  
					<li>
						<label for="lab_date">Date</label>
						<?php echo form_input('lab_date', $sc->date_given ? $sc->date_given: '', 'id="lab_date" maxlength="10" class="datepicker" class="text width-20"'); ?>
						(yyyy-mm-dd)
					</li>
					<li>
						<label for="lab_type">Test Type</label>
						<div>
							<?php echo form_dropdown('lab_type', $diagnosis_list, $sc->diagnosis, 'id="lab_type" class="chzn"'); ?>
						</div>
					</li>
					
				</ul>
			</fieldset>
		</div>
		<div class="form_inputs" id="client-urinalysis-tab">
			<fieldset>
				<ul>
					<li>
						<label for="color1">Color</label>
						<?php echo form_input('color1', '', 'id="color1" maxlength="50"'); ?>
					</li>
					<li>
						<label for="sp_gravity1">Sp. Gravity</label>
						<?php echo form_input('sp_gravity1', '', 'id="sp_gravity1" maxlength="50"'); ?>
					</li> 
					<li>
						<label for="transparency1">Transparency</label>
						<?php echo form_input('transparency1', '', 'id="transparency1" maxlength="50"'); ?>
					</li>  
					<li>
						<label for="reaction1">Reaction</label>
						<?php echo form_input('reaction1', '', 'id="reaction1" maxlength="50"'); ?>
					</li> 
					<li>
						<label for="sugar1">Sugar</label>
						<?php echo form_input('sugar1', '', 'id="sugar1" maxlength="50"'); ?>
					</li>  
					<li>
						<label for="abumin1">Albumin</label>
						<?php echo form_input('abumin1', '', 'id="abumin1" maxlength="50"'); ?>
					</li> 
					<li>
						<h4>Microscopic Exam</h4>
					</li>  
					<li>
						<label for="pus_cells1">Pus cells (WBC)</label>
						<?php echo form_input('pus_cells1', '', 'id="pus_cells1" maxlength="50"'); ?>
					</li>  
					<li>
						<label for="rbc1">RBC</label>
						<?php echo form_input('rbc1', '', 'id="rbc1" maxlength="50"'); ?>
					</li>  
					<li>
						<label for="bacteria1">Bacteria</label>
						<?php echo form_input('bacteria1', '', 'id="bacteria1" maxlength="50"'); ?>
					</li> 
					<li>
						<label for="epithelium1">Epithelium</label>
						<?php echo form_input('epithelium1', '', 'id="epithelium1" maxlength="50"'); ?>
					</li> 
					<li>
						<label for="casts1">Casts</label>
						<?php echo form_input('casts1', '', 'id="casts1" maxlength="50"'); ?>
					</li> 
					<li>
						<label for="cystals1">Crystals</label>
						<?php echo form_input('cystals1', '', 'id="cystals1" maxlength="50"'); ?>
					</li> 
					<li>
						<label for="others1">Others</label>
						<?php echo form_input('others1', '', 'id="others1" maxlength="50"'); ?>
					</li> 
				</ul>
			</fieldset>
		</div>
		<div class="form_inputs" id="client-clinical-chemistry-tab">
			<fieldset>
				<ul>
					<li>
						<label for="color1">Color</label>
						<?php echo form_input('color1', '', 'id="color1" maxlength="50"'); ?>
					</li>
					<li>
						<label for="sp_gravity1">Sp. Gravity</label>
						<?php echo form_input('sp_gravity1', '', 'id="sp_gravity1" maxlength="50"'); ?>
					</li> 
					<li>
						<label for="transparency1">Transparency</label>
						<?php echo form_input('transparency1', '', 'id="transparency1" maxlength="50"'); ?>
					</li>  
					<li>
						<label for="reaction1">Reaction</label>
						<?php echo form_input('reaction1', '', 'id="reaction1" maxlength="50"'); ?>
					</li> 
					<li>
						<label for="sugar1">Sugar</label>
						<?php echo form_input('sugar1', '', 'id="sugar1" maxlength="50"'); ?>
					</li>  
					<li>
						<label for="abumin1">Albumin</label>
						<?php echo form_input('abumin1', '', 'id="abumin1" maxlength="50"'); ?>
					</li> 
					<li>
						<h4>Microscopic Exam</h4>
					</li>  
					<li>
						<label for="pus_cells1">Pus cells (WBC)</label>
						<?php echo form_input('pus_cells1', '', 'id="pus_cells1" maxlength="50"'); ?>
					</li>  
					<li>
						<label for="rbc1">RBC</label>
						<?php echo form_input('rbc1', '', 'id="rbc1" maxlength="50"'); ?>
					</li>  
					<li>
						<label for="bacteria1">Bacteria</label>
						<?php echo form_input('bacteria1', '', 'id="bacteria1" maxlength="50"'); ?>
					</li> 
					<li>
						<label for="epithelium1">Epithelium</label>
						<?php echo form_input('epithelium1', '', 'id="epithelium1" maxlength="50"'); ?>
					</li> 
					<li>
						<label for="casts1">Casts</label>
						<?php echo form_input('casts1', '', 'id="casts1" maxlength="50"'); ?>
					</li> 
					<li>
						<label for="cystals1">Crystals</label>
						<?php echo form_input('cystals1', '', 'id="cystals1" maxlength="50"'); ?>
					</li> 
					<li>
						<label for="others1">Others</label>
						<?php echo form_input('others1', '', 'id="others1" maxlength="50"'); ?>
					</li> 
				</ul>
			</fieldset>
		</div>
		
    <div class="form_inputs" id="client-remarks-tab-req">
			<fieldset>
				<ul>
          <li>
    				<label for="remarks">Remarks</label>
    				<div class="input">
						<?php echo form_input('remarks', $sc->remarks, 'id="remarks"'); ?>
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