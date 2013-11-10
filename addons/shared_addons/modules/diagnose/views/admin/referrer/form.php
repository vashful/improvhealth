<section class="title">
	<?php if ($this->controller == 'admin_referrer' && $this->method === 'edit'): ?>
	<h4><?php echo sprintf(lang('referrer_edit_title'), $referrer->lastname.', '.$referrer->firstname.' '.$referrer->middlename);?></h4>
	<?php else: ?>
	<h4><?php echo lang('referrer_create_title');?></h4>
	<?php endif; ?>
</section>

<section class="item">
<?php echo form_open($this->uri->uri_string(), 'class="crud" id="referrer"'); ?>

<div class="form_inputs">

	<ul>
		<li class="even">
		<label for="title">First Name <span>*</span></label>
		<div class="input"><?php echo  form_input('firstname', $referrer->firstname); ?></div>
		</li>
		<li class="even">
		<label for="title">Last Name <span>*</span></label>
		<div class="input"><?php echo  form_input('lastname', $referrer->lastname); ?></div>
		</li>                                                        
		<li class="even">
		<label for="title">Middle Name<span>*</span></label>
		<div class="input"><?php echo  form_input('middlename', $referrer->middlename); ?></div>
		</li>
		<li class="even">
		<label for="title">Profession</label>
		<div class="input"><?php echo  form_input('profession', $referrer->profession); ?></div>
		</li>
	</ul>
	
</div>

	<div><?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?></div>

<?php echo form_close(); ?>
</section>