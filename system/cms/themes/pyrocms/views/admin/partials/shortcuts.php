<nav id="shortcuts">
	<ul>
	<?php $mods = array("family","postpartum","children_under_one","sick_children","prenatals","consultations","dental_health","environmental_health");?>
	<?php if($this->session->userdata('selected_client_id') && in_array($this->module, $mods)): ?>
    <?php if ( ! empty($module_details['sections']['mylist'])): ?>
			<?php foreach ($module_details['sections']['mylist'] as $shortcut):
				$name 	= $shortcut['name'];
				$uri	= $shortcut['uri'];
				unset($shortcut['name']);
				unset($shortcut['uri']); ?>
			<li><a <?php foreach($shortcut AS $attr => $value) echo $attr.'="'.$value.'"'; echo 'href="' . site_url($uri).'/'.$this->session->userdata('selected_client_id'). '">' . $name . '</a>'; ?></li>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php else: ?>
		<?php if ( ! empty($module_details['sections'][$active_section]['shortcuts'])): ?>
			<?php foreach ($module_details['sections'][$active_section]['shortcuts'] as $shortcut):
				$name 	= $shortcut['name'];
				$uri	= $shortcut['uri'];
				unset($shortcut['name']);
				unset($shortcut['uri']); ?>
			<li><a <?php foreach($shortcut AS $attr => $value) echo $attr.'="'.$value.'"'; echo 'href="' . site_url($uri) . '">' . lang($name) . '</a>'; ?></li>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if ( ! empty($module_details['shortcuts'])): ?>
			<?php foreach ($module_details['shortcuts'] as $shortcut):
				$name 	= $shortcut['name'];
				$uri	= $shortcut['uri'];
				unset($shortcut['name']);
				unset($shortcut['uri']); ?>
			<li><a <?php foreach($shortcut AS $attr => $value) echo $attr.'="'.$value.'"'; echo 'href="' . site_url($uri) . '">' . lang($name) . '</a>'; ?></li>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php endif; ?>	
	</ul>
</nav>