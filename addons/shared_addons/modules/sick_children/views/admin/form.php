<?php //print_r($sc);?>
<section class="title">
    <?php if ($sc->method_action == 'add'): ?>
    		<h4>Add <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> as Sick Children</h4>
    		<?php echo form_open(uri_string(), 'class="crud" autocomplete="off"'); ?>
       	<?php else: ?>
    		<h4><?php echo sprintf('Edit Sick Children "%s"', $client->first_name);?></h4>
    		<?php echo form_open(uri_string(), 'class="crud"'); ?>
	 <?php endif; ?>
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
			<li><a href="#client-vitamin-tab"><span>Vitamin A Supplementation</span></a></li>
			<li><a href="#client-anemic-tab"><span>Anemic Children</span></a></li>
			<li><a href="#client-diarrhea-tab"><span>Diarrhea Cases</span></a></li>
			<li><a href="#client-pneumonia-tab"><span>Pneumonia Cases Seen</span></a></li>
			<li><a href="#client-remarks-tab"><span>Remarks</span></a></li>
			<li><a href="#client-details-tab"><span>Client Information</span></a></li>
		</ul>
    
		<!-- Content tab -->
		<div class="form_inputs" id="client-vitamin-tab">
			<fieldset>
				<ul>
					<li>
    				<label for="history">Check the Corresponding Month</label>
    				<div>
  						<?php echo form_checkbox('six_months', '1', $sc->six_months ? TRUE : FALSE, 'id=six_months'); ?> <span>6-11 Months</span>
  					    <?php echo form_checkbox('twelve_months', '1', $sc->twelve_months ? TRUE : FALSE, 'id=twelve_months'); ?> <span>12-59 Months</span>
  						<?php echo form_checkbox('sixty_months', '1', $sc->sixty_months ? TRUE :  FALSE, 'id=sixty_months'); ?> <span>60-71 Months</span>
  						<?php echo form_hidden('client_id', $client->id, 'id="client_id"') ?>
  					</div>
    			</li>
          <li class="even">
						<label for="drop_out_reason">Diagnosis/Findings*</label>
						<div class="input">
						<?php echo form_dropdown('diagnosis', $diagnosis_list, $sc->diagnosis, 'id="diagnosis" class="chzn"'); ?>
						</div>
					</li> 
					<li>
    				<label for="date_given">Date Given</label>
    				<div class="input">
						<?php echo form_input('date_given', $sc->date_given ? $sc->date_given: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
						(yyyy-mm-dd)
						</div>
    			</li>	
				</ul>
			</fieldset>
		</div>
		<div class="form_inputs" id="client-anemic-tab">
		  
			<fieldset>
			<h6 class="form-title">Anemic Children Given Iron Supplemention</h6>
				<ul>
					 <li>
    				<label for="anemic_age">Age in Months</label>
    				<div class="input">
						<?php echo form_input('anemic_age', $sc->anemic_age, 'id="anemic_age"'); ?>
						</div>
    			</li>	
    			<li class="even">
						<label for="date_started">Date Started</label>
						<div class="fields">
  					<div>
              <?php echo form_input('date_started', $sc->date_started ? $sc->date_started: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					<li >
						<label for="date_completed">Date Completed</label>
						<div class="fields">
  					<div>
              <?php echo form_input('date_completed', $sc->date_completed ? $sc->date_completed: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
				</ul>
			</fieldset>
		</div>
			<div class="form_inputs" id="client-diarrhea-tab">
			<fieldset>
				<ul>
					 <li>
    				<label for="diarrhea_age">Age in Months</label>
    				<div class="input">
						<?php echo form_input('diarrhea_age', $sc->diarrhea_age, 'id="diarrhea_age"'); ?>
						</div>
    			</li>	
    			<li class="even">
						<label for="ort">Date Given ORT</label>
						<div class="fields">
  					<div>
              <?php echo form_input('ort', $sc->ort ? $sc->ort: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					<li>
						<label for="ors">Date Given ORS</label>
						<div class="fields">
  					<div>
              <?php echo form_input('ors', $sc->ors ? $sc->ors: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					<li class="even">
						<label for="ors_zinc">Date Given ORS with Zinc</label>
						<div class="fields">
  					<div>
              <?php echo form_input('ors_zinc', $sc->ors_zinc ? $sc->ors_zinc: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
				</ul>
			</fieldset>
		</div>
			<div class="form_inputs" id="client-pneumonia-tab">
			<fieldset>
				<ul>
				 <li>
    				<label for="pneumonia_age">Age in Months</label>
    				<div class="input">
						<?php echo form_input('pneumonia_age', $sc->pneumonia_age, 'id="pneumonia_age"'); ?>
						</div>
    			</li>	
    			<li class="even">
						<label for="date_given_treatment">Date Given Treatment</label>
						<div class="fields">
  					<div>
              <?php echo form_input('date_given_treatment', $sc->date_given_treatment ? $sc->date_given_treatment: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
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