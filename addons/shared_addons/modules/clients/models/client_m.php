<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author 		Kobusoft Team
 * @package 	BHS
 * @subpackage 	Client Module
 * @since		v1.0
 *
 */
class Client_m extends MY_Model
{
    function __construct()
    {
		parent::__construct();
		
		$this->clients_table = $this->db->dbprefix('clients');
    }

    // Create a new client
    public function add($input = array())
    {
      return parent::insert(array(
      'family_id'				=> 1,
			'firstname'				=> ucwords(strtolower($input['first_name'])),
			'lastname'			=> ucwords(strtolower($input['last_name'])),
			'middle_name'				=>  ucwords(strtolower($input['middle_name'])),
			'age' 		=> $input['age'],
			'gender' 		=> $input['gender'],
			'birthday' 				=> $input['birth_date'],
			'relation' 		=> $input['relation'],
			'consultation_history'				=> $input['history'],
			'registration_date' 		=> now(),
			'facility'		=> $input['facility'],
			'last_user_trans'		=> $this->current_user->id,
			'ip_address' 				=> $this->input->ip_address()
        ));
    }


}