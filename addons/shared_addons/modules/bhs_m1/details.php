<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Report Module
 *
 * @author		Donald Myers
 * @package		PyroCMS
 * @subpackage	Report Module
 * @category	Modules
 */
class Module_Bhs_M1 extends Module 
{

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'BHS M1'
			),
			'description' => array(
				'en' => 'Generate BHS M1 Reports'
			),
			'frontend' => FALSE,
			'backend' => TRUE,
			'menu' => 'report'
		);
	}


	public function install()
	{
		return TRUE;
	}


	public function uninstall()
	{
		return TRUE;
	}


	public function upgrade($old_version)
	{
		return False;
	}


	public function help()
	{
		return "This module is used for generating M1 reports.";
	}


}

/* End of file details.php */
