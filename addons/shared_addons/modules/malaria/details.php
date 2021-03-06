<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Malaria extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Malaria'
			),
			'description' => array(
				'en' => 'Manage Malaria'
			),
			'frontend' => FALSE,
			'backend'  => TRUE,
			'menu'	  => 'clients',
			
	    'shortcuts' => array(
						array(
					 	   'name' => 'malaria.add_title',
						    'uri' => 'admin/malaria/add_client',
						    'class' => 'add'
						),
					),
			'sections' => array(
          'mylist' => array(
						array(
					 	   'name' => 'Add Record for this Client Under Malaria',
						    'uri' => 'admin/malaria/add',
						    'class' => 'add'
						),
						array(
					 	   'name' => 'Back to Client Information Page',
						    'uri' => 'admin/clients/view',
						    'class' => 'view'
						),
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