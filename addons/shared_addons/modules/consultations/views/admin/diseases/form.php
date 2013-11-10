<section class="title">
	<?php if ($this->controller == 'admin_diseases' && $this->method === 'edit'): ?>
	<h4><?php echo sprintf(lang('diseases_edit_title'), $diseases->name);?></h4>
	<?php else: ?>
	<h4><?php echo lang('diseases_create_title');?></h4>
	<?php endif; ?>
</section>

<section class="item">
<?php echo form_open($this->uri->uri_string(), 'class="crud" id="diseases"'); ?>

<div class="form_inputs">

	<ul>
		<li class="even">
		<label for="title"><?php echo lang('diseases_title_label');?> <span>*</span></label>
		<div class="input"><?php echo  form_input('name', $diseases->name); ?></div>
		</li>
    <li class="even">
		<label for="title">Category</label>
		<div class="input">
    				<?php echo form_dropdown('category', array('0' => 'Other Category', '1' => 'Category I(Immediately Notifiable)', '2' => 'Category II(Weekly Notifiable)', '3' => 'Non-Disease' ), $diseases->category, 'class="chzn"'); ?>
    </div>
		</li>
	</ul>
	
</div>

	<div><?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?></div>

<?php echo form_close(); ?>
</section>