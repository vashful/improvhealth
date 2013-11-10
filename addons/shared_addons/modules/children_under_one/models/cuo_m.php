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
class Cuo_m extends MY_Model
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
                $bf_1 = 0;
                $bf_2 = 0;
                $bf_3 = 0;
                $bf_4 = 0;
                $bf_5 = 0;                                                
                if(!empty($input['bf_1']))
                {
                $bf_1 = 1;
                }
                if(!empty($input['bf_2']))
                {
                $bf_2 = 1;
                }
                if(!empty($input['bf_3']))
                {
                $bf_3 = 1;
                }
                if(!empty($input['bf_4']))
                {
                $bf_4 = 1;
                }
                if(!empty($input['bf_5']))
                {
                $bf_5 = 1;
                }
                
      $data =array(
            'client_id' => $input['client_id'],
            'mother_name' => $input['mother_name'],
            'remarks' => $input['remarks'],
            'nb_referral_date' => $input['nb_referral_date'] ? $input['nb_referral_date'] : null,
            'nb_done_date' => $input['nb_done_date'] ? $input['nb_done_date'] : null,
            'cp_date_assess' => $input['cp_date_assess'] ? $input['cp_date_assess'] : null,
            'cp_tt_status' => $input['cp_tt_status'] ? $input['cp_tt_status'] : null,
            'ms_a_age_months' => $input['ms_a_age_months'] ? $input['ms_a_age_months'] : null,
            'ms_a_date_given' => $input['ms_a_date_given'] ? $input['ms_a_date_given'] : null,
            'ms_iron_birth_weight' => $input['ms_iron_birth_weight'],
            'ms_iron_date_started' => $input['ms_iron_date_started'] ? $input['ms_iron_date_started'] : null,
            'ms_iron_date_completed' => $input['ms_iron_date_completed'] ? $input['ms_iron_date_completed'] : null,
            'im_bcg' => $input['im_bcg'] ? $input['im_bcg'] : null,
            'im_hepa_b_at_birth' => $input['im_hepa_b_at_birth'] ? $input['im_hepa_b_at_birth'] : null,      
            'im_pentavalent_1' => $input['im_pentavalent_1'] ? $input['im_pentavalent_1'] : null,
            'im_pentavalent_2' => $input['im_pentavalent_2'] ? $input['im_pentavalent_2'] : null,
            'im_pentavalent_3' => $input['im_pentavalent_3'] ? $input['im_pentavalent_3'] : null,
            'im_dpt1' => $input['im_dpt1'] ? $input['im_dpt1'] : null,
            'im_dpt2' => $input['im_dpt2'] ? $input['im_dpt2'] : null,
            'im_dpt3' => $input['im_dpt3'] ? $input['im_dpt3'] : null,
            'im_polio1' => $input['im_polio1'] ? $input['im_polio1'] : null,
            'im_polio2' => $input['im_polio2'] ? $input['im_polio2'] : null,
            'im_polio3' => $input['im_polio3'] ? $input['im_polio3'] : null,
            'im_rotarix1' => $input['im_rotarix1'] ? $input['im_rotarix1'] : null,
            'im_rotarix2' => $input['im_rotarix2'] ? $input['im_rotarix2'] : null,
            'im_hepa_b1_with_in' => $input['im_hepa_b1_with_in'] ? $input['im_hepa_b1_with_in'] : null,
            'im_hepa_b1_more_than' => $input['im_hepa_b1_more_than'] ? $input['im_hepa_b1_more_than'] : null,
            'im_hepa_b2' => $input['im_hepa_b2'] ? $input['im_hepa_b2'] : null,
            'im_hepa_b3' => $input['im_hepa_b3'] ? $input['im_hepa_b3'] : null,
            'im_anti_measles' => $input['im_anti_measles'] ? $input['im_anti_measles'] : null,
            'im_mmr' => $input['im_mmr'] ? $input['im_mmr'] : null,
            'im_fully' => $input['im_fully'] ? $input['im_fully'] : null,
            'bf_1' => $bf_1 ? '1' : '0',
            'bf_2' => $bf_2 ? '1' : '0',
            'bf_3' => $bf_3 ? '1' : '0',
            'bf_4' => $bf_4 ? '1' : '0',
            'bf_5' => $bf_5 ? '1' : '0',
            'bf_6' => $input['bf_6'] ? $input['bf_6'] : null,
            'date_added' => now()
            );
    
      $this->db->insert('children_under_one', $data); 
               
      return true;          
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
              
      $bf_1 = 0;
      $bf_2 = 0;
      $bf_3 = 0;
      $bf_4 = 0;
      $bf_5 = 0;                                                
      if(!empty($input['bf_1']))
      {
      $bf_1 = 1;
      }
      if(!empty($input['bf_2']))
      {
      $bf_2 = 1;
      }
      if(!empty($input['bf_3']))
      {
      $bf_3 = 1;
      }
      if(!empty($input['bf_4']))
      {
      $bf_4 = 1;
      }
      if(!empty($input['bf_5']))
      {
      $bf_5 = 1;
      }
                
      $data =array(
            'client_id' => $input['client_id'],
            'mother_name' => $input['mother_name'],
            'remarks' => $input['remarks'],
            'nb_referral_date' => $input['nb_referral_date'] ? $input['nb_referral_date'] : null,
            'nb_done_date' => $input['nb_done_date'] ? $input['nb_done_date'] : null,
            'cp_date_assess' => $input['cp_date_assess'] ? $input['cp_date_assess'] : null,
            'cp_tt_status' => $input['cp_tt_status'] ? $input['cp_tt_status'] : null,
            'ms_a_age_months' => $input['ms_a_age_months'] ? $input['ms_a_age_months'] : null,
            'ms_a_date_given' => $input['ms_a_date_given'] ? $input['ms_a_date_given'] : null,
            'ms_iron_birth_weight' => $input['ms_iron_birth_weight'],
            'ms_iron_date_started' => $input['ms_iron_date_started'] ? $input['ms_iron_date_started'] : null,
            'ms_iron_date_completed' => $input['ms_iron_date_completed'] ? $input['ms_iron_date_completed'] : null,
            'im_bcg' => $input['im_bcg'] ? $input['im_bcg'] : null,
            'im_hepa_b_at_birth' => $input['im_hepa_b_at_birth'] ? $input['im_hepa_b_at_birth'] : null,      
            'im_pentavalent_1' => $input['im_pentavalent_1'] ? $input['im_pentavalent_1'] : null,
            'im_pentavalent_2' => $input['im_pentavalent_2'] ? $input['im_pentavalent_2'] : null,
            'im_pentavalent_3' => $input['im_pentavalent_3'] ? $input['im_pentavalent_3'] : null,
            'im_dpt1' => $input['im_dpt1'] ? $input['im_dpt1'] : null,
            'im_dpt2' => $input['im_dpt2'] ? $input['im_dpt2'] : null,
            'im_dpt3' => $input['im_dpt3'] ? $input['im_dpt3'] : null,
            'im_polio1' => $input['im_polio1'] ? $input['im_polio1'] : null,
            'im_polio2' => $input['im_polio2'] ? $input['im_polio2'] : null,
            'im_polio3' => $input['im_polio3'] ? $input['im_polio3'] : null,
            'im_rotarix1' => $input['im_rotarix1'] ? $input['im_rotarix1'] : null,
            'im_rotarix2' => $input['im_rotarix2'] ? $input['im_rotarix2'] : null,
            'im_hepa_b1_with_in' => $input['im_hepa_b1_with_in'] ? $input['im_hepa_b1_with_in'] : null,
            'im_hepa_b1_more_than' => $input['im_hepa_b1_more_than'] ? $input['im_hepa_b1_more_than'] : null,
            'im_hepa_b2' => $input['im_hepa_b2'] ? $input['im_hepa_b2'] : null,
            'im_hepa_b3' => $input['im_hepa_b3'] ? $input['im_hepa_b3'] : null,
            'im_anti_measles' => $input['im_anti_measles'] ? $input['im_anti_measles'] : null,
            'im_mmr' => $input['im_mmr'] ? $input['im_mmr'] : null,
            'im_fully' => $input['im_fully'] ? $input['im_fully'] : null,
            'bf_1' => $bf_1 ? '1' : '0',
            'bf_2' => $bf_2 ? '1' : '0',
            'bf_3' => $bf_3 ? '1' : '0',
            'bf_4' => $bf_4 ? '1' : '0',
            'bf_5' => $bf_5 ? '1' : '0',
            'bf_6' => $input['bf_6'] ? $input['bf_6'] : null,
            'date_added' => now()
            );
                
        $this->db->where('id', $id);
        $this->db->update('children_under_one', $data); 

       
      return true;          
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
		$this->db->delete('children_under_one', array('id' => $id)); 
								
		return true;
	}
	
	
    public function count_by($params = array())
    {

    $this->db->from('children_under_one');
    $this->db->join('clients', 'clients.id = children_under_one.client_id');
    
    if ( ! empty($params['barangay']))
		{
		    $this->db->where('barangay_id', $params['barangay']);
		}
    
  	if ( ! empty($params['gender']))
		{
		    $this->db->where('gender', $params['gender']);
		}
        
        if ( ! empty($params['by_year']))
		{
		    $this->db->where('year(from_unixtime(date_added))', $params['by_year']);
		}
        
        if ( ! empty($params['status']))
  		{
      		if ($params['status'] == "nb")
      		{
      		    $this->db->where('nb_done_date is not NULL');
      		}  		
      		if ($params['status'] == "cpab")
      		{
      		    $this->db->where('cp_tt_status is not NULL');
      		}
      		if ($params['status'] == "ms_a")
      		{
      		    $this->db->where('ms_a_date_given  is not NULL');
      		}
      		
      		if ($params['status'] == "ms_iron")
      		{
      		    $this->db->where('ms_iron_date_completed is not NULL');
      		}
      		
      		if ($params['status'] == "im_fully")
      		{
      		    $this->db->where('im_fully is not NULL');
      		}
      		
      		if ($params['status'] == "breastfed")
      		{
      		    $this->db->where('bf_6 is not NULL');
      		}
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

		return $this->db->count_all_results();
    }
    
    
    public function count_rows_on_table($table = 'sc', $field = 'id' ,$value = 0)
    {

    $this->db->from($table);
		$this->db->where($field, $value);

		return $this->db->count_all_results();
    }
    
    public function get_results($limit = array(),$params = array())
    {
          $this->db->select('*, children_under_one.id as cuo_id');
          $this->db->from('children_under_one');
          $this->db->join('clients', 'clients.id = children_under_one.client_id');
        	
          if ( ! empty($params['barangay']))
      		{
      		    $this->db->where('barangay_id', $params['barangay']);
      		}
    
          if ( ! empty($params['gender']))
      		{
      		    $this->db->where('gender', $params['gender']);
      		}
            
            if ( ! empty($params['by_year']))
    		{
    		    $this->db->where('year(from_unixtime(date_added))', $params['by_year']);
    		}
          	
        	if ( ! empty($params['status']))
      		{
          		if ($params['status'] == "nb")
          		{
          		    $this->db->where('nb_done_date is not NULL');
          		}  		
          		if ($params['status'] == "cpab")
          		{
          		    $this->db->where('cp_tt_status is not NULL');
          		}
          		if ($params['status'] == "ms_a")
          		{
          		    $this->db->where('ms_a_date_given  is not NULL');
          		}
          		
          		if ($params['status'] == "ms_iron")
          		{
          		    $this->db->where('ms_iron_date_completed is not NULL');
          		}
          		
          		if ($params['status'] == "im_fully")
          		{
          		    $this->db->where('im_fully is not NULL');
          		}
          		
          		if ($params['status'] == "breastfed")
          		{
          		    $this->db->where('bf_6 is not NULL');
          		}
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
          if (isset($params['except']))
      		{
      			$this->db->where_not_in('name', $params['except']);
      		}
		
      		$query = $this->db->get();
      		
      		return $query->result();
		
		
    }
    
    public function get_cuo_details($id = null)
    {
          $this->db->select('*');
          $this->db->from('children_under_one');
      		$this->db->where('id', $id);

      		$query = $this->db->get();
      		
      		return $query->row();
    }
    
    public function check_cuo_exist($client_id = null)
    {
          $this->db->select('id');
          $this->db->from('children_under_one');
      		$this->db->where('client_id', $client_id);

      		$query = $this->db->get();
      		
      	if ($query->num_rows() > 0)
        {
           $row = $query->row();
        
           return $row->id;
        } 
        else
           return false;
        
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
    
    public function check_record($client_id)
    {
    return false;
    }
    
    public function get_record($client_id)
    {
    return 1;
    }
    
    public function get_method()
    {
      $row = $this->db
      ->from('family_planning_methods')
			->get()
			->result();

      return $row;
    }
    
    
}