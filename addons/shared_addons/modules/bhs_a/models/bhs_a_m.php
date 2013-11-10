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
class Bhs_a_m extends MY_Model
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
		if ( ! empty($params['gender']))
		{
		    $this->db->where('gender', $params['gender']);
		    $this->session->set_userdata('on_search','true');
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
    
    public function get_familyplanning_montlyreport($y)
    {
            $query = $this->db->query("
                SELECT  
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 11 and client_type = 'CU' and (YEAR(FROM_UNIXTIME(date_started)) = $y) ) as fstr_cu,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 11 and client_type = 'NA' and (YEAR(FROM_UNIXTIME(date_started)) = $y) ) as fstr_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 11 and ( client_type != 'NA' and client_type != 'CU' ) and (YEAR(FROM_UNIXTIME(date_started)) = $y) ) as fstr_others,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 11 and (YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as fstr_dropout,

                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 10 and client_type = 'CU' and (YEAR(FROM_UNIXTIME(date_started)) = $y) ) as mstr_cu,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 10 and client_type = 'NA' and (YEAR(FROM_UNIXTIME(date_started)) = $y) ) as mstr_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 10 and ( client_type != 'NA' and client_type != 'CU' ) and (YEAR(FROM_UNIXTIME(date_started)) = $y) ) as mstr_others,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 10 and (YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as mstr_dropout,

                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 4 and client_type = 'CU' and (YEAR(FROM_UNIXTIME(date_started)) = $y) ) as pills_cu,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 4 and client_type = 'NA' and (YEAR(FROM_UNIXTIME(date_started)) = $y) ) as pills_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 4 and ( client_type != 'NA' and client_type != 'CU' ) and (YEAR(FROM_UNIXTIME(date_started)) = $y) ) as pills_others,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 4 and (YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as pills_dropout        
                FROM dual
                    ");
            $rows = $query->row_array();
            return $rows;
    }
    public function get_medicare_montlyreport($y)
    {
            $query = $this->db->query("
                SELECT
                    ( SELECT count(*) FROM default_prenatals WHERE (YEAR(FROM_UNIXTIME(prenatal_tri3_v2)) = $y) or (YEAR(FROM_UNIXTIME(prenatal_tri3_v3)) = $y)) as total1,
                    ( SELECT count(*) FROM default_prenatals WHERE ((YEAR(FROM_UNIXTIME(tt1)) = $y) and (tt2 > 0 || tt3 > 0 || tt4 > 0 || tt5 > 0 ) ) || ((YEAR(FROM_UNIXTIME(tt2)) = $y) and (tt3 > 0 || tt4 > 0 || tt5 > 0 || tt1 > 0 ) ) || ((YEAR(FROM_UNIXTIME(tt3)) = $y) and (tt4 > 0 || tt5 > 0 || tt2 > 0 || tt1 > 0 ) ) || ((YEAR(FROM_UNIXTIME(tt4)) = $y) and (tt5 > 0 || tt3 > 0 || tt2 > 0 || tt1 > 0 ) ) || ((YEAR(FROM_UNIXTIME(tt5)) = $y) and (tt4 > 0 || tt3 > 0 || tt2 > 0 || tt1 > 0 ) ) ) as total2,
                    ( SELECT count(*) FROM default_prenatals WHERE (tt2 > 0 and ((YEAR(FROM_UNIXTIME(tt2)) = $y) || (YEAR(FROM_UNIXTIME(tt3)) = $y) || (YEAR(FROM_UNIXTIME(tt4)) = $y) || (YEAR(FROM_UNIXTIME(tt5)) = $y)   ) ) ) as total3,
                    ( SELECT count(*) FROM default_prenatals WHERE  (YEAR(FROM_UNIXTIME(iron6_date)) = $y) and iron5_date > 0 and iron4_date > 0 and iron3_date  > 0 and iron2_date > 0 and iron1_date > 0 ) as total4,
                    ( SELECT count(*) FROM default_prenatals WHERE  (YEAR(FROM_UNIXTIME(date_given_vit_a)) = $y) ) as total5, 
                    ( SELECT count(*) FROM default_postpartum WHERE ((YEAR(FROM_UNIXTIME(iron2_date)) = $y) and (iron1_date > 0 || iron3_date > 0)) ) as total6,
                    ( SELECT count(*) FROM default_postpartum WHERE (iron1_tabs > 0 and iron2_tabs > 0 and iron3_tabs > 0) and (YEAR(FROM_UNIXTIME(iron3_date)) = $y) ) as total7,
                    ( SELECT count(*) FROM default_postpartum WHERE (YEAR(FROM_UNIXTIME(vitamin_a)) = $y ) ) as total8,
                    ( SELECT count(*) FROM `default_postpartum` WHERE ((YEAR(FROM_UNIXTIME(delivery)) = $y ) and (( breastfeeding - delivery ) / 60 ) < 61) ) as total9
                FROM dual
                ");
            $rows = $query->row_array();
            return $rows;
    }
    public function get_childcare_montlyreport($m, $y)
    {       
        $query = $this->db->query("
        SELECT
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_bcg)) = $m and YEAR(FROM_UNIXTIME(a.im_bcg)) = $y) ) as bcgF_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_bcg)) = $m and YEAR(FROM_UNIXTIME(a.im_bcg)) = $y) ) as bcgM_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_hepa_b_at_birth)) = $m and YEAR(FROM_UNIXTIME(a.im_hepa_b_at_birth)) = $y) ) as hepa_b_at_birthF_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_hepa_b_at_birth)) = $m and YEAR(FROM_UNIXTIME(a.im_hepa_b_at_birth)) = $y) ) as hepa_b_at_birthM_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_pentavalent_1)) = $m and YEAR(FROM_UNIXTIME(a.im_pentavalent_1)) = $y) ) as pentavalent_1F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_pentavalent_1)) = $m and YEAR(FROM_UNIXTIME(a.im_pentavalent_1)) = $y) ) as pentavalent_1M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_pentavalent_2)) = $m and YEAR(FROM_UNIXTIME(a.im_pentavalent_2)) = $y) ) as pentavalent_2F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_pentavalent_2)) = $m and YEAR(FROM_UNIXTIME(a.im_pentavalent_2)) = $y) ) as pentavalent_2M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_pentavalent_3)) = $m and YEAR(FROM_UNIXTIME(a.im_pentavalent_3)) = $y) ) as pentavalent_3F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_pentavalent_3)) = $m and YEAR(FROM_UNIXTIME(a.im_pentavalent_3)) = $y) ) as pentavalent_3M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_dpt1)) = $m and YEAR(FROM_UNIXTIME(a.im_dpt1)) = $y) ) as dpt1F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_dpt1)) = $m and YEAR(FROM_UNIXTIME(a.im_dpt1)) = $y) ) as dpt1M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_dpt2)) = $m and YEAR(FROM_UNIXTIME(a.im_dpt2)) = $y) ) as dpt2F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_dpt2)) = $m and YEAR(FROM_UNIXTIME(a.im_dpt2)) = $y) ) as dpt2M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_dpt3)) = $m and YEAR(FROM_UNIXTIME(a.im_dpt3)) = $y) ) as dpt3F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_dpt3)) = $m and YEAR(FROM_UNIXTIME(a.im_dpt3)) = $y) ) as dpt3M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_polio1)) = $m and YEAR(FROM_UNIXTIME(a.im_polio1)) = $y) ) as opv1F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_polio1)) = $m and YEAR(FROM_UNIXTIME(a.im_polio1)) = $y) ) as opv1M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_polio2)) = $m and YEAR(FROM_UNIXTIME(a.im_polio2)) = $y) ) as opv2F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_polio2)) = $m and YEAR(FROM_UNIXTIME(a.im_polio2)) = $y) ) as opv2M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_polio3)) = $m and YEAR(FROM_UNIXTIME(a.im_polio3)) = $y) ) as opv3F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_polio3)) = $m and YEAR(FROM_UNIXTIME(a.im_polio3)) = $y) ) as opv3M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_rotarix1)) = $m and YEAR(FROM_UNIXTIME(a.im_rotarix1)) = $y) ) as rotarix1F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_rotarix1)) = $m and YEAR(FROM_UNIXTIME(a.im_rotarix1)) = $y) ) as rotarix1M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_rotarix2)) = $m and YEAR(FROM_UNIXTIME(a.im_rotarix2)) = $y) ) as rotarix2F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_rotarix2)) = $m and YEAR(FROM_UNIXTIME(a.im_rotarix2)) = $y) ) as rotarix2M_total,      	
        	
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_hepa_b1_with_in)) = $m and YEAR(FROM_UNIXTIME(a.im_hepa_b1_with_in)) = $y) ) as hepa_b1_with_inF_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_hepa_b1_with_in)) = $m and YEAR(FROM_UNIXTIME(a.im_hepa_b1_with_in)) = $y) ) as hepa_b1_with_inM_total,
        	
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_hepa_b1_more_than)) = $m and YEAR(FROM_UNIXTIME(a.im_hepa_b1_more_than)) = $y) ) as hepa_b1_more_thanF_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_hepa_b1_more_than)) = $m and YEAR(FROM_UNIXTIME(a.im_hepa_b1_more_than)) = $y) ) as hepa_b1_more_thanM_total,
        	
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_hepa_b2)) = $m and YEAR(FROM_UNIXTIME(a.im_hepa_b2)) = $y) ) as hepa_b2F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_hepa_b2)) = $m and YEAR(FROM_UNIXTIME(a.im_hepa_b2)) = $y) ) as hepa_b2M_total,
        	        		
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_hepa_b3)) = $m and YEAR(FROM_UNIXTIME(a.im_hepa_b3)) = $y) ) as hepa_b3F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_hepa_b3)) = $m and YEAR(FROM_UNIXTIME(a.im_hepa_b3)) = $y) ) as hepa_b3M_total,
                		
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_anti_measles)) = $m and YEAR(FROM_UNIXTIME(a.im_anti_measles)) = $y) ) as im_anti_measlesF_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_anti_measles)) = $m and YEAR(FROM_UNIXTIME(a.im_anti_measles)) = $y) ) as im_anti_measlesM_total,
        	
            ( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_mmr)) = $m and YEAR(FROM_UNIXTIME(a.im_mmr)) = $y) ) as mmrF_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_mmr)) = $m and YEAR(FROM_UNIXTIME(a.im_mmr)) = $y) ) as mmrM_total,
          	
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_fully)) = $m and YEAR(FROM_UNIXTIME(a.im_fully)) = $y)) as im_fully_under1F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_fully)) = $m and YEAR(FROM_UNIXTIME(a.im_fully)) = $y)) as im_fully_under1M_total, 
        
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(b.dob)) = $m and YEAR(FROM_UNIXTIME(b.dob)) = $y)) as livebirths_F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(b.dob)) = $m and YEAR(FROM_UNIXTIME(b.dob)) = $y)) as livebirths_M_total, 
          
            ( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.cp_date_assess)) = $m and YEAR(FROM_UNIXTIME(a.cp_date_assess)) = $y)) as child_protected_F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.cp_date_assess)) = $m and YEAR(FROM_UNIXTIME(a.cp_date_assess)) = $y)) as child_protected_M_total, 
          
            ( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.nb_referral_date)) = $m and YEAR(FROM_UNIXTIME(a.nb_referral_date)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(a.date_added), FROM_UNIXTIME(b.dob)) >= 6) as infant_6mos_seen_F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.nb_referral_date)) = $m and YEAR(FROM_UNIXTIME(a.nb_referral_date)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(a.date_added), FROM_UNIXTIME(b.dob)) >= 6 ) as infant_6mos_seen_M_total, 
            
            ( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.bf_6)) = $m and YEAR(FROM_UNIXTIME(a.bf_6)) = $y)) as breastfeed_6th_F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.bf_6)) = $m and YEAR(FROM_UNIXTIME(a.bf_6)) = $y)) as breastfeed_6th_M_total,                   
            
            ( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.nb_referral_date)) = $m and YEAR(FROM_UNIXTIME(a.nb_referral_date)) = $y) ) as  referred_nb_screeningF_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.nb_referral_date)) = $m and YEAR(FROM_UNIXTIME(a.nb_referral_date)) = $y) ) as referred_nb_screeningM_total    
        
          FROM dual
        ");
        $rows = $query->row_array();
        return $rows;
    }
    
    public function get_sc_vita_m1($m, $y)
    {       
        $query = $this->db->query("
        SELECT
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and c.six_months = 1 and (c.diagnosis!='A' and c.diagnosis!='B' and c.diagnosis!='C' and c.diagnosis!='D' and c.diagnosis!='E' and c.diagnosis!='F' and c.diagnosis!='G' and c.diagnosis!='H' and c.diagnosis!='I' and c.diagnosis!='J') and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as child_611_vitA_F_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and c.six_months = 1 and (c.diagnosis!='A' and c.diagnosis!='B' and c.diagnosis!='C' and c.diagnosis!='D' and c.diagnosis!='E' and c.diagnosis!='F' and c.diagnosis!='G' and c.diagnosis!='H' and c.diagnosis!='I' and c.diagnosis!='J') and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as child_611_vitA_M_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and c.twelve_months = 1 and (c.diagnosis!='A' and c.diagnosis!='B' and c.diagnosis!='C' and c.diagnosis!='D' and c.diagnosis!='E' and c.diagnosis!='F' and c.diagnosis!='G' and c.diagnosis!='H' and c.diagnosis!='I' and c.diagnosis!='J') and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as child_1259_vitA_F_total,          
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and c.twelve_months = 1 and (c.diagnosis!='A' and c.diagnosis!='B' and c.diagnosis!='C' and c.diagnosis!='D' and c.diagnosis!='E' and c.diagnosis!='F' and c.diagnosis!='G' and c.diagnosis!='H' and c.diagnosis!='I' and c.diagnosis!='J') and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as child_1259_vitA_M_total,         
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and c.sixty_months = 1 and (c.diagnosis!='A' and c.diagnosis!='B' and c.diagnosis!='C' and c.diagnosis!='D' and c.diagnosis!='E' and c.diagnosis!='F' and c.diagnosis!='G' and c.diagnosis!='H' and c.diagnosis!='I' and c.diagnosis!='J') and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as child_6079_vitA_F_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and c.sixty_months = 1 and (c.diagnosis!='A' and c.diagnosis!='B' and c.diagnosis!='C' and c.diagnosis!='D' and c.diagnosis!='E' and c.diagnosis!='F' and c.diagnosis!='G' and c.diagnosis!='H' and c.diagnosis!='I' and c.diagnosis!='J') and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as child_6079_vitA_M_total,
                   
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and c.six_months = 1 and  (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as child_sick_611_F_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and c.six_months = 1 and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as child_sick_611_M_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and c.twelve_months = 1 and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as child_sick_1259_F_total,        	
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and c.twelve_months = 1 and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as child_sick_1259_M_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and c.sixty_months = 1 and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as child_sick_6079_F_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and c.sixty_months = 1 and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) ) as child_sick_6079_M_total,
            
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and c.six_months = 1 and (MONTH(FROM_UNIXTIME(c.date_given)) = $m and YEAR(FROM_UNIXTIME(c.date_given)) = $y)) as child_sick_611_vitA_F_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and c.six_months = 1 and (MONTH(FROM_UNIXTIME(c.date_given)) = $m and YEAR(FROM_UNIXTIME(c.date_given)) = $y)) as child_sick_611_vitA_M_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and c.twelve_months = 1 and (MONTH(FROM_UNIXTIME(c.date_given)) = $m and YEAR(FROM_UNIXTIME(c.date_given)) = $y)) as child_sick_1259_vitA_F_total,        	
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and c.twelve_months = 1 and (MONTH(FROM_UNIXTIME(c.date_given)) = $m and YEAR(FROM_UNIXTIME(c.date_given)) = $y)) as child_sick_1259_vitA_M_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and c.sixty_months = 1 and (MONTH(FROM_UNIXTIME(c.date_given)) = $m and YEAR(FROM_UNIXTIME(c.date_given)) = $y)) as child_sick_6079_vitA_F_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and c.sixty_months = 1 and (MONTH(FROM_UNIXTIME(c.date_given)) = $m and YEAR(FROM_UNIXTIME(c.date_given)) = $y)) as child_sick_6079_vitA_M_total
                  
          FROM dual
        ");
        $rows = $query->row_array();
        return $rows;
    }
    
    public function get_sc_lbw_m1($m, $y)
    {       
        $query = $this->db->query("
        SELECT               
            (SELECT count(*) FROM default_children_under_one b JOIN default_clients a ON b.client_id = a.id WHERE a.gender = 'female' and (b.ms_a_age_months >= 2 and b.ms_a_age_months <= 6) and b.ms_iron_birth_weight < 1.13 and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as infant_26mos_lbw_F_total,
            (SELECT count(*) FROM default_children_under_one b JOIN default_clients a ON b.client_id = a.id WHERE a.gender = 'male' and (b.ms_a_age_months >= 2 and b.ms_a_age_months <= 6) and b.ms_iron_birth_weight < 1.13 and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as infant_26mos_lbw_M_total,
            (SELECT count(*) FROM default_children_under_one b JOIN default_clients a ON b.client_id = a.id WHERE a.gender = 'female' and (b.ms_a_age_months >= 2 and b.ms_a_age_months <= 6) and b.ms_iron_birth_weight < 1.13 and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as infant_26mos_lbw_iron_F_total,        	
            (SELECT count(*) FROM default_children_under_one b JOIN default_clients a ON b.client_id = a.id WHERE a.gender = 'male' and (b.ms_a_age_months >= 2 and b.ms_a_age_months <= 6) and b.ms_iron_birth_weight < 1.13 and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as infant_26mos_lbw_iron_M_total
        
          FROM dual
        ");
        $rows = $query->row_array();
        return $rows;
    }   
    
    public function get_sc_anemic_m1($m, $y)
    {       
        $query = $this->db->query("
        SELECT               
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id JOIN default_sc_anemic_children_iron d ON b.id = d.sc_id where a.gender = 'female' and (d.anemic_age >= 2 and d.anemic_age <= 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as anemic_259_F_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id JOIN default_sc_anemic_children_iron d ON b.id = d.sc_id where a.gender = 'male' and (d.anemic_age >= 2 and d.anemic_age <= 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as anemic_259_M_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id JOIN default_sc_anemic_children_iron d ON b.id = d.sc_id where a.gender = 'female' and (d.anemic_age >= 2 and d.anemic_age <= 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as anemic_259_iron_F_total,        	
            ( SELECT count(*) FROM default_sc b JOIN default_sc_vitamin_a c ON b.id = c.sc_id JOIN default_clients a ON b.client_id = a.id JOIN default_sc_anemic_children_iron d ON b.id = d.sc_id where a.gender = 'male' and (d.anemic_age >= 2 and d.anemic_age <= 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as anemic_259_iron_M_total
        
          FROM dual
        ");
        $rows = $query->row_array();
        return $rows;
    }
    
    public function get_sc_diarrhea_m1($m, $y)
    {       
        $query = $this->db->query("
        SELECT                   
            ( SELECT count(*) FROM default_sc b JOIN default_sc_diarrhea_cases e ON b.id = e.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and (e.diarrhea_age >= 0 and e.diarrhea_age < 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as diarrhea_059_F_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_diarrhea_cases e ON b.id = e.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and (e.diarrhea_age >= 0 and e.diarrhea_age < 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as diarrhea_059_M_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_diarrhea_cases e ON b.id = e.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and (e.diarrhea_age >= 0 and e.diarrhea_age < 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(e.ort)) = $m and YEAR(FROM_UNIXTIME(e.ort)) = $y)) as diarrhea_059_ort_F_total,        	
            ( SELECT count(*) FROM default_sc b JOIN default_sc_diarrhea_cases e ON b.id = e.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and (e.diarrhea_age >= 0 and e.diarrhea_age < 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(e.ort)) = $m and YEAR(FROM_UNIXTIME(e.ort)) = $y)) as diarrhea_059_ort_M_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_diarrhea_cases e ON b.id = e.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and (e.diarrhea_age >= 0 and e.diarrhea_age < 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(e.ors)) = $m and YEAR(FROM_UNIXTIME(e.ors)) = $y)) as diarrhea_059_ors_F_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_diarrhea_cases e ON b.id = e.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and (e.diarrhea_age >= 0 and e.diarrhea_age < 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(e.ors)) = $m and YEAR(FROM_UNIXTIME(e.ors)) = $y)) as diarrhea_059_ors_M_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_diarrhea_cases e ON b.id = e.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and (e.diarrhea_age >= 0 and e.diarrhea_age < 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(e.ors_zinc)) = $m and YEAR(FROM_UNIXTIME(e.ors_zinc)) = $y)) as diarrhea_059_ors_zinc_F_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_diarrhea_cases e ON b.id = e.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and (e.diarrhea_age >= 0 and e.diarrhea_age < 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(e.ors_zinc)) = $m and YEAR(FROM_UNIXTIME(e.ors_zinc)) = $y)) as diarrhea_059_ors_zinc_M_total
                            
          FROM dual
        ");
        $rows = $query->row_array();
        return $rows;
    }
    
    public function get_sc_pneumonia_m1($m, $y)
    {       
        $query = $this->db->query("
        SELECT               
            ( SELECT count(*) FROM default_sc b JOIN default_sc_pneumonia_cases f ON b.id = f.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and (f.pneumonia_age >= 0 and f.pneumonia_age <= 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as pneumonia_059_F_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_pneumonia_cases f ON b.id = f.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and (f.pneumonia_age >= 0 and f.pneumonia_age <= 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y)) as pneumonia_059_M_total,
            ( SELECT count(*) FROM default_sc b JOIN default_sc_pneumonia_cases f ON b.id = f.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'female' and (f.pneumonia_age >= 0 and f.pneumonia_age <= 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(f.date_given_treatment)) = $m and YEAR(FROM_UNIXTIME(f.date_given_treatment)) = $y)) as pneumonia_059_tx_F_total,        	
            ( SELECT count(*) FROM default_sc b JOIN default_sc_pneumonia_cases f ON b.id = f.sc_id JOIN default_clients a ON b.client_id = a.id where a.gender = 'male' and (f.pneumonia_age >= 0 and f.pneumonia_age <= 59) and (MONTH(FROM_UNIXTIME(b.date_added)) = $m and YEAR(FROM_UNIXTIME(b.date_added)) = $y) and (MONTH(FROM_UNIXTIME(f.date_given_treatment)) = $m and YEAR(FROM_UNIXTIME(f.date_given_treatment)) = $y)) as pneumonia_059_tx_M_total
        
          FROM dual
        ");
        $rows = $query->row_array();
        return $rows;
    }
    
}