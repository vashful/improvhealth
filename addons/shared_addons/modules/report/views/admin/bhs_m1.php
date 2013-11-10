<section class="title">
	<h4>BHS M1</h4>
</section>

<section class="item">

	<?php if ( ! empty($tables)): ?>
		<table border="0" class="table-list">
			<thead>
				<tr>
					<th width="200px">Reports</th>
					<th class="align-center">Date Range</th>
					<th width="500px">Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3">
						<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
      <?php echo form_open(uri_string().'/action', 'class="crud"'); ?>
				<tr>
					<td><strong>FHSIS</strong></td>
					<td class="align-center"><?php echo form_dropdown('fhsi_month', $months, date("m"), 'id="" class="chznsm"') ?> <?php echo form_dropdown('fhsi_year', $years, date("Y"), 'id="" class="chzn"') ?></td>
					<td class="buttons buttons-small">
						<button type="submit" name="btnAction" value="generate_fhis" class="btn blue">Generate Report</button>
					</td>
				</tr> 
                <tr>                
                <td><strong>BHS M1</strong></td>
					<td class="align-center"><?php echo form_dropdown('m1_month', $months, date("m"), 'id="" class="chznsm"') ?> <?php echo form_dropdown('m1_year', $years, date("Y"), 'id="" class="chzn"') ?></td>
					<td class="buttons buttons-small">
						<button type="submit" name="btnAction" value="generate_bhs_m1" class="btn blue">Show</button>
                        <button type="submit" name="btnAction" value="generate_bhs_m1_dl" class="btn blue">Download</button>
					</td>
				</tr>
                <td><strong>RHU Q1</strong></td>
					<td class="align-center"><?php echo form_dropdown('q1_quarter', $quarters, '', 'id="" class="chznsm"') ?><?php echo form_dropdown('q1_year', $years, date("Y"), 'id="" class="chzn"') ?></td>
					<td class="buttons buttons-small">
						<button type="submit" name="btnAction" value="generate_rhu_q1" class="btn blue">Show</button>
                        <button type="submit" name="btnAction" value="generate_rhu_q1_dl" class="btn blue">Download</button>
					</td>
				</tr>
                <td><strong>RHU A1</strong></td>
					<td class="align-center"><?php echo form_dropdown('rhu_a1_year', $years, date("Y"), 'id="" class="chzn"') ?></td>
					<td class="buttons buttons-small">
						<button type="submit" name="btnAction" value="generate_rhu_a1" class="btn blue">Show</button>
                        <button type="submit" name="btnAction" value="generate_rhu_a1_dl" class="btn blue">Download</button>
					</td>
				</tr>  
                <td><strong>RHU A2</strong></td>
					<td class="align-center"><?php echo form_dropdown('fhsi_year', $years, date("Y"), 'id="" class="chzn"') ?></td>
					<td class="buttons buttons-small">
						<button type="submit" name="btnAction" value="generate_rhu_a2" class="btn blue">Show</button>
                        <button type="submit" name="btnAction" value="generate_rhu_a2_dl" class="btn blue">Download</button>
					</td>
				</tr>
                <td><strong>RHU A3</strong></td>
					<td class="align-center"><?php echo form_dropdown('fhsi_year', $years, date("Y"), 'id="" class="chzn"') ?></td>
					<td class="buttons buttons-small">
						<button type="submit" name="btnAction" value="generate_rhu_a3" class="btn blue">Show</button>
                        <button type="submit" name="btnAction" value="generate_rhu_a3_dl" class="btn blue">Download</button>
					</td>
				</tr>
      <?php echo form_close(); ?>
			</tbody>
		</table>
	<?php endif;?>

</section>
