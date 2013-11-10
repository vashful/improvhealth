  <?php if (!empty($clients)): ?>
            <div class="number-results">
                    <p>Showing <?php echo number_format($item_start); ?> - <?php echo number_format($item_end); ?> clients (<?php echo number_format($totalitems); ?> total.)</p>
            </div>
			<table border="0" class="table-list">
				<thead>
                    <tr>
						<td colspan="8">
							<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
						</td>
					</tr>
					<tr>
						<th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
						<th>Last Name</th>
						<th class="collapse">First Name</th>
						<th>Middle Name</th>
						<th class="collapse">Gender</th>
						<th class="collapse">Age</th>
						<th class="collapse">Registration Date</th>
						<th>Family Serial Number</th>
						<th width="200" style="text-align: center">Action</th>
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
							<td class="collapse"><?php echo ucfirst($client->gender); ?></td>
							<td class="collapse"><?php $age = floor((time() - $client->dob)/31556926); ?>
						  <?php echo $age ? $age : ''; ?></td>
							<td class="collapse"><?php echo $client->registration_date ? date('M j, Y', $client->registration_date) : ''; ?></td>
							<td>
								<?php echo $client->serial_number; ?>
							</td>
							<td class="actions">
							  <?php echo anchor('admin/clients/view/' . $client->id, 'Open', array('class'=>'button activate')); ?>
								<?php echo anchor('admin/clients/edit/' . $client->id, lang('global:edit'), array('class'=>'button edit')); ?>
								<?php echo anchor('admin/clients/delete/' . $client->id, lang('global:delete'), array('class'=>'confirm button delete')); ?>
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