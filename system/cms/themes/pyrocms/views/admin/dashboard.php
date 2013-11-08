<?php if ((isset($analytic_visits) OR isset($analytic_views)) AND $theme_options->pyrocms_analytics_graph == 'yes'): ?>
<script type="text/javascript">

$(function($) {
		var visits = <?php echo isset($analytic_visits) ? $analytic_visits : 0; ?>;
		var views = <?php echo isset($analytic_views) ? $analytic_views : 0; ?>;

		$.plot($('#analytics'), [{ label: 'Visits', data: visits },{ label: 'Page views', data: views }], {
			lines: { show: true },
			points: { show: true },
			grid: { hoverable: true, backgroundColor: '#fefefe' },
			series: {
				lines: { show: true, lineWidth: 1 },
				shadowSize: 0
			},
			xaxis: { mode: "time" },
			yaxis: { min: 0},
			selection: { mode: "x" }
		});
		
		function showTooltip(x, y, contents) {
			$('<div id="tooltip">' + contents + '</div>').css( {
				position: 'absolute',
				display: 'none',
				top: y + 5,
				left: x + 5,
				'border-radius': '3px',
				'-moz-border-radius': '3px',
				'-webkit-border-radius': '3px',
				padding: '3px 8px 3px 8px',
				color: '#ffffff',
				background: '#000000',
				opacity: 0.80
			}).appendTo("body").fadeIn(500);
		}
	 
		var previousPoint = null;
		
		$("#analytics").bind("plothover", function (event, pos, item) {
			$("#x").text(pos.x.toFixed(2));
			$("#y").text(pos.y.toFixed(2));
	 
				if (item) {
					if (previousPoint != item.dataIndex) {
						previousPoint = item.dataIndex;
						
						$("#tooltip").remove();
						var x = item.datapoint[0],
							y = item.datapoint[1];
						
						showTooltip(item.pageX, item.pageY,
									item.series.label + " : " + y);
					}
				}
				else {
					$("#tooltip").fadeOut(500);
					previousPoint = null;            
				}
		});
	
	});
</script>

<section class="title">
	<h4>Statistics</h4>
</section>
	
<div id="analyticsWrapper">
	<div id="analytics"></div>
</div>

<?php endif; ?>
<!-- End Analytics -->
	
