<section class="title">
		<h4><?php echo sprintf('Edit "%s"', $client->last_name.', '.$client->first_name);?> in Prenatal Care </h4>
		<?php echo form_open(uri_string(), 'class="crud"'); ?> 
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
			<li><a href="#prenatal-clients-tab"><span>Prenatal Information</span></a></li>
			<li><a href="#prenatal-visits-tab"><span>Prenatal Date Visits</span></a></li>
			<li><a href="#prenatal-tetanus-tab"><span>Tetanus Toxiod Vaccine Date Given</span></a></li>
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
							<div>
          						  <?php echo form_hidden('client_id', $prenatal_client->pn_id, 'id="pn_id"') ?>
                                                          <?php echo form_input('last_menstrual_period', $prenatal_client->last_menstrual_period ? date('Y-m-d', $prenatal_client->last_menstrual_period) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                          (yyyy-mm-dd) 
          					        </div>
						</div>
					</li>
          <li class="even">
						<label for="input">G - Gravida <span>*</span></label>
						<div class="input">
							<?php echo form_input('gravida', $prenatal_client->gravida, 'id="gravida" class="small-input"'); ?>
						</div>
					</li>
          <li class="even">
						<label for="input">T - Term <span>*</span></label>
						<div class="input">
							<?php echo form_input('term', $prenatal_client->term, 'id="term" class="small-input"'); ?>
						</div>
					</li>
          <li class="even">
						<label for="input">P - Para <span>*</span></label>
						<div class="input">
              <?php echo form_input('para', $prenatal_client->para, 'id="para" class="small-input"'); ?>
						</div>
					</li>
          <li class="even">
						<label for="input">A - Abortion <span>*</span></label>
						<div class="input">
              <?php echo form_input('abortion', $prenatal_client->abortion, 'id="abortion" class="small-input"'); ?>
						</div>
					</li>
          <li class="even">
						<label for="input">L - Live Birth <span>*</span></label>
						<div class="input">
              <?php echo form_input('live', $prenatal_client->live, 'id="live" class="small-input"'); ?>
						</div>
					</li>
					
					<li class="even">
						<label for="first_name">EDC (Estimated Date Confinement)</label>
						<div class="input">
							<div>
          						<?php echo form_input('estimated_date_confinement', $prenatal_client->estimated_date_confinement ? date('Y-m-d', $prenatal_client->estimated_date_confinement) : '', 'maxlength="10" class="text width-20 datepicker" '); ?>
          						(yyyy-mm-dd) 
          					        </div>
						</div>
					</li>
					
					<li class="even">
						<label for="middle_name">Tetanus Status</label>
						<div class="input">
							<div>
          						<?php echo form_input('tetanus_status', $prenatal_client->tetanus_status, 'id="tetanus_status"'); ?>                                                                  
          					        </div>
                                                </div>
					</li>
					<li class="even">
						<label for="relation">Risk Code </label>
						<div class="input">
						          <?php echo form_dropdown('risk_code', $risk_code, isset($prenatal_client->risk_code) ? $prenatal_client->risk_code : 0, 'class="chzn"') ?>
						</div>
					</li>
          		                <li class="even">
						<label for="facility">Risk - Date Detected </label>
						<div class="input">       
          						<div>
          						<?php echo form_input('risk_date_detected', $prenatal_client->risk_date_detected ? date('Y-m-d', $prenatal_client->risk_date_detected) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
          						(yyyy-mm-dd) 
          					        </div>
                                                </div>
					</li>
					<li class="even">
						<label for="facility">Pregnancy - Date Terminated</label>
						<div class="input">       
          						<div>
          						<?php echo form_input('pregnancy_date_terminated', $prenatal_client->pregnancy_date_terminated ? date('Y-m-d', $prenatal_client->pregnancy_date_terminated) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
          						(yyyy-mm-dd) 
          					        </div>
                                                </div>
					</li>
					<li class="even">
						<label for="facility">Pregnancy - Outcome </label>
						<div class="input">
						          <?php echo form_dropdown('pregnancy_outcome', $livebirths_outcome, isset($prenatal_client->pregnancy_outcome) ? $prenatal_client->pregnancy_outcome : 0, 'class="chzn"') ?>
                                                </div>
					</li>
					<li class="even">
						<label for="facility">Livebirths - Birth Weight </label>
						<div class="input">
						<?php echo form_input('livebirths_birth_weight', $prenatal_client->livebirths_birth_weight, 'id="livebirths_birth_weight"'); ?> (in Grams)
						</div>
					</li>
					<li class="even">
						<label for="facility">Livebirths - Place of Delivery </label>
						<div class="input">
						<?php echo form_input('livebirths_place_delivery', $prenatal_client->livebirths_place_delivery, 'id="livebirths_place_delivery"'); ?>
						</div>
					</li>
					<li class="even">
						<label for="facility">Livebirths - Type of Delivery </label>
						<div class="input">
						          <?php echo form_dropdown('livebirths_type_delivery', array(NULL => '-- Select a Type of Delivery --') + $livebirths_type_delivery, $prenatal_client->livebirths_type_delivery, 'id="livebirths_type_delivery" class="chzn"') ?>
                                                </div>
					</li>
					<li class="even">
						<label for="facility">Livebirths - Attended by </label>
						<div class="input">
							<?php echo form_dropdown('livebirths_attended_by', $livebirths_attendant, isset($prenatal_client->livebirths_attended_by) ? $prenatal_client->livebirths_attended_by : 0,'class="chzn"') ?>
                                                </div>
					</li>
					<li class="even">
						<label for="facility">Remarks</label>
						<div class="input">
						<?php echo form_input('remarks', $prenatal_client->remarks, 'id="remarks"'); ?>
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
                    <li style="border-bottom: 1px solid #d9d9d9;">                                    
                            <label for="history">First Trimester</label>
                            <div class="input">
                                    <?php echo form_input('prenatal_tri1_v1', $prenatal_client->prenatal_tri1_v1 ? date('Y-m-d', $prenatal_client->prenatal_tri1_v1) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                    (yyyy-mm-dd) 
                            </div> 
                            <label for="history"></label>
                            <div class="input">
                                    <?php echo form_input('prenatal_tri1_v2', $prenatal_client->prenatal_tri1_v2 ? date('Y-m-d', $prenatal_client->prenatal_tri1_v2) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                    (yyyy-mm-dd) 
                            </div>
                            <label for="history"></label>
                            <div class="input">
                                    <?php echo form_input('prenatal_tri1_v3', $prenatal_client->prenatal_tri1_v3 ? date('Y-m-d', $prenatal_client->prenatal_tri1_v3) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                    (yyyy-mm-dd) 
                            </div>
                    </li>                
                    <li style="border-bottom: 1px solid #d9d9d9;">      
                            <label for="history">Second Trimester</label>
                            <div class="input">
                                    <?php echo form_input('prenatal_tri2_v1', $prenatal_client->prenatal_tri2_v1 ? date('Y-m-d', $prenatal_client->prenatal_tri2_v1) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                    (yyyy-mm-dd) 
                            </div> 
                            <label for="history"></label>
                            <div class="input">
                                    <?php echo form_input('prenatal_tri2_v2', $prenatal_client->prenatal_tri2_v2 ? date('Y-m-d', $prenatal_client->prenatal_tri2_v2) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                    (yyyy-mm-dd) 
                            </div>
                            <label for="history"></label>
                            <div class="input">
                                    <?php echo form_input('prenatal_tri2_v3', $prenatal_client->prenatal_tri2_v3 ? date('Y-m-d', $prenatal_client->prenatal_tri2_v3) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                    (yyyy-mm-dd) 
                            </div>
                    </li>               
                    <li style="border-bottom: 1px solid #d9d9d9;">        
                            <label for="history">Third Trimester</label>
                            <div class="input">
                                    <?php echo form_input('prenatal_tri3_v1', $prenatal_client->prenatal_tri3_v1 ? date('Y-m-d', $prenatal_client->prenatal_tri3_v1) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                    (yyyy-mm-dd) 
                            </div> 
                            <label for="history"></label>
                            <div class="input" >
                                    <?php echo form_input('prenatal_tri3_v2', $prenatal_client->prenatal_tri3_v2 ? date('Y-m-d', $prenatal_client->prenatal_tri3_v2) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                    (yyyy-mm-dd) 
                            </div>
                            <label for="history"></label>
                            <div class="input">
                                    <?php echo form_input('prenatal_tri3_v3', $prenatal_client->prenatal_tri3_v3 ? date('Y-m-d', $prenatal_client->prenatal_tri3_v3) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                    (yyyy-mm-dd) 
                            </div>
                    </li>                                                                                                                                     			
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="prenatal-tetanus-tab">
			<fieldset>
				<ul>                                
                                        <li>
                                                <label for="history">TT1</label>
                                                <div class="input">
                                                        <?php echo form_input('tt1', $prenatal_client->tt1 ? date('Y-m-d', $prenatal_client->tt1) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        (yyyy-mm-dd) 
                                                </div>
                                        </li>    			        
                                        <li>
                                                <label for="history">TT2</label>
                                                <div class="input">
                                                        <?php echo form_input('tt2', $prenatal_client->tt2 ? date('Y-m-d', $prenatal_client->tt2) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        (yyyy-mm-dd) 
                                                </div>
                                        </li>
                                        <li>
                                                <label for="history">TT3</label>
                                                <div class="input">
                                                        <?php echo form_input('tt3', $prenatal_client->tt3 ? date('Y-m-d', $prenatal_client->tt3) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        (yyyy-mm-dd) 
                                                </div>
                                        </li>    			        
                                        <li>
                                                <label for="history">TT4</label>
                                                <div class="input">
                                                        <?php echo form_input('tt4', $prenatal_client->tt4 ? date('Y-m-d', $prenatal_client->tt4) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        (yyyy-mm-dd) 
                                                </div>
                                        </li>
                                        <li>
                                                <label for="history">TT5</label>
                                                <div class="input">
                                                        <?php echo form_input('tt5', $prenatal_client->tt5 ? date('Y-m-d', $prenatal_client->tt5) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
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
                                                        <?php echo form_input('date_given_vit_a', $prenatal_client->date_given_vit_a ? date('Y-m-d', $prenatal_client->date_given_vit_a) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
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
                                                        <?php echo form_input('iron1_date', $prenatal_client->iron1_date ? date('Y-m-d', $prenatal_client->iron1_date) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        <?php echo form_input('iron1_number', $prenatal_client->iron1_number, 'id="iron1_number"'); ?> (tabs)
                                                </div>
                                        </li>
                                        <li class="even">
                                                <div class="input">
                                                        <span>2.</span>
                                                        <?php echo form_input('iron2_date', $prenatal_client->iron2_date ? date('Y-m-d', $prenatal_client->iron2_date) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        <?php echo form_input('iron2_number', $prenatal_client->iron2_number, 'id="iron2_number"'); ?> (tabs)
                                                </div>
                                        </li>
                                        <li class="even">
                                                <div class="input">
                                                        <span>3.</span>
                                                        <?php echo form_input('iron3_date', $prenatal_client->iron3_date ? date('Y-m-d', $prenatal_client->iron3_date) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        <?php echo form_input('iron3_number', $prenatal_client->iron3_number, 'id="iron3_number"'); ?> (tabs) 
                                                </div>
                                        </li>
                                        <li class="even">
                                                <div class="input">
                                                        <span>4.</span>
                                                        <?php echo form_input('iron4_date', $prenatal_client->iron4_date ? date('Y-m-d', $prenatal_client->iron4_date) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        <?php echo form_input('iron4_number', $prenatal_client->iron4_number, 'id="iron4_number"'); ?> (tabs)
                                                </div>
                                        </li>
                                        <li class="even">
                                                <div class="input">
                                                        <span>5.</span>
                                                        <?php echo form_input('iron5_date', $prenatal_client->iron5_date ? date('Y-m-d', $prenatal_client->iron5_date) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        <?php echo form_input('iron5_number', $prenatal_client->iron5_number, 'id="iron5_number"'); ?> (tabs)
                                                </div>
                                        </li>
                                        <li class="even">
                                                <div class="input">
                                                        <span>6.</span>
                                                        <?php echo form_input('iron6_date', $prenatal_client->iron6_date ? date('Y-m-d', $prenatal_client->iron6_date) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
                                                        <?php echo form_input('iron6_number', $prenatal_client->iron6_number, 'id="iron6_number"'); ?> (tabs)
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
li.main-rc {padding: 2px 2px 2px 2px;}
li.sub-rc {padding: 2px 2px 2px 25px;}
</style>