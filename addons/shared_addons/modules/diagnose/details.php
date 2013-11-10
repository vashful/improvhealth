<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Diagnose extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Diagnose',
			),
			'description' => array(
				'en' => 'Diagnose a Client.'
			),
			'frontend' => FALSE,
			'backend'  => TRUE,
			'menu'	  => 'clients',
			
      'sections' => array(
			    'diagnose' => array(
				    'name' => 'diagnose.index_title',
				    'uri' => 'admin/diagnose',
				    'shortcuts' => array(
						array(
					 	   'name' => 'diagnose.add_title',
						    'uri' => 'admin/diagnose/add_client',
						    'class' => 'add'
						),
					),
				),
			  'diagnosis' => array(
				    'name' => 'diagnosis_list_title',
				    'uri' => 'admin/diagnose/diagnosis',
				    'shortcuts' => array(
						array(
						    'name' => 'diagnosis_create_title',
						    'uri' => 'admin/diagnose/diagnosis/create',
						    'class' => 'add'
						),
				    ),
			    ),
			    'referrer' => array(
				    'name' => 'referrer_list_title',
				    'uri' => 'admin/diagnose/referrer',
				    'shortcuts' => array(
						array(
						    'name' => 'referrer_create_title',
						    'uri' => 'admin/diagnose/referrer/create',
						    'class' => 'add'
						),
				    ),
			    ),
			   'mylist' => array(
						array(
					 	   'name' => 'Add Diagnosis for this Client',
						    'uri' => 'admin/diagnose/add',
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