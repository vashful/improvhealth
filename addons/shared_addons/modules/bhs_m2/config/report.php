<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * The Report Module - currently only remove/empty cache folder(s)
 *
 * @author		Donald Myers
 * @package		PyroCMS
 * @subpackage	Report Module
 * @category	Modules
 */

$config['report.cache_protected_folders'] 	= array('simplepie');
$config['report.cannot_remove_folders'] 	= array('codeigniter','theme_m');

// An array of database tables that are eligible to be exported.
$config['report.export_tables']	= array('users',
												'contact_log',
												'files',
												'pages',
												'blog',
												'navigation_links',
												'comments',
                        'clients'
												);