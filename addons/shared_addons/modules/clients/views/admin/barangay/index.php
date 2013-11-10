<section class="title">
	<h4><?php echo lang('barangay_list_title'); ?></h4>
</section>

<section class="item">
	
	<?php if ($barangays): ?>

		<?php echo form_open('admin/clients/barangay/delete'); ?>

		<table border="0" class="table-list">
			<thead>
			<tr>
				<th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
				<th><?php echo lang('barangay_category_label'); ?></th>
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
				<?php foreach ($barangays as $barangay): ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $barangay->id); ?></td>
					<td><?php echo $barangay->name; ?></td>
					<td class="align-center buttons buttons-small">
						<?php echo anchor('admin/clients/barangay/edit/' . $barangay->id, lang('global:edit'), 'class="button edit"'); ?>
						<?php echo anchor('admin/clients/barangay/delete/' . $barangay->id, lang('global:delete'), 'class="confirm button delete"') ;?>
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
		<div class="no_data"><?php echo lang('barangay_no_barangays'); ?></div>
	<?php endif; ?>
</section>