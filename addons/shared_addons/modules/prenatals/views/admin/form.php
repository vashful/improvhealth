<section class="title">
		<h4>Add <em>"<?php echo $prenatal_client->last_name; ?>, <?php echo $prenatal_client->first_name; ?>"</em> for Prenatal Visit</h4>
		<?php echo form_open(uri_string(), 'class="crud"'); ?>
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
			<li><a href="#prenatal-clients-tab"><span>Prenatal Visits Information</span></a></li>
			<li><a href="#prenatal-visits-tab"><span>Date Prenatal Visits</span></a></li>
			<li><a href="#prenatal-tetanus-tab"><span>Date Tetanus Toxiod Vaccine Given</span></a></li>
			<li><a href="#prenatal-micronutrients-tab"><span>Micronutrient Supplementation</span></a></li>
			<li><a href="#client-details-tab"><span>Client Detail Information</span></a></li>
		</ul>

		<!-- Content tab -->
		<div class="form_inputs" id="prenatal-clients-tab">
			<fieldset>
				
                                <ul>					                                                         
                                        <li class="even">
						<label for="serial_number">LMP(Last Menstrual Period)<span>*</span></label>
						<div class="input">
          						  <?php echo form_hidden('client_id', $prenatal_client->client_id, 'id="client_id"') ?>
                        <?php echo form_input('last_menstrual_period', $prenatal->last_menstrual_period, 'maxlength="10" class="text width-20 datepicker"'); ?>
                        (yyyy-mm-dd)
						</div>
					</li>
				        <li class="even">
						<label for="input">G - Gravida <span>*</span></label>
						<div class="input">
							<?php echo form_input('gravida', $prenatal->gravida, 'id="gravida" class="small-input"'); ?>
						</div>
					</li>
          <li class="even">
						<label for="input">T - Term <span>*</span></label>
						<div class="input">
							<?php echo form_input('term', $prenatal->term, 'id="term" class="small-input"'); ?>
						</div>
					</li>
          <li class="even">
						<label for="input">P - Para <span>*</span></label>
						<div class="input">
              <?php echo form_input('para', $prenatal->para, 'id="para" class="small-input"'); ?>
						</div>
					</li>
          <li class="even">
						<label for="input">A - Abortion <span>*</span></label>
						<div class="input">
              <?php echo form_input('abortion', $prenatal->abortion, 'id="abortion" class="small-input"'); ?>
						</div>
					</li>
          <li class="even">
						<label for="input">L - Live Birth <span>*</span></label>
						<div class="input">
              <?php echo form_input('live', $prenatal->live, 'id="live" class="small-input"'); ?>
						</div>
					</li>
          
					
					<li class="even">
						<label for="first_name">EDC (Estimated Date Confinement)<span>*</span></label>
						<div class="input">
          						<?php echo form_input('estimated_date_confinement', $prenatal->estimated_date_confinement, 'maxlength="10" class="text width-20 datepicker"'); ?>
                      (yyyy-mm-dd)          			
						</div>
					</li>
					
					<li class="even">
						<label for="middle_name">Tetanus Status</label>
						<div class="input">
          						<?php echo form_input('tetanus_status', $prenatal->tetanus_status, 'id="tetanus_status"'); ?>                                                                  
                                                </div>
					</li>
					<li class="even">
						<label for="relation">Risk Code </label>
						<div class="input">
						          <?php echo form_dropdown('risk_code', $risk_code, 0, 'id="risk_code" class="chzn"'); ?> (see below for information about each code) 
						</div>
					</li>
          		                <li class="even">
						<label for="facility">Risk - Date Detected </label>
						<div class="input">
          						<?php echo form_input('risk_date_detected', $prenatal->risk_date_detected, 'maxlength="10" class="text width-20 datepicker"'); ?>
                      (yyyy-mm-dd)          				
                                                </div>
					</li>
					<li class="even">
						<label for="facility">Pregnancy - Date Terminated</label>
						<div class="input">
          						<?php echo form_input('pregnancy_date_terminated', $prenatal->pregnancy_date_terminated, 'maxlength="10" class="text width-20 datepicker"'); ?>
          						(yyyy-mm-dd) 
                                                </div>
					</li>
					<li class="even">
						<label for="facility">Pregnancy - Outcome </label>
						<div class="input">
						          <?php echo form_dropdown('pregnancy_outcome', array(NULL => '-- Select a Pregnancy Outcome --') + $livebirths_outcome, $prenatal->pregnancy_outcome, 'id="pregnancy_outcome" class="chzn"') ?>
                                                </div>
					</li>
					<li class="even">
						<label for="facility">Livebirths - Birth Weight </label>
						<div class="input">
						          <?php echo form_input('livebirths_birth_weight', $prenatal->livebirths_birth_weight, 'id="livebirths_birth_weight"'); ?> (in Grams)
						</div>
					</li>
					<li class="even">
						<label for="facility">Livebirths - Place of Delivery </label>
						<div class="input">
						          <?php echo form_input('livebirths_place_delivery', $prenatal->livebirths_place_delivery, 'id="livebirths_place_delivery"'); ?>
						</div>
					</li>
					<li class="even">
						<label for="facility">Livebirths - Type of Delivery </label>
						<div class="input">
						          <?php echo form_dropdown('livebirths_type_delivery', array(NULL => '-- Select a Type of Delivery --') + $livebirths_type_delivery, $prenatal->livebirths_type_delivery, 'id="livebirths_type_delivery" class="chzn"') ?>
                                                </div>
					</li>
					<li class="even">
						<label for="facility">Livebirths - Attended by </label>
						<div class="input">
							<?php echo form_dropdown('livebirths_attended_by', array(NULL => '-- Select the Attendant --') + $livebirths_attendant, $prenatal->livebirths_attended_by, 'id="livebirths_attended_by" class="chzn"') ?>
                                                </div>
					</li>
					<li class="even">
						<label for="facility">Remarks</label>
						<div class="input">
						          <?php echo form_input('remarks', $prenatal->remarks, 'id="remarks"'); ?>
						</div>
					</li>
					<li class="even">
						<label for="facility">*Risk Code:</label>
						<div class="input">
                                                        <ul>
                                                                <li class="main-rc"><b>N</b> - Normal (No risk found)</li>
                                                                <li class="main-rc"><b>A</b> - An age less than 18 or greater than 35</li>
                                                                <li class="main-rc"><b>B</b> - Being less than 145 cm (4'9") tall</li>
                                                                <li class="main-rc"><b>C</b> - Having a fourth (or more) baby (or so called grand multi)</li>
                                                                <li class="main-rc"><b>D</b> - Having one or more of the ff:</li> 
                                                                        <li class="sub-rc"><b>(a)</b> a previous caesarian section</li> 
                                                                        <li class="sub-rc"><b>(b)</b> 3 consecutive miscarriages or stillborn bay and</li> 
                                                                        <li class="sub-rc"><b>(c)</b> postpartum hemorrhage</li>
                                                                <li class="main-rc"><b>E</b> - Having one or more of the ff medical conditions:</li> 
                                                                        <li class="sub-rc"><b>(1)</b> Tuberculosis</li> 
                                                                        <li class="sub-rc"><b>(2)</b> Heart Disease</li>
                                                                        <li class="sub-rc"><b>(3)</b> Diabetes</li> 
                                                                        <li class="sub-rc"><b>(4)</b> Bronchial Asthma</li>
                                                                        <li class="sub-rc"><b>(5)</b> Goiter</li>
                                                                <li class="main-rc"><b>O</b> - Others</li>
                                                        </ul>
						</div>
					</li>					
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="prenatal-visits-tab">
			<fieldset>
				<ul>
                        <li class="even" style="border-bottom: 1px solid #d9d9d9;">                                       
                                <label for="trimester1">First Trimester</label>
                                <div class="input">
                                        <?php echo form_input('prenatal_tri1_v1', $prenatal->prenatal_tri1_v1, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                        (yyyy-mm-dd) 
                                </div> 
                                <label for="history"></label>
                                <div class="input">
                                        <?php echo form_input('prenatal_tri1_v2', $prenatal->prenatal_tri1_v2, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                        (yyyy-mm-dd) 
                                </div>
                                <label for="history"></label>
                                <div class="input">
                                        <?php echo form_input('prenatal_tri1_v3', $prenatal->prenatal_tri1_v3, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                        (yyyy-mm-dd) 
                                </div>
                        </li>
                        <li class="even" style="border-bottom: 1px solid #d9d9d9;">
                                <label for="trimester1">Second Trimester</label>
                                <div class="input">
                                        <?php echo form_input('prenatal_tri2_v1', $prenatal->prenatal_tri2_v1, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                        (yyyy-mm-dd) 
                                </div> 
                                <label for="history"></label>
                                <div class="input">
                                        <?php echo form_input('prenatal_tri2_v2', $prenatal->prenatal_tri2_v2, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                        (yyyy-mm-dd) 
                                </div>
                                <label for="history"></label>
                                <div class="input">
                                        <?php echo form_input('prenatal_tri2_v3', $prenatal->prenatal_tri2_v3, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                        (yyyy-mm-dd) 
                                </div>
                        </li>
                        <li class="even" style="border-bottom: 1px solid #d9d9d9;">
                                <label for="trimester1">Third Trimester</label>
                                <div class="input">
                                        <?php echo form_input('prenatal_tri3_v1', $prenatal->prenatal_tri3_v1, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                        (yyyy-mm-dd) 
                                </div> 
                                <label for="history"></label>
                                <div class="input">
                                        <?php echo form_input('prenatal_tri3_v2', $prenatal->prenatal_tri3_v2, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                        (yyyy-mm-dd) 
                                </div>
                                <label for="history"></label>
                                <div class="input">
                                        <?php echo form_input('prenatal_tri3_v3', $prenatal->prenatal_tri3_v3, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                        (yyyy-mm-dd) 
                                </div>                              
                         </li>
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="prenatal-tetanus-tab">
			<fieldset>
				<ul>
                                        <li class="even">
                                                <label for="history">TT1</label>                                        
                    			        <div class="input">
                                                        <?php echo form_input('tt1', $prenatal->tt1, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        (yyyy-mm-dd) 
                                                </div>
                                        </li>
                                        <li class="even">
                                                <label for="history">TT2</label>                                        
                    			        <div class="input">
                                                        <?php echo form_input('tt2', $prenatal->tt2, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        (yyyy-mm-dd) 
                                                </div>
                                        </li>
                                        <li class="even">
                                                <label for="history">TT3</label>                                        
                    			        <div class="input">
                                                        <?php echo form_input('tt3', $prenatal->tt3, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        (yyyy-mm-dd) 
                                                </div>
                                        </li>
                                        <li class="even">
                                                <label for="history">TT4</label>                                        
                    			        <div class="input">
                                                        <?php echo form_input('tt4', $prenatal->tt4, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        (yyyy-mm-dd) 
                                                </div>
                                        </li>
                                        <li class="even">
                                                <label for="history">TT5</label>                                        
                    			        <div class="input">
                                                        <?php echo form_input('tt5', $prenatal->tt5, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        (yyyy-mm-dd) 
                                                </div>
                                        </li>
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="prenatal-micronutrients-tab">
			<fieldset>
				<ul>
					<li class="even">
						<label for="vitamin-a">Date Given Vitamin A</label>
						<div class="input">
                                                        <?php echo form_input('date_given_vit_a', $prenatal->date_given_vit_a, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        (yyyy-mm-dd) 
						</div>
					</li>
					<h6 class="form-title">Date and No. of Iron with Folic Acid was given</h6>
                                        <li class="even">
                                                <label for="-iron-date-header">Date Given (yyyy-mm-dd) </label>
                                                <label for="-iron-date-header">Number Given</label>
                                        </li>              
                                        <li class="even"> 
                                               <div class="input">
                                                        <span>1.</span>
                                                        <?php echo form_input('iron1_date', $prenatal->iron1_date, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        <?php echo form_input('iron1_number', $prenatal->iron1_number, 'id="iron1_number"'); ?> (tabs)
                                                </div>
                                        </li>
                                        <li class="even">
                                                <div class="input">
                                                        <span>2.</span>
                                                        <?php echo form_input('iron2_date', $prenatal->iron2_date, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        <?php echo form_input('iron2_number', $prenatal->iron2_number, 'id="iron2_number"'); ?> (tabs)
                                                </div>
                                        </li>
                                        <li class="even">
                                                <div class="input">
                                                        <span>3.</span>
                                                        <?php echo form_input('iron3_date', $prenatal->iron3_date, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        <?php echo form_input('iron3_number', $prenatal->iron3_number, 'id="iron3_number"'); ?> (tabs)
                                                </div>
                                        </li>
                                        <li class="even">
                                                <div class="input">
                                                        <span>4.</span>
                                                        <?php echo form_input('iron4_date', $prenatal->iron4_date, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        <?php echo form_input('iron4_number', $prenatal->iron4_number, 'id="iron4_number"'); ?> (tabs)
                                                </div>
                                        </li>
                                        <li class="even">
                                                <div class="input">
                                                        <span>5.</span>
                                                        <?php echo form_input('iron5_date', $prenatal->iron5_date, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        <?php echo form_input('iron5_number', $prenatal->iron5_number, 'id="iron5_number"'); ?> (tabs)
                                                </div>
                                        </li>
                                        <li class="even">
                                                <div class="input">
                                                        <span>6.</span>
                                                        <?php echo form_input('iron6_date', $prenatal->iron6_date, 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        <?php echo form_input('iron6_number', $prenatal->iron6_number, 'id="iron6_number"'); ?> (tabs)
                                                </div>
                                        </li>				
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="client-details-tab">
			<fieldset>
				<div class="left-corner">
              
        	     <img src="<?php echo base_url();?>uploads/<?php echo $prenatal_client->photo; ?>" width="150px"/>
            </div>
   
   
   <div class="info-mid">
      <div class="fam-client-name">
          <?php echo $prenatal_client->last_name; ?>, <?php echo $prenatal_client->first_name; ?> <?php echo $prenatal_client->middle_name; ?>
          <a href="admin/clients/edit/<?php echo $prenatal_client->id; ?>" class="btn edit-info">Edit</a>
      </div>
      <div class="date-info"><?php echo format_date($prenatal_client->dob, 'F j, Y'); ?></div>
        <div class="top-info">
        <?php $age = floor((time() - $prenatal_client->dob)/31556926); ?>
						  <?php echo $age ? $age.' -' : ''; ?> <?php echo $prenatal_client->gender; ?> <?php if(!empty($prenatal_client->relation)): ?>- <?php echo $prenatal_client->relation; ?><?php endif; ?> <?php if(!empty($prenatal_client->phonenumber)): ?>- <span class="top-info-mob"><?php echo $prenatal_client->phonenumber; ?></span><?php endif; ?> <?php if(!empty($prenatal_client->phonenumber)): ?>- <span class="top-info-email"><?php echo $prenatal_client->email; ?></span><?php endif; ?>
			  </div>
				
				<div class="mid-bot-client-info">
						<?php if(!empty($prenatal_client->residence)): ?><?php echo $prenatal_client->residence; ?>,<?php endif; ?>  <?php echo $prenatal_client->address; ?> <?php echo $prenatal_client->barangay_name; ?>, <?php echo $prenatal_client->city_name; ?>, 
						<?php echo $prenatal_client->province_name; ?> Region <?php echo $prenatal_client->region_name; ?>
				</div>
						
					
					
   </div>
   <div class="right-corner">
        <p>Date Registered: <span class="r-info"><?php echo format_date($prenatal_client->registration_date, 'F j, Y'); ?></span></p>
        <?php if(!empty($prenatal_client->facility)): ?><p>Facility: <span class="r-info" style="text-transform:uppercase;"><?php echo $prenatal_client->facility; ?></span></p><?php endif; ?>  
        <p>Family SN: <span class="r-info"><?php echo $prenatal_client->serial_number; ?></span></p> 
        <p>FORM #: <span class="r-info"><?php echo $prenatal_client->form_number; ?></span></p> 
        <?php if(!empty($prenatal_client->bloodtype)): ?> <p>Blood Type: <span class="r-info"><?php echo $prenatal_client->bloodtype; ?></span></p><?php endif; ?> 
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
li.main-rc {padding: 2px 2px 2px 2px;}
li.sub-rc {padding: 2px 2px 2px 25px;}
</style>