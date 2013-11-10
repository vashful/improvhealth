  <?php if (!empty($clients)): ?>

			<table border="0" class="table-list">
				<thead>
					<tr>
                                                <th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
                                                <th>Full Name</th>
                                                <th>Gender</th>
                                                <th class="collapse">Date Conducted</th>
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
                                                        <td class="align-center"><?php echo form_checkbox('action_to[]', $client->environmental_health_id); ?></td>
                                                        <td class="collapse"><?php echo $client->last_name; ?>, <?php echo $client->first_name; ?> <?php echo substr($client->middle_name,0,1); ?>.</td>
                                                        <td class="collapse"><?php echo ucwords($client->gender); ?>&nbsp;</td>
                                                        <td class="collapse"><?php echo date('F j, Y', $client->date_conducted); ?>&nbsp;</td>
                                                        <td class="collapse"><?php echo $client->remarks; ?>&nbsp;</td>
                                                        <td class="actions" style="text-align: center">
                                                                <?php echo anchor('admin/environmental_health/edit/' . $client->environmental_health_id, lang('global:edit'), array('class'=>'button edit')); ?>
                                                                <?php echo anchor('admin/environmental_health/delete/' . $client->environmental_health_id, lang('global:delete'), array('class'=>'confirm button delete')); ?>
                                                        </td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
	<?php endif; ?>