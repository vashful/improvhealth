<section class="title">
	<?php if ($this->controller == 'admin_province' && $this->method === 'edit'): ?>
	<h4><?php echo sprintf(lang('province_edit_title'), $province->name);?></h4>
	<?php else: ?>
	<h4><?php echo lang('province_create_title');?></h4>
	<?php endif; ?>
</section>

<section class="item">
<?php echo form_open($this->uri->uri_string(), 'class="crud" id="province"'); ?>

<div class="form_inputs">

	<ul>
		<li class="even">
		<label for="title"><?php echo lang('province_title_label');?> <span>*</span></label>
		<div class="input"><?php echo  form_input('name', $province->name); ?></div>
		</li>
	</ul>
	
</div>

	<div><?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?></div>

<?php echo form_close(); ?>
</section>