  <?php if (!empty($clients)): ?>

			<table border="0" class="table-list">
				<thead>
					<tr>
						<th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
						<th class="collapse">Age Diagnosed</th>
						<th class="collapse">Diagnosis</th>
						<th class="collapse">Date Diagnosed</th>
						<th class="collapse">Referred by</th>

						

						<th width="120" style="text-align: center">Action</th>
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
							<?php $age = floor((time() - $client->dob)/31556926); ?>
							<td class="collapse"><?php echo $age ? $age : ''; ?></td>
							<td class="collapse"><?php echo $client->diagnosis_id ? $list_diagnosis[$client->diagnosis_id]: '- - - - - - -'; ?></td>
							<td class="collapse"><?php echo date('F j, Y', $client->date_diagnose); ?>&nbsp;</td>
							<td class="collapse"><?php echo $client->referrer_id ? $referrers[$client->referrer_id]: '- - - - - - -'; ?></td>
							<td class="actions" style="text-align: center">
								<?php echo anchor('admin/diagnose/edit/' . $client->diagnoses_id, lang('global:edit'), array('class'=>'button edit')); ?>
								<?php echo anchor('admin/diagnose/delete/' . $client->diagnoses_id, lang('global:delete'), array('class'=>'confirm button delete')); ?>
							</td>
							</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
	<?php endif; ?>