<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Children_Under_One extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Children Under 1 Year Old'
			),
			'description' => array(
				'en' => 'Manage Children Under 1 Year Old.'
			),
			'frontend' => FALSE,
			'backend'  => TRUE,
			'menu'	  => 'clients',

	    'shortcuts' => array(
						array(
					 	   'name' => 'cuo.add_title',
						    'uri' => 'admin/children_under_one/add_client',
						    'class' => 'add'
						),
					),
		);
	}

	public function install()
	{
		return TRUE;
	}

	public function uninstall()
	{
		//it's a core module, lets keep it around
		return FALSE;
	}

	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return TRUE;
	}
}
/* End of file details.php */