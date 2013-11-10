<section class="title">
	<h4><?php echo lang('province_list_title'); ?></h4>
</section>

<section class="item">
	
	<?php if ($provinces): ?>

		<?php echo form_open('admin/clients/province/delete'); ?>

		<table border="0" class="table-list">
			<thead>
			<tr>
				<th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
				<th><?php echo lang('province_category_label'); ?></th>
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
				<?php foreach ($provinces as $province): ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $province->id); ?></td>
					<td><?php echo $province->name; ?></td>
					<td class="align-center buttons buttons-small">
						<?php echo anchor('admin/clients/province/edit/' . $province->id, lang('global:edit'), 'class="button edit"'); ?>
						<?php echo anchor('admin/clients/province/delete/' . $province->id, lang('global:delete'), 'class="confirm button delete"') ;?>
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
		<div class="no_data"><?php echo lang('province_no_provinces'); ?></div>
	<?php endif; ?>
</section>