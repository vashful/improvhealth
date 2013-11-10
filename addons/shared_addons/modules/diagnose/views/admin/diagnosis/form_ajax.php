<section class="title">
	<?php if ($this->controller == 'admin_diagnosis' && $this->method === 'edit'): ?>
	<h4><?php echo sprintf(lang('diagnosis_edit_title'), $diagnosis->name);?></h4>
	<?php else: ?>
	<h4><?php echo lang('diagnosis_create_title');?></h4>
	<?php endif; ?>
</section>

<section class="item">
<?php echo form_open($this->uri->uri_string(), 'class="crud" id="diagnosis"'); ?>

<div class="form_inputs">

	<ul>
		<li class="even">
		<label for="title"><?php echo lang('diagnosis_title_label');?> <span>*</span></label>
		<div class="input"><?php echo  form_input('name', $diagnosis->name); ?></div>
		</li>
		<li class="even">
		<label for="title">Description</label>
		<div class="input"><?php echo  form_input('description', $diagnosis->description); ?></div>
		</li>
		<li class="even">
		<label for="gender">Classification</label>
						<div class="input">
						<? $options = array(
                  'disease'  => 'Disease',
                  'other'    => 'Other'
                );?>
						<?php echo form_dropdown('classification', $options, $diagnosis->classification, 'id="classification"'); ?>
						</div>
		</li>
		<li>
    				<label for="history">Symptoms</label>
    				<br style="clear: both;" />
    				<?php echo form_textarea(array('id' => 'symptoms', 'name' => 'symptoms', 'value' => $diagnosis->symptoms, 'rows' => 5)); ?>
    			</li>
    <li>
  	<label for="history">Treatment</label>
  			<br style="clear: both;" />
  			<?php echo form_textarea(array('id' => 'treatment', 'name' => 'treatment', 'value' => $diagnosis->treatment, 'rows' => 5)); ?>
  		</li>
	</ul>
	
</div>

	<div><?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?></div>

<?php echo form_close(); ?>
</section>