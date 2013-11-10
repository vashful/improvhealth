  <?php if (!empty($clients)): ?>

			<table border="0" class="table-list">
				<thead>
					<tr>
						<th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
						<th class="collapse">Date of Delivery</th>
						<th class="collapse">Date of Birth</th>
	          <th class="collapse">Remarks</th>     
						<th width="200" style="text-align: center">Actions</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="8">
							<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach ($clients as $client): ?>
						<tr>
							<td class="align-center"><?php echo form_checkbox('action_to[]', $client->postpartum_id); ?></td>
							<td class="collapse"><?php echo date('F j, Y, g:i a', $client->delivery); ?></td>     
							<td class="collapse"><?php echo date('F j, Y', $client->dob); ?>&nbsp;</td>
              <td class="collapse"><?php echo $client->remarks; ?>&nbsp;</td>
							<td class="actions" style="text-align: center">
								<?php echo anchor('admin/postpartum/edit/' . $client->postpartum_id, lang('global:edit'), array('class'=>'button edit')); ?>
								<?php echo anchor('admin/postpartum/delete/' . $client->postpartum_id, lang('global:delete'), array('class'=>'confirm button delete')); ?>
							</td>
							</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
    <?php else:?>
	<div class="no-results">
        <p>No Record(s) Found.</p>
    </div>
	<?php endif; ?>