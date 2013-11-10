<section class="title">
	<h4>RHU Q1</h4>
</section>
<script type="text/javascript">
    var base_url = "<?php echo base_url();?>";
</script>
<section class="item">
    <div class="number-results" style="display:none;">
    </div>
	<?php if ( ! empty($tables)): ?>
		<table border="0" class="table-list">
			<thead>
				<tr>
					<th width="150px">Reports</th>
					<th width="350px">Date Range</th>
                    <th width="300px">Projected Population of the Year</th>
					<th width="300px">Action</th>
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
      <?php $attributes = array('class' => 'crud', 'id' => 'frm_show', 'target' => '_blank'); ?>     
      <?php echo form_open(uri_string().'/show', $attributes); ?>
               <tr>  
                    <td><strong>RHU Q1</strong></td>
					<td class="align-center">
                        <?php echo form_dropdown('q1_quarter', $quarters, '', 'id="q1_quarter" class="chzn"') ?>
                        <?php echo form_dropdown('q1_year', $years, date("Y"), 'id="q1_year" class="chznsm"') ?>
                    </td>
                    <td>
                        <?php $data = array('name'=>'q1_ppoty','id'=>'q1_ppoty'); ?>
                        <?php echo form_input($data); ?>
                    </td>
					<td class="buttons buttons-small">
                        <button type="button" id="rpt_preview" name="btnAction" value="generate_rhu_q1" class="btn blue">Preview</button>
                        <button type="button" id="generate_rhu_q1" name="btnAction" value="generate_rhu_q1" class="btn blue">Show</button>
                        <button type="button" id="generate_rhu_q1_dl" name="btnAction" value="generate_rhu_q1_dl" class="btn blue">Download</button>
					</td>
				</tr>
      <?php echo form_close(); ?>
			</tbody>
		</table>
	<?php endif;?>

</section>
