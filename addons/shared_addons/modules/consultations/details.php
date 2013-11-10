<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Consultations extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Consultations',
			),
			'description' => array(
				'en' => 'Manage Client Consultations.'
			),
			'frontend' => FALSE,
			'backend'  => TRUE,
			'menu'	  => 'clients',
			
      'sections' => array(
			    'consultations' => array(
				    'name' => 'consultations.index_title',
				    'uri' => 'admin/consultations',
				    'shortcuts' => array(
						array(
					 	   'name' => 'consultations.add_title',
						    'uri' => 'admin/consultations/add_client',
						    'class' => 'add'
						),
					),
				),
			  'diseases' => array(
				    'name' => 'diseases_list_title',
				    'uri' => 'admin/consultations/diseases',
				    'shortcuts' => array(
						array(
						    'name' => 'diseases_create_title',
						    'uri' => 'admin/consultations/diseases/create',
						    'class' => 'add'
						),
				    ),
			    ),
			    'referrer' => array(
				    'name' => 'referrer_list_title',
				    'uri' => 'admin/consultations/referrer',
				    'shortcuts' => array(
						array(
						    'name' => 'referrer_create_title',
						    'uri' => 'admin/consultations/referrer/create',
						    'class' => 'add'
						),
				    ),
			    ),
			   'mylist' => array(
						array(
					 	   'name' => 'Add Consultations for this Client',
						    'uri' => 'admin/consultations/add',
						    'class' => 'add'
						),
						array(
					 	   'name' => 'Back to Client Information Page',
						    'uri' => 'admin/clients/view',
						    'class' => 'view'
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