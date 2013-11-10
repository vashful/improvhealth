<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Group model
 *
 * @author Phil Sturgeon - PyroCMS Dev Team
 * @package PyroCMS
 * @subpackage Groups module
 * @category Modules
 *
 */
class Clients_m extends MY_Model
{
	/**
	 * Check a rule based on it's role
	 *
	 * @access public
	 * @param string $role The role
	 * @param array $location
	 * @return mixed
	 */
	public function check_rule_by_role($role, $location)
	{
		// No more checking to do, admins win
		if ( $role == 1 )
		{
			return TRUE;
		}

		// Check the rule based on whatever parts of the location we have
		if ( isset($location['module']) )
		{
			 $this->db->where('(module = "'.$location['module'].'" or module = "*")');
		}

		if ( isset($location['controller']) )
		{
			 $this->db->where('(controller = "'.$location['controller'].'" or controller = "*")');
		}

		if ( isset($location['method']) )
		{
			 $this->db->where('(method = "'.$location['method'].'" or method = "*")');
		}

		// Check which kind of user?
		$this->db->where('g.id', $role);

		$this->db->from('permissions p');
		$this->db->join('groups as g', 'g.id = p.group_id');

		$query = $this->db->get();

		return $query->num_rows() > 0;
	}

	/**
	 * Return an array of groups
	 *
	 * @access public
	 * @param array $params Optional parameters
	 * @return array
	 */
	public function get_all($params = array())
	{
		if ( isset($params['except']) )
		{
			$this->db->where_not_in('name', $params['except']);
		}

		return parent::get_all();
	}

	/**
	 * Add a group
	 *
	 * @access public
	 * @param array $input The data to insert
	 * @return array
	 */
	public function insert($input = array())
	{
		  
      $image['female'] = array ('default_female_black.jpg','default_female_white.jpg');
      $image['male'] = array ('default_male_black.jpg','default_male_white.jpg');
      $index = rand(0, 1);

      return parent::insert(array(
		  'serial_number'				=> trim($input['serial_number']),
			'form_number'				=> $input['form_number'],
			'photo'				=> $image[$input['gender']][$index],
			'first_name'				=> trim(ucwords(strtolower($input['first_name']))),
			'last_name'			=> trim(ucwords(strtolower($input['last_name']))),
			'middle_name'				=>  trim(ucwords(strtolower($input['middle_name']))),
			'gender' 		=> $input['gender'],
			'dob' 				=> $input['dob'],
			'email' 				=> $input['email'],
			'phonenumber' 				=> trim($input['phonenumber']),
			'bloodtype' 				=> $input['bloodtype'],
			'philhealth' 				=> trim($input['philhealth']),
			'philhealth_type' 				=> $input['philhealth_type'],
			'philhealth_sponsor' 				=> $input['philhealth_sponsor'],
			'relation' 		=> $input['relation'],
			'history'				=> $input['history'],
			'registration_date' 		=> now(),
			'last_update' 		=> now(),
			'facility'		=> $input['facility'],
			'residence'		=> ucwords(strtolower($input['residence'])),
			'address'		=> ucwords(strtolower($input['address'])),
			'barangay_id'		=> $input['barangay_id'],
			'city_id'		=> $input['city_id'],
			'province_id'		=> $input['province_id'],
			'region_id'		=> $input['region_id'],
			'last_user_trans'		=> $this->current_user->id,
			'ip_address' 				=> $this->input->ip_address()
        ));
	}

	/**
	 * Update a group
	 *
	 * @access public
	 * @param int $id The ID of the role
	 * @param array $input The data to update
	 * @return array
	 */
	public function update($id = 0, $input = array())
	{
		  $image['female'] = array ('default_female_black.jpg','default_female_white.jpg');
      $image['male'] = array ('default_male_black.jpg','default_male_white.jpg');
      $index = rand(0, 1);
      
    return parent::update($id, array(
			'serial_number'				=> $input['serial_number'],
			'form_number'				=> $input['form_number'],
      'photo'				=> $image[$input['gender']][$index],
			'first_name'				=> ucwords(strtolower($input['first_name'])),
			'last_name'			=> ucwords(strtolower($input['last_name'])),
			'middle_name'				=>  ucwords(strtolower($input['middle_name'])),
			'gender' 		=> $input['gender'],
			'dob' 				=> $input['dob'],
			'email' 				=> $input['email'],
			'phonenumber' 				=> $input['phonenumber'],
			'bloodtype' 				=> $input['bloodtype'],
			'philhealth' 				=> $input['philhealth'],
			'philhealth_type' 				=> $input['philhealth_type'],
			'philhealth_sponsor' 				=> $input['philhealth_sponsor'],
			'relation' 		=> $input['relation'],
			'history'				=> $input['history'],
			'facility'		=> $input['facility'],     
			'residence'		=> ucwords(strtolower($input['residence'])),
			'address'		=> ucwords(strtolower($input['address'])),
			'barangay_id'		=> $input['barangay_id'],
			'city_id'		=> $input['city_id'],
			'province_id'		=> $input['province_id'],
			'region_id'		=> $input['region_id'],
			'last_update' 		=> now(),
			'last_user_trans'		=> $this->current_user->id,
			'ip_address' 				=> $this->input->ip_address()
        ));
	}

	/**
	 * Delete a group
	 *
	 * @access public
	 * @param int $id The ID of the group to delete
	 * @return
	 */
	public function delete($id = 0)
	{
    return parent::delete($id);
	}
	
	
    public function count_by($params = array())
    {
    $this->db->from($this->_table);
    $this->session->set_userdata('on_search','');
    $this->session->set_userdata('search_keyword','');
    if ( ! empty($params['barangay']))
		{
		    $this->db->where('barangay_id', $params['barangay']);
		}
          
		if ( ! empty($params['gender']))
		{
		    $this->db->where('gender', $params['gender']);
		    $this->session->set_userdata('on_search','true');
		}
        
        if ( ! empty($params['by_year']))
		{
		    $this->db->where('year(from_unixtime(registration_date))', $params['by_year']);
		}
		
		if ( ! empty($params['age']))
		{
		    $this->db->where('age', $params['age']);
		    $this->session->set_userdata('on_search','true');
		}

		if ( ! empty($params['name']))
		{
		    $this->db
				->or_like('last_name', trim($params['name']))
				->or_like('first_name', trim($params['name']))
				->or_like('middle_name', trim($params['name']))
				->or_like('serial_number', trim($params['name']))
				->or_like('form_number', trim($params['name']));
				$this->session->set_userdata('on_search','true');
				$this->session->set_userdata('search_keyword',$params['name']);
		}

		return $this->db->count_all_results();
    }

    public function get_many_by($params = array())
    {
    if ( ! empty($params['barangay']))
		{
		    $this->db->where('barangay_id', $params['barangay']);
		}
    
		if ( ! empty($params['by_year']))
		{
		    $this->db->where('year(from_unixtime(registration_date))', $params['by_year']);
		}
        if ( ! empty($params['gender']))
		{
		    $this->db->where('gender', $params['gender']);
		}
		
		if ( ! empty($params['age']))
		{
		    $this->db->where('age', $params['age']);
		}

		if ( ! empty($params['name']))
		{
		    $this->db
				->or_like('last_name', trim($params['name']))
				->or_like('first_name', trim($params['name']))
				->or_like('middle_name', trim($params['name']))
				->or_like('serial_number', trim($params['name']))
				->or_like('form_number', trim($params['name']));
		}           
    
		return $this->get_all();
    }
    
}