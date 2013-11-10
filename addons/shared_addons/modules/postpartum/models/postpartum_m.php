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
class Postpartum_m extends MY_Model
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
               
      $data =array(
            'client_id' => $input['client_id'],
            'remarks' => $input['remarks'] ? $input['remarks'] : null,
            'delivery' => $input['delivery'] ? $input['delivery'] : null,
            'visits_day' => $input['visits_day'] ? $input['visits_day'] : null,
            'visits_week' => $input['visits_week'] ? $input['visits_week'] : null,
            'breastfeeding' => $input['breastfeeding'] ? $input['breastfeeding'] : null,
            'iron1_date' => $input['iron1_date'] ? $input['iron1_date'] : null,
            'iron1_tabs' => $input['iron1_tabs'] ? $input['iron1_tabs'] : null,
            'iron2_date' => $input['iron2_date'] ? $input['iron2_date'] : null,
            'iron2_tabs' => $input['iron2_tabs'] ? $input['iron2_tabs'] : null,
            'iron3_date' => $input['iron3_date'] ? $input['iron3_date'] : null,
            'iron3_tabs' => $input['iron3_tabs'] ? $input['iron3_tabs'] : null,
            'vitamin_a' => $input['vitamin_a'] ? $input['vitamin_a'] : null,
            'date_added' => now()
            );
    
      $this->db->insert('postpartum', $data); 
               
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
              
      $data =array(
            'remarks' => $input['remarks'] ? $input['remarks'] : null,
            'delivery' => $input['delivery'] ? $input['delivery'] : null,
            'visits_day' => $input['visits_day'] ? $input['visits_day'] : null,
            'visits_week' => $input['visits_week'] ? $input['visits_week'] : null,
            'breastfeeding' => $input['breastfeeding'] ? $input['breastfeeding'] : null,
            'iron1_date' => $input['iron1_date'] ? $input['iron1_date'] : null,
            'iron1_tabs' => $input['iron1_tabs'] ? $input['iron1_tabs'] : null,
            'iron2_date' => $input['iron2_date'] ? $input['iron2_date'] : null,
            'iron2_tabs' => $input['iron2_tabs'] ? $input['iron2_tabs'] : null,
            'iron3_date' => $input['iron3_date'] ? $input['iron3_date'] : null,
            'iron3_tabs' => $input['iron3_tabs'] ? $input['iron3_tabs'] : null,
            'vitamin_a' => $input['vitamin_a'] ? $input['vitamin_a'] : null
            );
                
        $this->db->where('id', $id);
        $this->db->update('postpartum', $data); 

       
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
		$this->db->delete('postpartum', array('id' => $id)); 
								
		return true;
	}
	
	
    public function count_by($params = array())
    {

        $this->db->from('postpartum');
        $this->db->join('clients', 'clients.id = postpartum.client_id');
    
        if ( ! empty($params['client_id']))
		{
		    $this->db->where('client_id', $params['client_id']);
		}	
         if ( ! empty($params['barangay']))
		{
		    $this->db->where('barangay_id', $params['barangay']);
		}   
        if ( ! empty($params['by_year']))
        {
            $this->db->where('year(from_unixtime(date_added))', $params['by_year']);
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
        
        if ( ! empty($params['postpartum_filters']))
		{		    
            if ($params['postpartum_filters']=="ppv2")
            {
                $this->db
                    ->where('postpartum.visits_day !=','')
                    ->where('postpartum.visits_week !=','');
            }
            if ($params['postpartum_filters']=="vita")
            {
                $this->db->where('postpartum.vitamin_a !=','');
            }                      
            if ($params['postpartum_filters']=="iron")
            {
                $this->db
                    ->where('postpartum.iron1_date !=','')
                    ->where('postpartum.iron2_date !=','')
                    ->where('postpartum.iron3_date !=','');
            }
            if ($params['postpartum_filters']=="bf")
            {
                $this->db->where('postpartum.breastfeeding !=','');       
            } 
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
            $this->db->select('*, postpartum.id as postpartum_id');
            $this->db->from('postpartum');
            $this->db->join('clients', 'clients.id = postpartum.client_id');
        	
            if ( ! empty($params['client_id']))
      		{
      		    $this->db->where('client_id', $params['client_id']);
      		}
          
              if ( ! empty($params['barangay']))
		{
		    $this->db->where('barangay_id', $params['barangay']);
		}          
            
            if ( ! empty($params['by_year']))
            {
                $this->db->where('year(from_unixtime(date_added))', $params['by_year']);
            }
      		
            if ( ! empty($params['gender']))
      		{
      		    $this->db->where('gender', $params['gender']);
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
            
            if ( ! empty($params['postpartum_filters']))
    		{		    
                if ($params['postpartum_filters']=="ppv2")
                {
                    $this->db
                        ->where('postpartum.visits_day !=','')
                        ->where('postpartum.visits_week !=','');
                }
                if ($params['postpartum_filters']=="vita")
                {
                    $this->db->where('postpartum.vitamin_a !=','');
                }                      
                if ($params['postpartum_filters']=="iron")
                {
                    $this->db
                        ->where('postpartum.iron1_date !=','')
                        ->where('postpartum.iron2_date !=','')
                        ->where('postpartum.iron3_date !=','');
                }
                if ($params['postpartum_filters']=="bf")
                {
                    $this->db->where('postpartum.breastfeeding !=','');       
                } 
    		}
		
      		$query = $this->db->get();
      		
      		return $query->result();
		
		
    }
    
    public function get_postpartum_details($id = null)
    {
          $this->db->select('*');
          $this->db->from('postpartum');
      		$this->db->where('id', $id);

      		$query = $this->db->get();
      		
      		return $query->row();
    }
    
    public function check_postpartum_exist($client_id = null)
    {
          $this->db->select('id');
          $this->db->from('postpartum');
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
        $this->db->from('postpartum');
		    $this->db->where('client_id', $client_id);
		    return $this->db->count_all_results();
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