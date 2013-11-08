<section class="title">
	<h4><?php echo lang('region_list_title'); ?></h4>
</section>

<section class="item">
	
	<?php if ($regions): ?>

		<?php echo form_open('admin/clients/region/delete'); ?>

		<table border="0" class="table-list">
			<thead>
			<tr>
				<th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
				<th><?php echo lang('region_category_label'); ?></th>
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
				<?php foreach ($regions as $region): ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $region->id); ?></td>
					<td><?php echo $region->name; ?></td>
					<td class="align-center buttons buttons-small">
						<?php echo anchor('admin/clients/region/edit/' . $region->id, lang('global:edit'), 'class="button edit"'); ?>
						<?php echo anchor('admin/clients/region/delete/' . $region->id, lang('global:delete'), 'class="confirm button delete"') ;?>
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
		<div class="no_data"><?php echo lang('region_no_regions'); ?></div>
	<?php endif; ?>
</section>