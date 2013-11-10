<section class="title">
	<h4>"<a href='admin/clients/view/<?php echo $selected_client->id; ?>' class='title-client-name'><?php echo $selected_client->last_name; ?>, <?php echo $selected_client->first_name; ?> <?php echo $selected_client->middle_name;?></a>" Record(s) Under Sick Children</h4>
</section>

<section class="item">
	
	<?php template_partial('filters'); ?>

	<?php echo form_open('admin/sick_children/action'); ?>
		<div id="filter-stage">
			<?php template_partial('sc-my_list'); ?>
		</div>
	
    <?php if (!empty($clients)): ?>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
		</div>
		<?php else:?>
		No Record(s) Found.
		<?php endif; ?>

	<?php echo form_close(); ?>

</section>
