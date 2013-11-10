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
      <?php echo form_open(uri_string().'/show', 'class="crud" target="_blank"'); ?>
                <tr>                
                <td><strong>BHS M1</strong></td>
					<td class="align-center">
						<?php echo form_dropdown('a_year', $years, date("Y"), 'id="" class="chznsm"') ?></td>
					<td class="buttons buttons-small">
						<button type="submit" name="btnAction" value="generate_bhs_a" class="btn blue">Show</button>
                        <button type="submit" name="btnAction" value="generate_bhs_a_dl" class="btn blue">Download</button>
					</td>
				</tr>
      <?php echo form_close(); ?>
			</tbody>
		</table>
	<?php endif;?>

</section>