<!-- Add an extra div to allow the elements within it to be sortable! -->
<div id="sortable">

	<!-- Dashboard Widgets -->
	{{ widgets:area slug="dashboard" }}
		
		<!-- Begin Search Form -->
	<?php if ($theme_options->pyrocms_quick_links == 'yes') : ?>
	<div class="">
		
		<section class="draggable title">
			<h4>Search Clients</h4>
			<a class="tooltip-s toggle" title="Toggle this element"></a>
		</section>
		
		<section id="search_form" class="item <?php echo isset($rss_items); ?>">	
	<?php echo form_open('admin/clients'); ?>
	<?php echo form_hidden('f_module', $module_details['slug']); ?>
	<ul>
		<li><label for="f_gender">Enter search keyword: e.g. First name, Family name, Middle name, Serial number, Form number: </label></li> <br />
		<li><?php echo form_input('f_keywords','','placeholder="Start Search Now!"'); ?> 				
        <button type="submit" name="btnAction" value="re-index" class="btn green">
					<span>Search</span>
				</button></li>
	</ul>
	<?php echo form_close(); ?>
		</section>

	</div>		
	<?php endif; ?>
	<!-- End Search Form -->

	<!-- Begin Quick Links -->
	<?php if ($theme_options->pyrocms_quick_links == 'yes') : ?>
	<div class="">
		
		<section class="draggable title">
			<h4>ImprovHealth <?php echo lang('cp_admin_quick_links') ?></h4>
			<a class="tooltip-s toggle" title="Toggle this element"></a>
		</section>
		
		<section id="quick_links" class="item item_bhs <?php echo isset($rss_items); ?>">
			<ul>
			  
        <?php if(array_key_exists('clients', $this->permissions) OR $this->current_user->group == 'admin'): ?>
				<li>
					<a class="tooltip-s" title="Manage Clients" href="<?php echo site_url('admin/clients') ?>"><?php echo image('icons/bhs/clients.png'); ?></a>
				</li>
				<?php endif; ?>
				
				<?php if(array_key_exists('family', $this->permissions) OR $this->current_user->group == 'admin'): ?>
				<li>
					<a class="tooltip-s" title="Target Client List for Family Planning" href="<?php echo site_url('admin/family') ?>"><?php echo image('icons/bhs/family.png'); ?></a>
				</li>
				<?php endif; ?>
				
				<?php if(array_key_exists('prenatals', $this->permissions) OR $this->current_user->group == 'admin'): ?>
				<li>
					<a class="tooltip-s" title="Target Client List for Prenatal Care" href="<?php echo site_url('admin/prenatals') ?>"><?php echo image('icons/bhs/prenatals.png'); ?></a>
				</li>
				<?php endif; ?>
				
				<?php if(array_key_exists('postpartum', $this->permissions) OR $this->current_user->group == 'admin'): ?>
				<li>
					<a class="tooltip-s" title="Target Client List for Postpartum Care" href="<?php echo site_url('admin/postpartum') ?>"><?php echo image('icons/bhs/postpartum.png'); ?></a>
				</li>
				<?php endif; ?>
				
				<?php if(array_key_exists('children_under_one', $this->permissions) OR $this->current_user->group == 'admin'): ?>
				<li>
					<a class="tooltip-s" title="Target Client List for Children Under 1 Year Old" href="<?php echo site_url('admin/children_under_one') ?>"><?php echo image('icons/bhs/baby.png'); ?></a>
				</li>
				<?php endif; ?>
				
				<?php if(array_key_exists('sick_children', $this->permissions) OR $this->current_user->group == 'admin'): ?>
				<li>
					<a class="tooltip-s" title="Target Client List for Sick Children" href="<?php echo site_url('admin/sick_children') ?>"><?php echo image('icons/bhs/child.png'); ?></a>
				</li>
				<?php endif; ?>
				
			</ul>
		</section>

	</div>		
	<?php endif; ?>
	<!-- End Quick Links -->
	
		<!-- Begin Quick Links -->
	<?php if ($theme_options->pyrocms_quick_links == 'yes' && $this->current_user->group !="staff") : ?>
	<div class="">
		
		<section class="draggable title">
			<h4><?php echo lang('cp_admin_quick_links') ?></h4>
			<a class="tooltip-s toggle" title="Toggle this element"></a>
		</section>
		
		<section id="quick_links" class="item <?php echo isset($rss_items); ?>">
			<ul>
      				
				<?php if(array_key_exists('comments', $this->permissions) OR $this->current_user->group == 'admin'): ?>
				<li>
					<a class="tooltip-s" title="<?php echo lang('cp_manage_comments'); ?>" href="<?php echo site_url('admin/comments') ?>"><?php echo image('icons/comments.png'); ?></a>
				</li>
				<?php endif; ?>
				
				<?php if(array_key_exists('pages', $this->permissions) OR $this->current_user->group == 'admin'): ?>
				<li>
					<a class="tooltip-s" title="<?php echo lang('cp_manage_pages'); ?>" href="<?php echo site_url('admin/pages') ?>"><?php echo image('icons/pages.png'); ?></a>
				</li>
				<?php endif; ?>
				
				<?php if(array_key_exists('files', $this->permissions) OR $this->current_user->group == 'admin'): ?>
				<li>
					<a class="tooltip-s" title="<?php echo lang('cp_manage_files'); ?>" href="<?php echo site_url('admin/files') ?>"><?php echo image('icons/files.png'); ?></a>
				</li>
				<?php endif; ?>
				
				<?php if(array_key_exists('users', $this->permissions) OR $this->current_user->group == 'admin'): ?>
				<li>
					<a class="tooltip-s" title="<?php echo lang('cp_manage_users'); ?>" href="<?php echo site_url('admin/users') ?>"><?php echo image('icons/users.png'); ?></a>
				</li>
				<?php endif; ?>
				
			</ul>
		</section>

	</div>		
	<?php endif; ?>
	<!-- End Quick Links -->

	

</div>
<!-- End sortable div -->