<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Dental_Health extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Dental Health'
			),
			'description' => array(
				'en' => 'Manage Dental Health Clients'
			),
			'frontend' => FALSE,
			'backend'  => TRUE,
			'menu'	  => 'clients',
			
			'shortcuts' => array(
						array(
					 	   'name' => 'postpartum.add_title',
						    'uri' => 'admin/dental_health/add_client',
						    'class' => 'add'
						),
					),
			'sections' => array(
                        'mylist' => array(
						array(
					 	   'name' => 'Add New Dental Health for this Client',
						    'uri' => 'admin/dental_health/add',
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