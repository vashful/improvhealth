<section class="title">
	<?php if ($this->method == 'add'): ?>
		<h4>Add <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> for Consultations</h4>
		<?php echo form_open(uri_string(), 'class="crud" autocomplete="off"'); ?>
	
	<?php else: ?>
		<h4>Edit <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em></h4>
		<?php echo form_open(uri_string(), 'class="crud"'); ?>
	<?php endif; ?>
</section>

<section class="item">
	
	<div class="tabs">
    <ul class="tab-menu">
			<li><a href="#consultations-details-tab"><span>Visit's Information</span></a></li>
		</ul>
		<!-- Content tab -->
		<div class="form_inputs" id="consultations-details-tab">
			<fieldset>
				<ul>
					<li class="even">
						<label for="service_day">Date of Consultation <span>*</span></label>
						<div class="fields">
  					<div>
              <?php echo form_input('date_consultations', $consultations->date_consultations ? $consultations->date_consultations: '', 'maxlength="10" id="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
              <?php echo form_hidden('client_id', $client->id, 'id="client_id"') ?>
  					</div>
  				</div>                                                        
					</li>
          <li>
    				<label for="history">Chief Complaint <span>*</span></label>
    				<div class="input">
    				<?php echo form_textarea(array('id' => 'cc', 'name' => 'cc', 'value' => $consultations->cc, 'rows' => 3)); ?>
    				</div>
    			</li>
          <li class="even">
						<label for="first_name">Weight <span>*</span></label>
						<div class="input">
							<?php echo form_input('wt', $consultations->wt, 'id="wt" class="small-input"'); ?> kg
						</div>            
					</li>
          <li class="even">
						<label for="first_name">Height <span>*</span></label>
						<div class="input">
							<?php echo form_input('ht', $consultations->ht, 'id="ht" class="small-input"'); ?> cm
						</div>            
					</li>
          <li class="even">
						<label for="first_name">Blood Pressure <span>*</span></label>
						<div class="input">
							<?php echo form_input('bp', $consultations->bp, 'id="bp" class="medium-input"'); ?> mmHg
						</div>            
					</li>
          <li class="even">
						<label for="first_name">Temperature <span>*</span></label>
						<div class="input">
							<?php echo form_input('temp', $consultations->temp, 'id="temp" class="small-input"'); ?> &#176;C
						</div>            
					</li>	
          
          <li class="even">
						<label for="first_name">Pulse Rate <span>*</span></label>
						<div class="input">
							<?php echo form_input('pr', $consultations->pr, 'id="pr" class="small-input"'); ?> bpm
						</div>            
					</li>	
					
					<li class="even">
						<label for="first_name">Respiratory Rate <span>*</span></label>
						<div class="input">
							<?php echo form_input('rr', $consultations->rr, 'id="rr" class="small-input"'); ?> cpm
						</div>            
					</li>	
          <li>
    				<label for="history">Objective Description(optional)</label>
    				<div class="input">
    				<?php echo form_textarea(array('id' => 'objective', 'name' => 'objective', 'value' => $consultations->objective, 'rows' => 1)); ?>
    				</div>
    			</li>	
	                  <li class="even">
						<label for="serial_number">Assessment <span>*</span></label>
						<div class="input">
							<?php echo form_dropdown('assessment_ids[]', $list_diseases, $consultations->assessment_ids, 'multiple id="assessment_id" class="chzn" style="width:250px"'); ?>
							<div class="auto_add">[ <?php echo anchor('admin/consultations/diseases/create', 'Add Disease/Assessment', 'target="_blank" class="cboxElement"'); ?> ]</div>
						</div>
					</li>
					<li class="even">
						<label for="serial_number">Seen by <span>*</span></label>
						<div class="input">
						  <?php if(isset($referrers)): ?>
						  <?php $referrers = array(NULL => '-- Please Select --') + $referrers; ?>
						  <?php else:?>
						  <?php $referrers = array(NULL => '-- Please Select --'); ?>
						  <?php endif;?>
							<?php echo form_dropdown('referrer_id', $referrers, $consultations->referrer_id , 'id="referrer_id" class="chzn" style="width:250px;"'); ?>
              <!--<div class="auto_add">[ <?php echo anchor('admin/consultations/referrer/create', 'Add Personnel/Staff', 'target="_blank" class="cboxElement"'); ?> ]</div>-->
						</div>       
					</li>				
				  <li>
    				<label for="history">Plan of Intervention <span>*</span></label>
    				<div class="input">
    				<?php echo form_textarea(array('id' => 'plan', 'name' => 'plan', 'value' => $consultations->plan, 'rows' => 3)); ?>
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