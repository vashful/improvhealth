  <?php if (!empty($prenatals)): ?>

			<table border="0" class="table-list">
				<thead>
					<tr>
						<th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
						<th class="collapse">First Visit</th>
						<th class="collapse">Last Menstrual Period</th>
						<th class="collapse">Gravida - Para</th>
						<th class="collapse">Tetanus Status</th>
						<th class="collapse">Risk Code</th>
						<th width="130" style="text-align: center">Action</th> 
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
	            <td class="collapse"><?php echo date("F j, Y",$prenatal->prenatal_tri1_v1); ?></td>			
	            <td class="collapse"><?php echo date("F j, Y",$prenatal->last_menstrual_period); ?></td>			
							<td class="collapse"><?php echo $prenatal->gravida.'-'.$prenatal->para; ?></td>
							<td class="collapse"><?php echo $prenatal->tetanus_status; ?></td>
							<td class="collapse" style="text-align: center">
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
							<td class="actions">
								<?php echo anchor('admin/prenatals/edit/' . $prenatal->pn_id, lang('global:edit'), array('class'=>'button edit')); ?>
								<?php echo anchor('admin/prenatals/delete/' . $prenatal->pn_id, lang('global:delete'), array('class'=>'confirm button delete')); ?>
							</td>
							</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
	<?php endif; ?>