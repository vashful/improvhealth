<section class="title">
		<h4><?php echo sprintf('Edit "%s, %s"', $fp->last_name, $fp->first_name);?> in Family Planning</h4>
		<?php echo form_open(uri_string(), 'class="crud"'); ?>
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
		  <li><a href="#client-visits-tab"><span>Follow-up Visits</span></a></li>
		  <li><a href="#client-fp-tab"><span>Family Planning Method</span></a></li>
			<li><a href="#client-details-tab"><span>Client Information</span></a></li>
		</ul>

		<!-- Content tab -->
		<div class="form_inputs fp-edit-visits" id="client-visits-tab">
			<fieldset>
				 <?php if (!empty($fp_visits)): ?>
			<table border="0" class="table-list">
				<thead>
					<tr>
					  <th>Visit Number</th>
						<th>Next Service Date</th>
						<th class="collapse">Date Accomplished</th>
					</tr>
				</thead>
				<tbody>
				<?php $count_visits = 1; ?>
					<?php foreach ($fp_visits as $fp_visit): ?>
						<tr>
								<td class="collapse"><?php echo $count_visits; ?></td>
                <td class="collapse"><?php echo form_input("service_date_".$count_visits, $fp_visit->service_date ? date("Y-m-d",$fp_visit->service_date) : '', 'maxlength="10" class="text width-20 datepicker"'); ?></td>
							<td class="collapse"><?php echo form_input("accomplished_date_".$count_visits,  $fp_visit->accomplished_date ? date("Y-m-d",$fp_visit->accomplished_date) : '', 'maxlength="10" class="text width-20 datepicker"'); ?><?php echo form_hidden('fp_visits_id_'.$count_visits, $fp_visit->id) ?></td>
							</tr>
							<?php $count_visits++; ?>
					<?php endforeach; ?>    
				</tbody>
			</table>
	<?php endif; ?>
				<ul>     				
        	<li class="even hide-me">
						<label for="drop_out_reason">Reason if Drop-out</label>
						<div class="input">
						<?php echo form_dropdown('drop_out_reason', $drop_out_reasons, $fp->drop_out_reason, 'id="drop_out_reason" class="chzn"'); ?>
						</div>
					</li>
					<li class="even hide-me">
						<label for="drop_out_reason">Date Drop-out</label>
						<div class="input">
						<?php echo form_input("drop_out_date", $fp->drop_out_date ? date("Y-m-d",$fp->drop_out_date) : '', 'maxlength="10" class="text width-20 datepicker"'); ?>
						</div>
					</li>

          <li>
    				<label for="remarks">Remarks/Action Taken</label>
    				<div class="input">
						<?php echo form_input('remarks', $fp->remarks, 'id="remarks"'); ?>
						</div>
    			</li>	
				</ul>
			</fieldset>
		</div>
		
		<div class="form_inputs" id="client-fp-tab">
			<fieldset>
				<ul>
				   <li>
						<label for="method_id">Family Planning Method <span>*</span></label>
						<div class="input">
						<?php echo form_dropdown('method_id', array(NULL => '-- Please Select --') + $methods, $fp->method_id, 'id="method_id" class="chzn"'); ?>
						<?php echo form_hidden('client_id', $fp->client_id, 'id="client_id"') ?>
						</div>
					</li>
					
				  <li class="even">
						<label for="client_type">Type of Client <span>*</span></label>
						<div class="input">
						<?php echo form_dropdown('client_type', array('' => '-- Please Select --') + $client_types, $fp->client_type, 'id="client_type" class="chzn"'); ?>
						</div>
					</li>
					
					<li>
						<label for="previous_method_id">Previous Method</label>
						<div class="input">
						<?php echo form_dropdown('previous_method_id', array(0 =>'-- Please Select --') + $methods, $fp->previous_method_id, 'id="previous_method_id" class="chzn"'); ?>
						</div>
					</li>	
  				
				</ul>
			</fieldset>
		</div>
		
		
		<div class="form_inputs forms_view" id="client-details-tab">
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