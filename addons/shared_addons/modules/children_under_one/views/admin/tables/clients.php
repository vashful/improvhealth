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
						<th>Full Name</th>
						<th>Mother Name</th>
						<th class="collapse">Gender</th>
						<th class="collapse">Date of Birth</th>
	          <th class="collapse">Remarks</th>     
						<th style="text-align:center">Actions</th>
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
							<td class="align-center"><?php echo form_checkbox('action_to[]', $client->cuo_id); ?></td>
							<td class="collapse"><a class="green" href="admin/clients/view/<?php echo  $client->id; ?>"><?php echo $client->last_name; ?>, <?php echo $client->first_name; ?> <?php echo substr($client->middle_name,0,1); ?>.</a></td>
							<td class="collapse"><?php echo ucwords($client->mother_name); ?></td>
							<td class="collapse"><?php echo ucwords($client->gender); ?></td>
							<td class="collapse"><?php echo $client->dob ? date('M d, Y', $client->dob) : ''; ?>&nbsp;</td>
              <td class="collapse"><?php echo $client->remarks; ?>&nbsp;</td>
							<td class="actions">
								<?php echo anchor('admin/children_under_one/edit/' . $client->cuo_id, lang('global:edit'), array('class'=>'button edit')); ?>
								<?php echo anchor('admin/children_under_one/delete/' . $client->cuo_id, lang('global:delete'), array('class'=>'confirm button delete')); ?>
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