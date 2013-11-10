<section class="title">
	<?php if ($this->method == 'add'): ?>
		<h4>Add <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> for Diagnosis</h4>
		<?php echo form_open(uri_string(), 'class="crud" autocomplete="off"'); ?>
	
	<?php else: ?>
		<h4>Edit <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em></h4>
		<?php echo form_open(uri_string(), 'class="crud"'); ?>
	<?php endif; ?>
</section>

<section class="item">
	
	<div class="tabs">
    <ul class="tab-menu">
			<li><a href="#diagnose-details-tab"><span>Diagnosis Information</span></a></li>
		</ul>
		<!-- Content tab -->
		<div class="form_inputs" id="diagnose-details-tab">
			<fieldset>
				<ul>
					<li class="even">
						<label for="serial_number">Diagnosis <span>*</span></label>
						<div class="input">
							<?php echo form_dropdown('diagnosis_id', array(NULL => '-- Please Select --') + $list_diagnosis, $diagnose->diagnosis_id, 'id="diagnosis_id" class="chzn"'); ?>
							<div class="auto_add">[ <?php echo anchor('admin/clients/diagnosis/create', 'Add Diagnosis', 'target="_blank" class="cboxElement"'); ?> ]</div>
							<?php echo form_hidden('client_id', $diagnose->client_id, 'id="client_id"') ?>
						</div>
					</li>
					
          <li class="even">
						<label for="service_day">Date Diagnosed <span>*</span></label>
						<div class="fields">
  					<div>
              <?php echo form_input('date_diagnose', $diagnose->date_diagnose ? $diagnose->date_diagnose: '', 'maxlength="10" id="datepicker" class="text width-20"'); ?>
              (yyyy-mm-dd)
  					</div>
  				</div>                                                        
					</li>
					
					<li class="even">
						<label for="serial_number">Reffered by <span>*</span></label>
						<div class="input">
						  <?php if(isset($referrers)): ?>
						  <?php $referrers = array(NULL => '-- Please Select --') + $referrers; ?>
						  <?php else:?>
						  <?php $referrers = array(NULL => '-- Please Select --'); ?>
						  <?php endif;?>
							<?php echo form_dropdown('referrer_id', $referrers, $diagnose->referrer_id , 'id="referrer_id" class="chzn"'); ?>
						</div>       
					</li>
					
				  <li>
    				<label for="history">Treatment</label>
    				<br style="clear: both;" />
    				<?php echo form_textarea(array('id' => 'therapy', 'name' => 'therapy', 'value' => $diagnose->therapy, 'rows' => 5)); ?>
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