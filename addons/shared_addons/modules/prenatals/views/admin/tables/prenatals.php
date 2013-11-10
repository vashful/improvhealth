<?php if (!empty($prenatals)): ?>
        <div class="number-results">
                <p>Showing <?php echo number_format($item_start); ?> - <?php echo number_format($item_end); ?> clients (<?php echo number_format($totalitems); ?> total.)</p>
        </div>
        <table border="0" class="table-list">
				<thead>
					<tr>
						<th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
						<th class="collapse">Name</th>
						<th class="collapse">Age</th>
						<th class="collapse" width="75">LMP</th>
						<th class="collapse">G-P</th>
						<th class="collapse" width="75">EDC</th>
						<th class="collapse">Tetanus Status</th>
						<th class="collapse">Risk Code</th>
						<th class="collapse" width="75">Risk - Date Detected</th>
						<th width="200" style="text-align: center"></th> 
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
					<?php foreach ($prenatals as $prenatal): ?>
						<tr>
							<td class="align-center"><?php echo form_checkbox('action_to[]', $prenatal->pn_id); ?></td>							
							<td><a class="green" href="admin/clients/view/<?php echo  $prenatal->id; ?>"><?php echo $prenatal->last_name; ?>, <?php echo $prenatal->first_name; ?> <?php echo substr($prenatal->middle_name,0,1); ?>.</a>
                                                        </td>				
														<?php $age = floor((time() - $prenatal->dob)/31556926); ?>
							<td class="collapse"><?php echo $age ? $age : ''; ?></td>
							<td class="collapse"><?php echo $prenatal->last_menstrual_period ? date('m-d-Y',$prenatal->last_menstrual_period) : '-'; ?></td>
							<td class="collapse"><?php echo $prenatal->gravida.'-'.$prenatal->para; ?></td>
							<td class="collapse"><?php echo $prenatal->estimated_date_confinement ? date('m-d-Y',$prenatal->estimated_date_confinement) : '-'; ?></td>
							<td class="collapse"><?php echo $prenatal->tetanus_status; ?></td>
							<td class="collapse">
                                                                <?php
                                                                        if($prenatal->risk_code=="0") 
                                                                                {echo "N";}
                                                                        elseif($prenatal->risk_code=="1")
                                                                                {echo "A";}
                                                                        elseif($prenatal->risk_code=="2")
                                                                                {echo "B";}  
                                                                        elseif($prenatal->risk_code=="3")
                                                                                {echo "C";}
                                                                        elseif($prenatal->risk_code=="4")
                                                                                {echo "D";}  
                                                                        elseif($prenatal->risk_code=="5")
                                                                                {echo "E";}
                                                                        elseif($prenatal->risk_code=="6")
                                                                                {echo "O";}                                                              
                                                                 ?>
                                                        </td>
                                                        <td class="collapse"><?php echo $prenatal->risk_date_detected ? date('m-d-Y',$prenatal->risk_date_detected) : '-'; ?></td>			
							<td class="actions" style="text-align: center">
								<?php echo anchor('admin/prenatals/edit/' . $prenatal->pn_id, lang('global:edit'), array('class'=>'button edit')); ?>
								<?php echo anchor('admin/prenatals/delete/' . $prenatal->pn_id, lang('global:delete'), array('class'=>'confirm button delete')); ?>
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