<?php defined('BASEPATH') OR die('No direct script access allowed');
/**
 * Report Module
 *
 * @author 		PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Report module
 * @category 	Modules
 */
class Reporte_m extends MY_Model
{
	public function download()
	{
   // Load the DB utility class
    $this->load->dbutil();
    
    $prefs = array(
                    'tables'      => array(),  // Array of tables to backup.
                    'ignore'      => array(),           // List of tables to omit from the backup
                    'format'      => 'zip',             // gzip, zip, txt
                    'filename'    => 'mybackup.sql',    // File name - NEEDED ONLY WITH ZIP FILES
                    'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                    'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                    'newline'     => "\n"               // Newline character used in backup file
                  );
    
    $backup = $this->dbutil->backup($prefs);
    
    $this->load->helper('download');
    force_download('mybackup.zip', $backup);
	}
  
  public function upload()
	{
   // Load the DB utility class
    $this->load->dbutil();
    
    $prefs = array(
                    'tables'      => array(),  // Array of tables to backup.
                    'ignore'      => array(),           // List of tables to omit from the backup
                    'format'      => 'zip',             // gzip, zip, txt
                    'filename'    => 'mybackup.sql',    // File name - NEEDED ONLY WITH ZIP FILES
                    'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                    'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                    'newline'     => "\n"               // Newline character used in backup file
                  );
    
    $backup = $this->dbutil->backup($prefs);
    
    $this->load->helper('file');
    write_file('mybackup.zip', $backup); 


    $this->load->library('ftp');
    
    $config['hostname'] = '127.0.0.1';
    $config['username'] = 'ronald';
    $config['password'] = 'admin1021';
    $config['port']     = 21;
    $config['passive']  = FALSE;
    $config['debug']    = TRUE;
    
    $this->ftp->connect($config);
    $this->ftp->upload('mybackup.zip', 'mybackup.zip');
    $this->ftp->close();
	}

	public function export($table = '', $type = 'xml', $table_list)
	{
		switch ($table) {
			case 'users':
				$data_array = $this->db
					->select('users.id, email, IF(active = 1, "Y", "N") as active', FALSE)
					->select('first_name, last_name, display_name, company, lang, gender, website')
					->join('profiles', 'profiles.user_id = users.id')
					->get('users')
					->result_array();
			break;
		
			case 'files':
				$data_array = $this->db
					->select('files.*, file_folders.name folder_name, file_folders.slug')
					->join('file_folders', 'files.folder_id = file_folders.id')
					->get('files')
					->result_array();				
			break;
		
			default:
				$data_array = $this->db
					->get($table)
					->result_array();			
			break;
		}
		force_download($table.'.'.$type, $this->format->factory($data_array)->{'to_'.$type}());
	}
}