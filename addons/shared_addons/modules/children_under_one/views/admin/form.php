<?php //print_r($cuo);?>
<section class="title">
    <?php if ($cuo->method_action == 'add'): ?>
    		<h4>Add <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> in Child Under 1 Year Old</h4>
    		<?php echo form_open(uri_string(), 'class="crud" autocomplete="off"'); ?>
       	<?php else: ?>
    		<h4>Edit <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> in Child Under 1 Year Old</h4>
    		<?php echo form_open(uri_string(), 'class="crud"'); ?>
	 <?php endif; ?>
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
			<li><a href="#client-pi-tab"><span>Personal Info</span></a></li>
			<li><a href="#client-ns-tab"><span>Newborn Screening</span></a></li>
			<li><a href="#client-cpab-tab"><span>Protected at Birth</span></a></li>
			<li><a href="#client-ms-tab"><span>Micronutrient Supplementation</span></a></li>
			<li><a href="#client-im-tab"><span>Immunization</span></a></li>
			<li><a href="#client-br-tab"><span>Breastfed</span></a></li>
			<li><a href="#client-details-tab"><span>Detailed Information</span></a></li>
		</ul>
    
		<!-- Content tab -->
		
		<div class="form_inputs" id="client-pi-tab">
			<fieldset>
				<ul>
				  <li>
    				<label for="mother_name">Complete Name of Mother</label>
    				<div class="input">
						<?php echo form_input('mother_name', $cuo->mother_name, 'id="mother_name"'); ?>
						<?php echo form_hidden('client_id', $client->id, 'id="client_id"') ?>
						</div>
    			</li>
          <li>
    				<label for="remarks">Remarks</label>
    				<div class="input">
						<?php echo form_input('remarks', $cuo->remarks, 'id="remarks"'); ?>
						</div>
    			</li>	
  				
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="client-ns-tab">
			<fieldset>
				<ul>
					 <li>
    				<label for="nb_referral_date">Referral Date</label>     
    				<div class="input">
              <?php echo form_input('nb_referral_date', $cuo->nb_referral_date ? $cuo->nb_referral_date: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
						</div>
    			</li>	
    			<li class="even">
						<label for="nb_done_date">Date Done</label>
						<div class="fields">
  					<div>
              <?php echo form_input('nb_done_date', $cuo->nb_done_date ? $cuo->nb_done_date: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="client-cpab-tab">
			<fieldset>
				<ul>
    			<li class="even">
						<label for="cp_date_assess">Date Assess</label>
						<div class="fields">
  					<div>
              <?php echo form_input('cp_date_assess', $cuo->cp_date_assess ? $cuo->cp_date_assess: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					
					<li>
    				<label for="cp_tt_status">TT Status</label>
    				<div class="input">
						<?php echo form_input('cp_tt_status', $cuo->cp_tt_status, 'id="cp_tt_status"'); ?>
						</div>
    			</li>
	
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="client-ms-tab">
		<h3></h3>
			<fieldset>
				<ul> <h6 class="form-title">Vitamin A</h6>
					 <li>
    				<label for="ms_a_age_months">Age in Months</label>
    				<div class="input">
						<?php echo form_input('ms_a_age_months', $cuo->ms_a_age_months, 'id="ms_a_age_months"'); ?>
						</div>
    			</li>	
    			<li class="even">
						<label for="ms_a_date_given">Date Given</label>
						<div class="fields">
  					<div>
              <?php echo form_input('ms_a_date_given', $cuo->ms_a_date_given ? $cuo->ms_a_date_given: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					<h6 class="form-title">Iron</h6>
					 <li>
    				<label for="ms_iron_birth_weight">Birth Weight</label>
    				<div class="input">
						<?php echo form_input('ms_iron_birth_weight', $cuo->ms_iron_birth_weight, 'id="ms_iron_birth_weight"'); ?>
						</div>
    			</li>	
    			<li class="even">
						<label for="ms_iron_date_started">Date Started</label>
						<div class="fields">
  					<div>
              <?php echo form_input('ms_iron_date_started', $cuo->ms_iron_date_started ? $cuo->ms_iron_date_started: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					<li>
						<label for="ms_iron_date_completed">Date Completed</label>
						<div class="fields">
  					<div>
              <?php echo form_input('ms_iron_date_completed', $cuo->ms_iron_date_completed ? $cuo->ms_iron_date_completed: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
				</ul>
			</fieldset>
		</div>
		
		
		<div class="form_inputs" id="client-im-tab">
			<fieldset>
				<ul><h6 class="form-title">Date Immunization Recieved</h6>
				<li>
    				<label for="im_bcg">BCG</label>
    				<div class="fields">
  					<div>
                        <?php echo form_input('im_bcg', $cuo->im_bcg ? $cuo->im_bcg: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
                        (yyyy-mm-dd)
  					</div>
                    </div>    
    			</li>
                <li>
    				<label for="im_hepa_b_at_birth">Hepa B at Birth</label>
    				<div class="fields">
      					<div>
                            <?php echo form_input('im_hepa_b_at_birth', $cuo->im_hepa_b_at_birth ? $cuo->im_hepa_b_at_birth: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
                            (yyyy-mm-dd)
      					</div>
                    </div>    
    			</li>
                <li>
    				<label for="im_pentavalent_1">Pentavalent 1</label>
    				<div class="fields">
      					<div>
                            <?php echo form_input('im_pentavalent_1', $cuo->im_pentavalent_1 ? $cuo->im_pentavalent_1	: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
                            (yyyy-mm-dd)
      					</div>
                    </div>    
    			</li>
                <li>
    				<label for="im_pentavalent_2">Pentavalent 2</label>
    				<div class="fields">
      					<div>
                            <?php echo form_input('im_pentavalent_2', $cuo->im_pentavalent_2 ? $cuo->im_pentavalent_2	: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
                            (yyyy-mm-dd)
      					</div>
                    </div>    
    			</li>
                <li>
    				<label for="im_pentavalent_3">Pentavalent 3</label>
    				<div class="fields">
      					<div>
                            <?php echo form_input('im_pentavalent_3', $cuo->im_pentavalent_3 ? $cuo->im_pentavalent_3	: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
                            (yyyy-mm-dd)
      					</div>
                    </div>    
    			</li>
    			<li>
    				<label for="im_dpt1">DPT1</label>
    				<div class="fields">
  					<div>
              <?php echo form_input('im_dpt1', $cuo->im_dpt1 ? $cuo->im_dpt1: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>    
    			</li>
    			<li>
    				<label for="im_dpt2">DPT2</label>
    				<div class="fields">
  					<div>
              <?php echo form_input('im_dpt2', $cuo->im_dpt2 ? $cuo->im_dpt2: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>    
    			</li>
    			<li>
    				<label for="im_dpt3">DPT3</label>
    				<div class="fields">
  					<div>
              <?php echo form_input('im_dpt3', $cuo->im_dpt3 ? $cuo->im_dpt3: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>    
    			</li>
    			<li>
    				<label for="im_polio1">POLIO1</label>
    				<div class="fields">
  					<div>
              <?php echo form_input('im_polio1', $cuo->im_polio1 ? $cuo->im_polio1: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>    
    			</li>
    			<li>
    				<label for="im_polio2">POLIO2</label>
    				<div class="fields">
  					<div>
              <?php echo form_input('im_polio2', $cuo->im_polio2 ? $cuo->im_polio2: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>    
    			</li>
    			<li>
    				<label for="im_polio3">POLIO3</label>
    				<div class="fields">
  					<div>
              <?php echo form_input('im_polio3', $cuo->im_polio3 ? $cuo->im_polio3: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>    
    			</li>
          <li>
    				<label for="im_rotarix">Rotarix 1</label>
    				<div class="fields">
      					<div>
                            <?php echo form_input('im_rotarix1', $cuo->im_rotarix1 ? $cuo->im_rotarix1	: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
                            (yyyy-mm-dd)
      					</div>
              </div>    
    			</li>
    			<li>
    				<label for="im_rotarix">Rotarix 2</label>
    				<div class="fields">
      					<div>
                            <?php echo form_input('im_rotarix2', $cuo->im_rotarix2 ? $cuo->im_rotarix2	: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
                            (yyyy-mm-dd)
      					</div>
              </div>    
    			</li>
    			<li>
    				<label for="im_hepa_b1_with_in">HEPA B1 within 24 Hours</label>
    				<div class="fields">
  					<div>
              <?php echo form_input('im_hepa_b1_with_in', $cuo->im_hepa_b1_with_in ? $cuo->im_hepa_b1_with_in: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>    
    			</li>
    			<li>
    				<label for="im_hepa_b1_more_than">HEPA B1 more than 24 Hours</label>
    				<div class="fields">
  					<div>
              <?php echo form_input('im_hepa_b1_more_than', $cuo->im_hepa_b1_more_than ? $cuo->im_hepa_b1_more_than: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>    
    			</li>
    			
    			<li>
    				<label for="im_hepa_b2">HEPA B2</label>
    				<div class="fields">
  					<div>
              <?php echo form_input('im_hepa_b2', $cuo->im_hepa_b2 ? $cuo->im_hepa_b2: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>    
    			</li>
    			
    			<li>
    				<label for="im_hepa_b3">HEPA B3</label>
    				<div class="fields">
  					<div>
              <?php echo form_input('im_hepa_b3', $cuo->im_hepa_b3 ? $cuo->im_hepa_b3: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>    
    			</li>
    			
    			<li>
    				<label for="im_anti_measles">Anti-Measles Vaccine</label>
    				<div class="fields">
  					<div>
                      <?php echo form_input('im_anti_measles', $cuo->im_anti_measles ? $cuo->im_anti_measles: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
                      (yyyy-mm-dd)
  					 </div>
  				    </div>    
    			</li>
    			 <li>
    				<label for="im_mmr">MMR Vaccine</label>
    				<div class="fields">
  					<div>
                        <?php echo form_input('im_mmr', $cuo->im_mmr ? $cuo->im_mmr: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
                        (yyyy-mm-dd)
  					</div>
  				    </div>    
    			</li>
    			<li>
    				<label for="im_fully">Date Fully Immunized(FIC)</label>
    				<div class="fields">
  					<div>
              <?php echo form_input('im_fully', $cuo->im_fully ? $cuo->im_fully: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>    
    			</li>
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="client-br-tab">
			<fieldset>
				<ul>
					 <li>
    				<label for="bf_1">Put Check in which Child was Exclusively Breastfed</label>
    				<div class="input bf_1">
  						<?php echo form_checkbox('bf_1', '1', $cuo->bf_1 ? TRUE : FALSE, 'id=bf_1'); ?> <span>1st Month</span><br />
  						<?php echo form_checkbox('bf_2', '1', $cuo->bf_2 ? TRUE : FALSE, 'id=bf_2'); ?> <span>2nd Month</span><br />
  						<?php echo form_checkbox('bf_3', '1', $cuo->bf_3 ? TRUE : FALSE, 'id=bf_3'); ?> <span>3rd Month</span><br />
  						<?php echo form_checkbox('bf_4', '1', $cuo->bf_4 ? TRUE : FALSE, 'id=bf_4'); ?> <span>4th Month</span><br />
  						<?php echo form_checkbox('bf_5', '1', $cuo->bf_5 ? TRUE : FALSE, 'id=bf_5'); ?> <span>5th Month</span><br />
						</div>
    			</li>	
    			<li class="even">
						<label for="bf_6">Date for 6th Month Child was Exlusively Breastfed</label>
						<div class="fields">
  					<div>
              <?php echo form_input('bf_6', $cuo->bf_6 ? $cuo->bf_6: '', 'maxlength="10" class="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
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
		<?php if ($cuo->method_action != 'add'): ?>
		<a class="btn red confirm" href="admin/children_under_one/delete/<?php echo  $cuo->id; ?>">Delete</a>
		<?php endif;?>
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