<section class="title">
	<h4><?php echo lang('diagnosis_list_title'); ?></h4>
</section>

<section class="item">
	
	<?php if ($diagnosis): ?>

		<?php echo form_open('admin/diagnose/diagnosis/delete'); ?>

		<table border="0" class="table-list">
			<thead>
			<tr>
				<th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
				<th><?php echo lang('diagnosis_category_label'); ?></th>
				<th>Description</th>
				<th>Date Added</th>
				<th width="110"></th>
			</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3">
						<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($diagnosis as $diagnosis): ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $diagnosis->id); ?></td>
					<td><?php echo $diagnosis->name; ?></td>
					<td><?php echo $diagnosis->description; ?></td>
					<td><?php echo date("F j, Y",$diagnosis->date_added); ?></td>
					<td class="align-center buttons buttons-small">
						<?php echo anchor('admin/diagnose/diagnosis/edit/' . $diagnosis->id, lang('global:edit'), 'class="button edit"'); ?>
						<?php echo anchor('admin/diagnose/diagnosis/delete/' . $diagnosis->id, lang('global:delete'), 'class="confirm button delete"') ;?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div class="table_action_buttons">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
		</div>

		<?php echo form_close(); ?>

	<?php else: ?>
		<div class="no_data"><?php echo lang('diagnosis_no_diagnosis'); ?></div>
	<?php endif; ?>
</section>