<section class="title">
	<h4><?php echo lang('referrer_list_title'); ?></h4>
</section>

<section class="item">
	
	<?php if ($referrer): ?>

		<?php echo form_open('admin/consultations/referrer/delete'); ?>

		<table border="0" class="table-list">
			<thead>
			<tr>
				<th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
				<th><?php echo lang('referrer_category_label'); ?></th>
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
				<?php foreach ($referrer as $referrer): ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $referrer->id); ?></td>
					<td><?php echo $referrer->lastname.', '.$referrer->firstname.' '.$referrer->middlename; ?></td>
					<td><?php echo $referrer->profession; ?></td>
					<td><?php echo date("F j, Y",$referrer->date_added); ?></td>
					<td class="align-center buttons buttons-small">
						<?php echo anchor('admin/consultations/referrer/edit/' . $referrer->id, lang('global:edit'), 'class="button edit"'); ?>
					<!--	<?php echo anchor('admin/consultations/referrer/delete/' . $referrer->id, lang('global:delete'), 'class="confirm button delete"') ;?>    -->
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
		<div class="no_data"><?php echo lang('referrer_no_referrer'); ?></div>
	<?php endif; ?>
</section>