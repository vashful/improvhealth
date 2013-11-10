<section class="title">
		<h4><?php echo sprintf('Edit Client for FP "%s, %s"', $fp->last_name, $fp->first_name);?></h4>
		<?php echo form_open(uri_string(), 'class="crud"'); ?>
</section>

<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">
		  <li><a href="#client-visits-tab"><span>Follow-up Visits</span></a></li>
			<li><a href="#client-details-tab"><span>Client Information</span></a></li>
		</ul>

		<!-- Content tab -->
		
		<div class="form_inputs" id="client-visits-tab">

	
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
                <td class="collapse"><?php echo date("F j, Y",$fp_visit->service_date); ?></td>
							<td class="collapse"><?php echo $fp_visit->accomplished_date ? date("F j, Y",$fp_visit->accomplished_date) : 'Today(if Save)'; ?></td>
							</tr>
							<?php $count_visits++; ?>
					<?php endforeach; ?>     
				</tbody>
			</table>
	<?php endif; ?>
	<?php if ($fp->completed): ?>
	   <p>This Client has already completed his visits.</p>     
	<?php elseif ($fp->drop_out_reason): ?>
	   <p>This Client has been Drop-out for being "<?php echo $drop_out_reasons[$fp->drop_out_reason];?>" since <?php echo date("F j, Y",$fp->drop_out_date 	); ?>.</p>
  <?php else:?>
				<ul>
					<li>
						<label for="service_day">Visit Completed</label>
						<div class="fields">
  					<div>
  						<?php echo form_checkbox('complete', '1', FALSE, 'id=complete'); ?>
  					</div>
  				</div>                                                        
					</li>          
          <li class="hide-me">
						<label for="service_day">Next Service  Date(if Any)</label>
						<div class="fields">
  					<div>
              <?php echo form_hidden('fp_visit_id', $fp_visit->id, 'id="fp_visit_id"') ?>
              <?php echo form_input('service_date', date('Y-m-d', $fp->service_date), 'maxlength="10" id="datepicker" class="text width-20"'); ?>
  					</div>
  				</div>                                                        
					</li>
					
        	<li class="even hide-me">
						<label for="drop_out_reason">Reason if Drop-out</label>
						<div class="input">
						<?php echo form_dropdown('drop_out_reason', $drop_out_reasons, $fp->drop_out_reason, '', 'id="drop_out_reason"'); ?>
						</div>
					</li>

          <li>
    				<label for="remarks">Remarks</label>
    				<div class="input">
						<?php echo form_input('remarks', $fp->remarks, 'id="remarks"'); ?>
						</div>
    			</li>	
				</ul>
		<?php endif; ?>
			</fieldset>
		</div>
		
		
		<div class="form_inputs" id="client-details-tab">
			<fieldset>
				<ul>
				   <li>
						<label for="method_id">Registration Date</label>
						<div class="input">
						<?php echo format_date($fp->registration_date); ?>
						</div>
					</li>
					<li class="even">
						<label for="method_id">Form Number</label>
						<div class="input">
						<?php echo $fp->form_number; ?>
						</div>
					</li>
					<li>
						<label for="method_id">Serial Number</label>
						<div class="input">
						<?php echo $fp->serial_number ; ?>
						</div>
					</li>
					<li class="even">
						<label for="method_id">Full Name</label>
						<div class="input">
					<?php echo $fp->last_name; ?>, <?php echo $fp->first_name; ?> <?php echo substr($fp->middle_name,0,1); ?>
					 </div>
					</li>
					
					<li>
						<label for="method_id">Family Planning Method <span>*</span></label>
						<div class="input">
						<?php echo $fp->method_id ? $methods[$fp->method_id]: ''; ?>
						</div>
					</li>
					
				   <li class="even">
						<label for="method_id">Family Planning Method <span>*</span></label>
						<div class="input">
						<?php echo $fp->method_id ? $methods[$fp->method_id]: ''; ?>
						<?php echo form_hidden('client_id', $fp->client_id, 'id="client_id"') ?>
						<?php echo form_hidden('fp_id', $fp->fp_id, 'id="fp_id"') ?>
						<?php echo form_hidden('client_type', $fp->client_type, 'id="client_type"') ?>
						<?php echo form_hidden('method_id', $fp->method_id, 'id="method_id"') ?>
						</div>
					</li>
					
				  <li>
						<label for="client_type">Type of Client <span>*</span></label>
						<div class="input">
						<?php echo $fp->client_type ? $client_types[$fp->client_type]: ''; ?>
						</div>
					</li>
					
					<li class="even">
						<label for="previous_method_id">Previous Method</label>
						<div class="input">
						<?php echo $fp->previous_method_id ? $methods[$fp->previous_method_id]: ''; ?>
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