<section class="title">
		<h4>Client <em>"<?php echo $client->last_name; ?>, <?php echo $client->first_name; ?>"</em> Information</h4>
		<?php echo form_open(uri_string(), 'class="crud"'); ?>

</section>

<section class="item">
	
	<div class="tabs">
  <?php if($this->current_user->group == 'staff' || $this->current_user->group == 'admin'):?>
  <div class="buttons float-right padding-top">
  <a class="btn red" href="admin/consultations/set/my_list/<?php echo  $client->id; ?>">Consultations</a>
  <a class="btn blue" href="admin/family/set/my_list/<?php echo  $client->id; ?>">Family Planning</a>
  <?php if($client->gender == 'female'):?>
	<a class="btn green" href="admin/prenatals/set/my_list/<?php echo  $client->id; ?>">Prenatal Care</a>
	<a class="btn green" href="admin/postpartum/set/my_list/<?php echo  $client->id; ?>">Postpartum Care</a>
	<?php endif;?>
	<a class="btn orange" href="admin/children_under_one/set/my_list/<?php echo  $client->id; ?>">Children Under 1 Year Old</a>
	<a class="btn orange" href="admin/sick_children/set/my_list/<?php echo  $client->id; ?>">Sick Children</a>
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('cancel') )); ?>
	</div>
	<?php elseif($this->current_user->group == 'dental'):?>
	  <div class="buttons float-right padding-top">
	<a class="btn red" href="admin/consultations/set/my_list/<?php echo  $client->id; ?>">Consultations</a>	
	<a class="btn blue" href="admin/dental_health/set/my_list/<?php echo  $client->id; ?>">Dental Health</a>
	
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('cancel') )); ?>
	</div>
	<?php elseif($this->current_user->group == 'sanitary'):?>
	<div class="buttons float-right padding-top">
	<a class="btn blue" href="admin/environmental_health/set/my_list/<?php echo  $client->id; ?>">Environmental Health</a>
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('cancel') )); ?>
	</div>
	<?php endif; ?>
		

		<!-- Content tab -->
		<div class="form_inputs forms_view client-details-tab-view" id="client-details-tab">
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
        <?php if(!empty($client->bloodtype)): ?> <p>Blood Type: <span class="r-info"><?php echo $client->bloodtype; ?></span></p><?php endif; ?>
        <?php if(!empty($client->philhealth)): ?> <p>Philhealth: <span class="r-info"><?php echo $client->philhealth; ?></span></p><?php endif; ?>
        <?php if(!empty($client->philhealth_type)): ?> <p>Philhealth Membership Type: <span class="r-info"><?php echo $client->	philhealth_type; ?></span></p><?php endif; ?> 
        <?php if(!empty($client->philhealth_sponsor)): ?> <p>Philhealth Sponsor: <span class="r-info"><?php echo $client->philhealth_sponsor; ?></span></p><?php endif; ?>   
   </div>
	 
		</fieldset>	
		</div>

	
    		  <ul class="client-history">
            <?php if(!empty($client->history)): ?><li>Consultation History: <?php echo $client->history; ?></li><?php endif; ?> 
            <?php foreach ($consultations as $consultation): ?>
            <li>On <?php echo date('F j, Y', $consultation->date_consultations); ?> Client was Seen with <span class="hist-blue"><?php echo $consultation->diseases; ?></span> and given treatment of <span class="hist-blue"><?php echo $consultation->plan; ?>.</span> Seen by <span class="hist-ref"><?php echo $consultation->lastname.', '.$consultation->firstname.' '.$consultation->middlename; ?></span></li>
            <?php endforeach; ?>
       
    		  </ul>
	
		
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