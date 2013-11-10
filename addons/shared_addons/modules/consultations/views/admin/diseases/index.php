<section class="title">
	<h4><?php echo lang('diseases_list_title'); ?></h4>
</section>

<section class="item">
	
	<?php if ($diseases): ?>

		<?php echo form_open('admin/consultations/diseases/delete'); ?>

		<table border="0" class="table-list">
			<thead>
			<tr>
				<th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
				<th><?php echo lang('diseases_category_label'); ?></th>
				<th>Category</th>
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
				<?php foreach ($diseases as $diseases): ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $diseases->id); ?></td>
					<td><?php echo $diseases->name; ?></td>
          <?php if($diseases->category == 1):?>
					<td>Category I</td>
          <?php elseif($diseases->category == 2):?>
          <td>Category II</td>
          <?php elseif($diseases->category == 3):?>
          <td>Non-Disease</td>
          <?php else:?>
          <td>Other Category</td>
          <?php endif; ?>
					<td><?php echo date("F j, Y",$diseases->date_added); ?></td>
					<td class="align-center buttons buttons-small">
						<?php echo anchor('admin/consultations/diseases/edit/' . $diseases->id, lang('global:edit'), 'class="button edit"'); ?>
						<?php echo anchor('admin/consultations/diseases/delete/' . $diseases->id, lang('global:delete'), 'class="confirm button delete"') ;?>
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
		<div class="no_data"><?php echo lang('diseases_no_diseases'); ?></div>
	<?php endif; ?>
</section>