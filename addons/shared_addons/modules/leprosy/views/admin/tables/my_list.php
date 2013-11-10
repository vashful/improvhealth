  <?php if (!empty($clients)): ?>

			<table border="0" class="table-list">
				<thead>
					<tr>
						<th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
						<th>Date Recorded</th>
						<th class="collapse">Gender</th>
						<th class="collapse">Age</th>
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
							<td class="align-center"><?php echo form_checkbox('action_to[]', $client->leprosy_id); ?></td>
							<td class="collapse"><?php echo date("F j, Y",$client->date_added); ?></td>
							<td class="collapse"><?php echo $client->gender; ?></td>
																												<?php $age = floor((time() - $client->dob)/31556926); ?>
							<td class="collapse"><?php echo $age ? $age : ''; ?></td>
              <td class="collapse"><?php echo $client->remarks; ?>&nbsp;</td>
							<td class="actions" style="text-align: center">
								<?php echo anchor('admin/leprosy/edit/' . $client->leprosy_id, lang('global:edit'), array('class'=>'button edit')); ?>
								<?php echo anchor('admin/leprosy/delete/' . $client->leprosy_id, lang('global:delete'), array('class'=>'confirm button delete')); ?>
							</td>
							</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
	<?php endif; ?>