<section class="title">
	<h4><?php echo lang('filariasis.title'); ?></h4>
</section>

<section class="item">
	
	<?php template_partial('filters'); ?>

	<?php echo form_open('admin/filariasis/action'); ?>
            <div class="table_action_buttons">
    			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
                <a class="btn blue" href="admin/filariasis/print_page" target="_blank">Print</a>
    		</div>
            <div id="filter-stage">
    			<?php template_partial('filariasis-list'); ?>
    		</div>
    		<div class="table_action_buttons">
    			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
                <a class="btn blue" href="admin/filariasis/print_page" target="_blank">Print</a>
    		</div>
	<?php echo form_close(); ?>

</section>
