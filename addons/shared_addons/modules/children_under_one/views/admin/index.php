<section class="title">
	<h4><?php echo lang('cuo.title'); ?></h4>
</section>
<section class="item">	
	<?php template_partial('filters'); ?>

	<?php echo form_open('admin/children_under_one/action'); ?>	
            <div class="table_action_buttons">
    			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
                <a class="btn blue" href="admin/children_under_one/print_page" target="_blank">Print</a>
    		</div>
            <div id="filter-stage">
    			<?php template_partial('cuo-list'); ?>
    		</div>
    		<div class="table_action_buttons">
    			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
                <a class="btn blue" href="admin/children_under_one/print_page" target="_blank">Print</a>
    		</div>
	<?php echo form_close(); ?>
</section>
