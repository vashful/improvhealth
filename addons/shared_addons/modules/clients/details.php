<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Clients extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Clients'
			),
			'description' => array(
				'en' => 'Manage Clients.'
			),
			'frontend' => FALSE,
			'backend'  => TRUE,
			'menu'	  => 'clients',
			
      'sections' => array(
			    'clients' => array(
				    'name' => 'clients.index_title',
				    'uri' => 'admin/clients',
				    'shortcuts' => array(
						array(
					 	   'name' => 'clients.add_title',
						    'uri' => 'admin/clients/add',
						    'class' => 'add'
						),
					),
				),
				'region' => array(
				    'name' => 'region_list_title',
				    'uri' => 'admin/clients/region',
				    'shortcuts' => array(
						array(
						    'name' => 'region_create_title',
						    'uri' => 'admin/clients/region/create',
						    'class' => 'add'
						),
				    ),
			    ),
			    'province' => array(
				    'name' => 'province_list_title',
				    'uri' => 'admin/clients/province',
				    'shortcuts' => array(
						array(
						    'name' => 'province_create_title',
						    'uri' => 'admin/clients/province/create',
						    'class' => 'add'
						),
				    ),
			    ),
			    'city' => array(
				    'name' => 'city_list_title',
				    'uri' => 'admin/clients/city',
				    'shortcuts' => array(
						array(
						    'name' => 'city_create_title',
						    'uri' => 'admin/clients/city/create',
						    'class' => 'add'
						),
				    ),
			    ),
			    'barangay' => array(
				    'name' => 'barangay_list_title',
				    'uri' => 'admin/clients/barangay',
				    'shortcuts' => array(
						array(
						    'name' => 'barangay_create_title',
						    'uri' => 'admin/clients/barangay/create',
						    'class' => 'add'
						),
				    ),
			    ),
		    )
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