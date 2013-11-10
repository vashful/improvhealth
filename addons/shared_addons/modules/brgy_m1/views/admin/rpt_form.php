<section class="title">
	<h4>Barangay M1</h4>
</section>

<section class="item">

	<?php if ( ! empty($tables)): ?>
		<table border="0" class="table-list">
			<thead>
				<tr>
					<th width="150px">Reports</th>
					<th class="align-center">Barangay</th>
					<th class="align-center">Date Range</th>
					<th width="350px">Action</th>
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
      <?php echo form_open(uri_string().'/show', 'class="crud" target="_blank"'); ?>
        <tr>                
          <td><strong>BHS M1</strong></td>
					<td class="align-center"><?php echo form_dropdown('m1_brgy',  array(0 =>'-- All --') + $barangays, 0, 'id="m1_brgy" class="chzn"'); ?></td>
					<td class="align-center"><?php echo form_dropdown('m1_month', $months, date("m"), 'id="" class="chznsm"') ?> <?php echo form_dropdown('m1_year', $years, date("Y"), 'id="" class="chznsm"') ?></td>
          <td class="buttons buttons-small">
						<button type="submit" name="btnAction" value="generate_bhs_m1" class="btn blue">Show</button>
            <button type="submit" name="btnAction" value="generate_bhs_m1_dl" class="btn blue">Download</button>
					</td>
				</tr>
      <?php echo form_close(); ?>
			</tbody>
		</table>
	<?php endif;?>

</section>
