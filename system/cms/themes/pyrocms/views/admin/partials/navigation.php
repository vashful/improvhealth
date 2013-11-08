<ul class="primary-nav">

	<li id="dashboard-link"><?php echo anchor('admin', lang('global:dashboard'), 'class="btn green' . (!$this->module > '' ? ' current' : '').'"');?></li>
  <?php if($this->current_user->group == 'staff'):?>
  			<li><?php echo anchor('admin/clients', 'Clients', 'class="top-link no-submenu' . (($this->module == 'clients') ? ' current"' : '"'));?></li>
  			<li><?php echo anchor('admin/consultations/set', 'Consultations', 'class="top-link no-submenu' . (($this->module == 'consultations') ? ' current"' : '"'));?></li>
  			<li><?php echo anchor('admin/family/set', 'Family Planning', 'class="top-link no-submenu' . (($this->module == 'family') ? ' current"' : '"'));?></li>
  			<li> <a href="#" class="<?php if ($this->module == 'prenatals' || $this->module == 'postpartum') echo 'current';?>">Maternal Care</a>
             <ul>
                 <li><?php echo anchor('admin/prenatals/set', 'Prenatal Care', 'class="top-link no-submenu' . (($this->module == 'prenatals') ? ' current"' : '"'));?></li>
      			     <li><?php echo anchor('admin/postpartum/set', 'Postpartum Care', 'class="top-link no-submenu' . (($this->module == 'postpartum') ? ' current"' : '"'));?></li>
  			     </ul>
  		 </li>
        <li>
          			<a href="<?php echo site_url('admin/children_under_one/set'); ?>" class="<?php if ($this->module == 'children_under_one' || $this->module == 'sick_children') echo 'current';?>">Children</a>
        			<ul>
        				<li><?php echo anchor('admin/children_under_one/set', 'Under 1 Year Old', 'class="' . (($this->module == 'children_under_one') ? 'sub-current"' : '"'));?></li>
        				<li><?php echo anchor('admin/sick_children/set', 'Sick Children', 'class="' . (($this->module == 'sick_children') ? 'sub-current"' : '"'));?></li>
        			</ul>
			</li>
       <li>
          			<?php echo anchor('admin/report', 'Reports', 'class="top-link no-submenu' . (($this->module == 'clients') ? ' current"' : '"'));?>
        			<ul>
        				<li><?php echo anchor('admin/bhs_m1', 'BHS M1', 'class="' . (($this->module == 'bhs_m1') ? 'sub-current"' : '"'));?></li>
        				<li><?php echo anchor('admin/bhs_m2', 'BHS M2', 'class="' . (($this->module == 'bhs_m2') ? 'sub-current"' : '"'));?></li>
                <li><?php echo anchor('admin/maintenance', 'RHU Integration', 'class="' . (($this->module == 'maintenance') ? 'sub-current"' : '"'));?></li>
        			</ul>
			</li>
  <?php elseif($this->current_user->group == 'rhu'):?>
  			<li><?php echo anchor('admin/clients', 'Clients', 'class="top-link no-submenu' . (($this->module == 'clients') ? ' current"' : '"'));?></li>
  			<li><?php echo anchor('admin/consultations/set', 'Consultations', 'class="top-link no-submenu' . (($this->module == 'consultations') ? ' current"' : '"'));?></li>
  			<li><?php echo anchor('admin/family/set', 'Family Planning', 'class="top-link no-submenu' . (($this->module == 'family') ? ' current"' : '"'));?></li>
  			<li> <a href="#" class="<?php if ($this->module == 'prenatals' || $this->module == 'postpartum') echo 'current';?>">Maternal Care</a>
             <ul>
                 <li><?php echo anchor('admin/prenatals/set', 'Prenatal Care', 'class="top-link no-submenu' . (($this->module == 'prenatals') ? ' current"' : '"'));?></li>
      			     <li><?php echo anchor('admin/postpartum/set', 'Postpartum Care', 'class="top-link no-submenu' . (($this->module == 'postpartum') ? ' current"' : '"'));?></li>
  			     </ul>
  		 </li>
        <li>
          			<a href="<?php echo site_url('admin/children_under_one/set'); ?>" class="<?php if ($this->module == 'children_under_one' || $this->module == 'sick_children') echo 'current';?>">Children</a>
        			<ul>
        				<li><?php echo anchor('admin/children_under_one/set', 'Under 1 Year Old', 'class="' . (($this->module == 'children_under_one') ? 'sub-current"' : '"'));?></li>
        				<li><?php echo anchor('admin/sick_children/set', 'Sick Children', 'class="' . (($this->module == 'sick_children') ? 'sub-current"' : '"'));?></li>
        			</ul>
			</li>
       <li>
          			<?php echo anchor('admin/report', 'Reports', 'class="top-link no-submenu' . (($this->module == 'clients') ? ' current"' : '"'));?>
        			<ul>
        				<li><?php echo anchor('admin/bhs_m1', 'BHS M1', 'class="' . (($this->module == 'bhs_m1') ? 'sub-current"' : '"'));?></li>
        				<li><?php echo anchor('admin/bhs_m2', 'BHS M2', 'class="' . (($this->module == 'bhs_m2') ? 'sub-current"' : '"'));?></li>
        				<li><?php echo anchor('admin/rhu_a1', 'RHU A1', 'class="' . (($this->module == 'rhu_a1') ? 'sub-current"' : '"'));?></li>
        				<li><?php echo anchor('admin/rhu_a2', 'RHU A2', 'class="' . (($this->module == 'rhu_a2') ? 'sub-current"' : '"'));?></li>
                <li><?php echo anchor('admin/rhu_a3', 'RHU A3', 'class="' . (($this->module == 'rhu_a3') ? 'sub-current"' : '"'));?></li>
        				<li><?php echo anchor('admin/rhu_q1', 'Quarterly Report', 'class="' . (($this->module == 'rhu_q1') ? 'sub-current"' : '"'));?></li>
                
                <li><?php echo anchor('admin/maintenance', 'RHU Integration', 'class="' . (($this->module == 'maintenance') ? 'sub-current"' : '"'));?></li>
        			</ul>
			</li>
  <?php elseif($this->current_user->group == 'dental'):?>
  			<li><?php echo anchor('admin/clients', 'Clients', 'class="top-link no-submenu' . (($this->module == 'clients') ? ' current"' : '"'));?></li>
        <li><?php echo anchor('admin/dental_health/set', 'Dental Health', 'class="top-link no-submenu' . (($this->module == 'dental_health') ? ' current"' : '"'));?></li>

  <?php elseif($this->current_user->group == 'sanitary'):?>
  			<li><?php echo anchor('admin/clients', 'Clients', 'class="top-link no-submenu' . (($this->module == 'clients') ? ' current"' : '"'));?></li>
        <li><?php echo anchor('admin/environmental_health/set', 'Environmental Health', 'class="top-link no-submenu' . (($this->module == 'environmental_health') ? ' current"' : '"'));?></li>
  <?php else:?>
	
		<?php
		foreach ($menu_items as $menu_item)
		{
			$count = 0;

			//Let's see how many menu items they have access to
			if ($this->current_user->group == 'admin')
			{
				$count = count($modules[$menu_item]);
			}
			else
			{
				if (array_key_exists($menu_item, $modules)) 
				{
					foreach ($modules[$menu_item] as $module)
					{
						$count += array_key_exists($module['slug'], $this->permissions) ? 1 : 0;
					}
				}
				
			}

			// If we are in the users menu, we have to account for the hacked link below
			if ($menu_item == 'users')
			{
				$count += (array_key_exists('users', $this->permissions) OR $this->current_user->group == 'admin') ? 1 : 0;
			}

			// If they only have access to one item in this menu, why not just have it as the main link?
			if ($count > 0)
			{
				// They have access to more than one menu item, so create a drop menu
				if ($count > 1 )
				{
					echo '<li>';

					$name = lang('cp_nav_'.$menu_item)!=''&&lang('cp_nav_'.$menu_item)!=NULL ? lang('cp_nav_'.$menu_item) : $menu_item;
					$current = (($this->module_details && $this->module_details['menu'] == $menu_item) or $menu_item == $this->module);
					$class = $current ? "top-link current" : "top-link";
					echo anchor(current_url() . '#', ucfirst($name), array('class' => $class));

					echo '<ul>';
					
				// User has access to Users module only, no other users item
				} 
				elseif ($count == 1 AND ($this->module == 'users' OR $this->module == '') AND $menu_item == 'users') 
				{
					echo '<li>' . anchor('admin/users', lang('cp_manage_users'), array('style' => 'font-weight: bold;', 'class' => $this->module == 'users' ? 'top-link no-submenu  current' : 'top-link no-submenu ')) . '</li>';
				}
				
				// Not a big fan of the following hack, if a module needs two navigation links, we should be able to define that
				if ( (array_key_exists('users', $this->permissions) OR $this->current_user->group == 'admin') AND $menu_item == 'users' AND $count != 1)
				{
					echo '<li>' . anchor('admin/users', lang('cp_manage_users'), 'class="' . (($this->module == 'users') ? ' current"' : '"')) . '</li>';
				} 

				//Lets get the sub-menu links, or parent link if that is the case
				if (array_key_exists($menu_item, $modules)) 
				{
					if (is_array($modules[$menu_item])) 
					{
						ksort($modules[$menu_item]);
					}
						
					foreach ($modules[$menu_item] as $module)
					{
						if (lang('cp_nav_'.$module['slug'])!=''&&lang('cp_nav_'.$module['slug'])!=NULL)
						{
							$module['name'] = lang('cp_nav_'.$module['slug']);
						}
						$current = $this->module == $module['slug'];
						$class = $current ? "current " : "";
						$class .= $count <= 1 ? "top-link no-submenu " : "";
						
						if (array_key_exists($module['slug'], $this->permissions) OR $this->current_user->group == 'admin')
						{
							echo '<li>' . anchor('admin/'.$module['slug'], $module['name'], array('class'=>$class)) . '</li>';
						}
					}
				}
			}
			
			// They have access to more than one menu item, so close the drop menu
			if ($count > 1)
			{
				echo '</ul>';
				echo '</li>';
			}
		}
		?>
		<?php endif;?>
		
		<?php if (array_key_exists('settings', $this->permissions) OR $this->current_user->group == 'admin'): ?>
			<li><?php echo anchor('admin/settings', lang('cp_nav_settings'), 'class="top-link no-submenu' . (($this->module == 'settings') ? ' current"' : '"'));?></li>
		<?php endif; ?>

		<?php if (array_key_exists('modules', $this->permissions) OR $this->current_user->group == 'admin'): ?>
			<li><?php echo anchor('admin/modules', lang('cp_nav_addons'), 'class="last top-link no-submenu' . (($this->module == 'modules') ? ' current"' : '"'));?></li>
		<?php endif; ?>
		
		<?php
		/* Do we really need to greet people? 
		<li id="user-greeting"><a href="#"><?php echo sprintf(lang('cp_logged_in_welcome'), $user->display_name); ?></a></li>
		*/
		?>
			
		<li>
			<a href="<?php echo site_url('admin/#'); ?>"><?php echo lang('global:profile'); ?></a>
			<ul>
				<li><?php if ($this->settings->enable_profiles) echo anchor('edit-profile', lang('cp_edit_profile_label')) ?></li>
				<!--<li><?php echo anchor('', lang('cp_view_frontend'), 'target="_blank"'); ?></li>-->
				<li><?php echo anchor('admin/logout', lang('cp_logout_label')); ?></li>
				
				<?php if($module_details['slug']): ?>
					<li id="help-link">
						<?php echo anchor('admin/help/'.$module_details['slug'], lang('help_label'), array('title' => lang('help_label').'->'.$module_details['name'], 'class' => 'modal')); ?>
					</li>
				<?php endif; ?>
			</ul>
		</li>

	</ul>
