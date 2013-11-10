<?php //echo print_r($clients); ?>
<?php if (!empty($clients)): ?>
        <table border="0" class="table-list">
        	<thead>
        		<tr>
        			<th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
        			<th class="collapse">Orally Fit</th>
              <th class="collapse">Date Given BOHC</th>
        			<th class="collapse">Services</th>
              <th class="collapse">Remarks</th>     
        			<th width="200" align="center">Actions</th>
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
        				<td class="align-center"><?php echo form_checkbox('action_to[]', $client->dental_health_id); ?></td>
        				<td class="collapse"><?php echo $client->orally_fit; ?></td>
                <td class="collapse"><?php echo date('F j, Y', $client->date_given_bohc); ?>&nbsp;</td>
        				<td class="collapse"><?php echo $client->bohc_services; ?></td>        
                                        <td class="collapse"><?php echo $client->remarks; ?>&nbsp;</td>
        				<td class="actions" align="center">
        					<?php echo anchor('admin/dental_health/edit/' . $client->dental_health_id, lang('global:edit'), array('class'=>'button edit')); ?>
        					<?php echo anchor('admin/dental_health/delete/' . $client->dental_health_id, lang('global:delete'), array('class'=>'confirm button delete')); ?>
        				</td>
        				</tr>
        		<?php endforeach; ?>
        	</tbody>
        </table>
<?php endif; ?>