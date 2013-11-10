<section class="title">
	<?php if ($this->controller == 'admin_region' && $this->method === 'edit'): ?>
	<h4><?php echo sprintf(lang('region_edit_title'), $region->name);?></h4>
	<?php else: ?>
	<h4><?php echo lang('region_create_title');?></h4>
	<?php endif; ?>
</section>

<section class="item">
<?php echo form_open($this->uri->uri_string(), 'class="crud" id="region"'); ?>

<div class="form_inputs">

	<ul>
		<li class="even">
		<label for="title">Region Name <span>*</span></label>
		<div class="input"><?php echo  form_input('name', $region->name); ?></div>
		</li>
	</ul>
	
</div>

	<div><?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?></div>

<?php echo form_close(); ?>
</section>