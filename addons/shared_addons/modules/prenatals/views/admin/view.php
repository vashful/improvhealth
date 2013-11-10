<section class="title">
		<h4><?php echo sprintf('Edit Client "%s"', $prenatal->last_name.', '.$prenatal->first_name);?></h4>
		<?php echo form_open(uri_string(), 'class="crud"'); ?>
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
			<li><a href="#client-details-tab"><span>Prenatal Visits Information</span></a></li>
			<li><a href="#client-prenatal-tab"><span>Prenatal Visits</span></a></li>
			<li><a href="#client-tetanus-tab"><span>Date Tetanus Toxid Vaccine Given</span></a></li>
			<li><a href="#client-micronutrient-tab"><span>Micronutrient Supplementation</span></a></li>
		</ul>

		<!-- Content tab -->
		<div class="form_inputs" id="client-details-tab">
			<fieldset>
				<ul>
					<li class="even">
						<label for="serial_number">Date of Registration</label>
						<div class="fields">
							<?php echo format_date($prenatal->registration_date); ?>
						</div>
					</li>
				  <li>
						<label for="form_number">LMP(Last Menstrual Period)</label>
						<div class="fields">
						  <?php echo format_date($prenatal->last_menstrual_period); ?>
						</div>
					</li>
					
					<li class="even">
						<label for="first_name">G-P</label>
						<div class="fields">
						  <?php echo $prenatal->gravida; ?>-<?php echo $prenatal->para; ?>
						</div>
					</li>
					
					<li>
						<label for="last_name">EDC</label>
						<div class="fields">
						  <?php echo format_date($prenatal->estimated_date_confinement); ?>
						</div>
					</li>
					
					<li class="even">
						<label for="middle_name">Risk Code</label>
						<div class="fields">
                                                <?php
                                                        if($prenatal->risk_code=="0") 
                                                                {echo "A - an age less than 18 or greater than 35";}
                                                        elseif($prenatal->risk_code=="1")
                                                                {echo "B -  being less than 145 cm (4'9\") tall";}
                                                        elseif($prenatal->risk_code=="2")
                                                                {echo "C - Having a fourth (or more) baby (or so called grand multi)";}  
                                                        elseif($prenatal->risk_code=="3")
                                                                {echo "D - Having one or more of the ff: (a) a previous caesarian section (b) 3 consective miscarriages or stillborn bay and (c) postpartum hemorrhage";}
                                                        elseif($prenatal->risk_code=="4")
                                                                {echo "E - Having one or more of the ff medical condtions: (1)Tuberculosis (2)Heart Disease (3)Diabetes (4)Bronchial Asthma (5)Goiter ";}                                                             
                                                 ?>
						</div>
					</li>
                                        <li>
						<label for="age">Risk - Date Detected</label>
						<div class="fields">
						  <?php echo format_date($prenatal->risk_date_detected); ?>
						</div>
					</li>                                     					
					<li>
						<label for="age">Pregnancy - Date Terminated</label>
						<div class="fields">
						  <?php echo format_date($prenatal->pregnancy_date_terminated); ?>
						</div>
					</li>
                                        <li>
						<label for="age">Pregnancy - Outcome</label>
						<div class="fields">
						<?php
                                                        if($prenatal->pregnancy_outcome=="0") 
                                                                {echo "LB - Livebirth";}
                                                        elseif($prenatal->pregnancy_outcome=="1")
                                                                {echo "SB - Stillbirth";}  
                                                        elseif($prenatal->pregnancy_outcome=="2")
                                                                {echo "AB - Abortion";}                                                             
                                                ?>
						</div>
					</li>
                                        <li class="even">
						<label for="gender">Livebirths - Birth Weight</label>
						<div class="fields">
						<?php echo $prenatal->livebirths_birth_weight; ?>
						</div>
					</li>
					<li>
						<label for="dob_day">Livebirths - Place of Delivery</label>
                                                <div class="fields">         	
                                                        <?php echo $prenatal->livebirths_place_delivery; ?>        
                                                </div>                                                        
					</li>
					
					<li class="even">
						<label for="relation">Livebirths - Attended by </label>
						<div class="fields">
                                                        <?php
                                                                if($prenatal->livebirths_attended_by=="0") 
                                                                        {echo "A - Doctor";}
                                                                elseif($prenatal->livebirths_attended_by=="1")
                                                                        {echo "B - Nurse";}  
                                                                elseif($prenatal->livebirths_attended_by=="2")
                                                                        {echo "C - Midwife";}
                                                                elseif($prenatal->livebirths_attended_by=="3")
                                                                        {echo "D - Hilot/TBA";}
                                                                elseif($prenatal->livebirths_attended_by=="4")
                                                                        {echo "E - Others";}                                                             
                                                        ?>
						</div>
					</li>
          		
    			                 <li>
						<label for="facility">Remarks </label>
						<div class="fields">
						  <?php echo $prenatal->remarks; ?>
						</div>
					</li>
					

    			
				</ul>
			</fieldset>
		</div>

		<div class="form_inputs" id="client-prenatal-tab">
			<fieldset>
				<ul>
                                        <li>
                                                <label for="history">First Trimester</label>
                                                <?php echo $prenatal->prenatal_visit1 ? format_date($prenatal->prenatal_visit1) : '-'; ?>
                                        </li>
                                        <li>
                                                <label for="history">Second Trimester</label>
                                                <?php echo $prenatal->prenatal_visit2 ? format_date($prenatal->prenatal_visit2) : '-'; ?>
                                        </li>
                                        <li>
                                                <label for="history">Third Trimester</label>
                                                <?php echo $prenatal->prenatal_visit3 ? format_date($prenatal->prenatal_visit3) : '-'; ?>
                                        </li>
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="client-tetanus-tab">
			<fieldset>
				<ul>
                                        <li>
                                                <label for="history">TT1</label>
                                                <?php echo $prenatal->tt1 ? format_date($prenatal->tt1) : '-'; ?>
                                        </li>
                                        <li>
                                                <label for="history">TT2</label>
                                                <?php echo $prenatal->tt2 ? format_date($prenatal->tt2) : '-'; ?>
                                        </li>
                                        <li>
                                                <label for="history">TT3</label>
                                                <?php echo $prenatal->tt3 ? format_date($prenatal->tt3) : '-'; ?>
                                        </li>
                                        <li>
                                                <label for="history">TT4</label>
                                                <?php echo $prenatal->tt4 ? format_date($prenatal->tt4) : '-'; ?>
                                        </li>
                                        <li>
                                                <label for="history">TT5</label>
                                                <?php echo $prenatal->tt5 ? format_date($prenatal->tt5) : '-'; ?>
            			        </li>
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="client-micronutrient-tab">
			<fieldset>
				<ul>
					<li class="even">
						<label for="vitamin-a">Date Give Vitamin A</label>
						<div class="fields">
                                                        <?php echo format_date($prenatal->date_given_vit_a); ?>
						</div>
					</li>
					<li>
                                                <label for="iron-folic-acid">Date and No. of Iron with Folic Acid was given</label>
                                        </li>
                                        <div>
                                        
                                        <li>
                                                <label for="-iron-date-header">Date Given</label>
                                                <label for="-iron-date-header">Number Given</label>
                                        </li>
                                                                         
                                                <li>                                                
                                                        <span style="width:1%; float:left; padding:2px;"><b>1.</b></span>
                                                        <span style="width:26%; float:left; padding:2px; margin:0 3% 0 0;"><?php echo $prenatal->iron1_date ? format_date($prenatal->iron1_date) : '-'; ?></span>
                                                        <span style="width:27%; float:left; padding:2px; margin:0 3% 0 0;"><?php echo $prenatal->iron1_number ? $prenatal->iron1_number : '-'; ?></span>          				                                                                                                                                                   
                    			        </li>
                    			        <li>                                                
                                                        <span style="width:1%; float:left; padding:2px;"><b>2.</b></span>
                                                        <span style="width:26%; float:left; padding:2px; margin:0 3% 0 0;"><?php echo $prenatal->iron2_date ? format_date($prenatal->iron2_date) : '-'; ?></span>
                                                        <span style="width:27%; float:left; padding:2px; margin:0 3% 0 0;"><?php echo $prenatal->iron2_number ? $prenatal->iron2_number : '-'; ?></span>          				                                                                                                                                                   
                    			        </li>
                    			        <li>                                                
                                                        <span style="width:1%; float:left; padding:2px;"><b>3.</b></span>
                                                        <span style="width:26%; float:left; padding:2px; margin:0 3% 0 0;"><?php echo $prenatal->iron3_date ? format_date($prenatal->iron3_date) : '-'; ?></span>
                                                        <span style="width:27%; float:left; padding:2px; margin:0 3% 0 0;"><?php echo $prenatal->iron3_number ? $prenatal->iron3_number : '-'; ?></span>          				                                                                                                                                                   
                    			        </li>
                    			        <li>                                                
                                                        <span style="width:1%; float:left; padding:2px;"><b>4.</b></span>
                                                        <span style="width:26%; float:left; padding:2px; margin:0 3% 0 0;"><?php echo $prenatal->iron4_date ? format_date($prenatal->iron4_date) : '-'; ?></span>
                                                        <span style="width:27%; float:left; padding:2px; margin:0 3% 0 0;"><?php echo $prenatal->iron4_number ? $prenatal->iron4_number : '-'; ?></span>          				                                                                                                                                                   
                    			        </li>
                    			        <li>                                                
                                                        <span style="width:1%; float:left; padding:2px;"><b>5.</b></span>
                                                        <span style="width:26%; float:left; padding:2px; margin:0 3% 0 0;"><?php echo $prenatal->iron5_date ? format_date($prenatal->iron5_date) : '-'; ?></span>
                                                        <span style="width:27%; float:left; padding:2px; margin:0 3% 0 0;"><?php echo $prenatal->iron5_number ? $prenatal->iron5_number : '-'; ?></span>          				                                                                                                                                                   
                    			        </li>
                    			        <li>                                                
                                                        <span style="width:1%; float:left; padding:2px;"><b>6.</b></span>
                                                        <span style="width:26%; float:left; padding:2px; margin:0 3% 0 0;"><?php echo $prenatal->iron6_date ? format_date($prenatal->iron6_date) : ''; ?></span>
                                                        <span style="width:27%; float:left; padding:2px; margin:0 3% 0 0;"><?php echo $prenatal->iron6_number ? $prenatal->iron6_number : '-';?></span>          				                                                                                                                                                   
                    			        </li>
                                        </div>
                                        				
				</ul>
			</fieldset>
		</div>
		
		
	</div>

	<div class="buttons float-right padding-top">
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