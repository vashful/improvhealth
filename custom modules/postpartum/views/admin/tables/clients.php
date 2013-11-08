  <?php if (!empty($clients)): ?>

			<table border="0" class="table-list">
				<thead>
					<tr>
						<th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
						<th>Lastname</th>
						<th class="collapse">Firstname</th>
						<th>Middle Name</th>
						<th class="collapse">Gender</th>
						<th class="collapse">Age</th>
						<th class="collapse">Registration Date</th>
						<th>Form Number</th>
						<th width="200"></th>
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
							<td class="align-center"><?php echo form_checkbox('action_to[]', $client->id); ?></td>
							
							<td>
								<?php echo anchor('admin/clients/view/' . $client->id, $client->last_name); ?>
							</td>
							<td class="collapse"><?php echo $client->first_name; ?></td>
							<td><?php echo $client->middle_name; ?></td>
							<td class="collapse"><?php echo $client->gender; ?></td>
							<td class="collapse"><?php echo $client->age; ?></td>
							<td class="collapse"><?php echo format_date($client->registration_date); ?></td>
							<td>
								<?php echo $client->form_number; ?>
							</td>
							<td class="actions">
								<?php echo anchor('admin/clients/edit/' . $client->id, lang('global:edit'), array('class'=>'button edit')); ?>
								<?php echo anchor('admin/clients/delete/' . $client->id, lang('global:delete'), array('class'=>'confirm button delete')); ?>
							</td>
							</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
	<?php endif; ?>