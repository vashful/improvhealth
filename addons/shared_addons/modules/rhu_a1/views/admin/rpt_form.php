<section class="title">
	<h4>BHS Reports</h4>
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
                <td><strong>RHU A1</strong></td>
					<td class="align-center"><?php echo form_dropdown('rhu_a1_year', $years, date("Y"), 'id="" class="chzn"') ?></td>
					<td class="buttons buttons-small">
						<button type="submit" name="btnAction" value="generate_rhu_a1" class="btn blue">Show</button>
                        <button type="submit" name="btnAction" value="generate_rhu_a1_dl" class="btn blue">Download</button>
					</td>
				</tr>
      <?php echo form_close(); ?>
			</tbody>
		</table>
	<?php endif;?>

</section>
