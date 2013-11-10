  <?php if (!empty($clients)): ?>

			<table border="0" class="table-list">
				<thead>
					<tr>
						<th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
						<th>Date Started</th>
						<th class="collapse">Gender</th>
	          <th class="collapse">FP Method</th>     
	          <th class="collapse">Type of Client</th>
	          <th class="collapse">FP Previous Method</th>
	          <th class="collapse">Drop-Out</th>
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
							<td class="align-center"><?php echo form_checkbox('action_to[]', $client->fp_id); ?></td>
							<td class="collapse"><?php echo date("F j, Y",$client->date_started); ?></td>
							<td class="collapse"><?php echo ucwords($client->gender); ?></td>
              <td class="collapse"><?php echo $client->method_id ? $methods[$client->method_id]: '- - - - - - -'; ?></td>
              <td class="collapse"><?php echo $client->client_type ? $client_types[$client->client_type]: '- - - - - - -'; ?></td>
              <td class="collapse"><?php echo $client->previous_method_id ? $methods[$client->previous_method_id]: '- - - - - - -'; ?></td>   
              <td class="collapse"><?php echo $client->drop_out_reason ? $drop_out_reasons[$client->drop_out_reason]: '- - - - - - -'; ?></td>
							<td class="actions" style="text-align: center">
								<?php echo anchor('admin/family/edit/' . $client->fp_id, lang('global:edit'), array('class'=>'button edit')); ?>
								<?php echo anchor('admin/family/delete/' . $client->fp_id, lang('global:delete'), array('class'=>'confirm button delete')); ?>
							</td>
							</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
	<?php endif; ?>