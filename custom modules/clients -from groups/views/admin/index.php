<section class="title">
	<h4><?php echo $module_details['name']; ?></h4>
</section>

<section class="item">
	<?php if ($clients): ?>
		<table class="table-list">
			<thead>
				<tr>
					<th width="40%"><?php echo lang('clients.name');?></th>
					<th><?php echo lang('clients.short_name');?></th>
					<th width="300"></th>
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
			<?php foreach ($clients as $client):?>
				<tr>
					<td><?php echo $client->description; ?></td>
					<td><?php echo $client->name; ?></td>
					<td class="actions">
					<?php echo anchor('admin/clients/edit/'.$client->id, lang('buttons.edit'), 'class="button edit"'); ?>
					<?php if ( ! in_array($client->name, array('user', 'admin'))): ?>
						<?php echo anchor('admin/clients/delete/'.$client->id, lang('buttons.delete'), 'class="confirm button delete"'); ?>
					<?php endif; ?>
					<?php echo anchor('admin/permissions/client/'.$client->id, lang('permissions.edit').' &rarr;', 'class="button edit"'); ?>
					</td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	
	<?php else: ?>
		<section class="title">
			<p><?php echo lang('clients.no_clients');?></p>
		</section>
	<?php endif;?>
</section>