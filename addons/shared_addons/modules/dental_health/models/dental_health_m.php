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
class Dental_health_m extends MY_Model
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
                        'orally_fit' => $input['orally_fit'] ? $input['orally_fit'] : 'N',
                        'date_given_bohc' => $input['date_given_bohc'] ? $input['date_given_bohc'] : null,
                        'bohc_services' => $input['bohc_services'] ? $input['bohc_services'] : null,
                        'remarks' => $input['remarks'] ? $input['remarks'] : null
                );
    
                $this->db->insert('dental_health', $data);
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
                        'orally_fit' => $input['orally_fit'] ? $input['orally_fit'] : 'N',
                        'date_given_bohc' => $input['date_given_bohc'] ? $input['date_given_bohc'] : null,
                        'bohc_services' => $input['bohc_services'] ? $input['bohc_services'] : null,
                        'remarks' => $input['remarks'] ? $input['remarks'] : null
                );
                
                $this->db->where('id', $id);
                $this->db->update('dental_health', $data);      
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
		$this->db->delete('dental_health', array('id' => $id)); 
								
		return true;
	}
	
	
    public function count_by($params = array())
    {

    $this->db->from('dental_health');
    $this->db->join('clients', 'clients.id = dental_health.client_id');
    
        if ( ! empty($params['client_id']))
		{
		    $this->db->where('client_id', $params['client_id']);
		}	
        
        if ( ! empty($params['gender']))
        {
            $this->db->where('gender', $params['gender']);
        }
        
        if ( ! empty($params['by_year']))
		{
		    $this->db->where('year(from_unixtime(date_given_bohc))', $params['by_year']);
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
            $this->db->select('*, dental_health.id as dental_health_id');
            $this->db->from('dental_health');
            $this->db->join('clients', 'clients.id = dental_health.client_id');
            
            if ( ! empty($params['client_id']))
            {
                $this->db->where('client_id', $params['client_id']);
            }
            
            if ( ! empty($params['gender']))
            {
                $this->db->where('gender', $params['gender']);
            }
            
            if ( ! empty($params['by_year']))
            {
                $this->db->where('year(from_unixtime(date_given_bohc))', $params['by_year']);
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
    
    public function get_dental_health_details($id = null)
    {
        $this->db->select('*');
        $this->db->from('dental_health');
        $this->db->where('id', $id);
        
        $query = $this->db->get();
        
        return $query->row();
    }
    
    public function check_dental_health_exist($client_id = null)
    {
        $this->db->select('id');
        $this->db->from('dental_health');
        $this->db->where('client_id', $client_id);
        
        $query = $this->db->get();
      		
        if ($query->num_rows() > 0)
        {
                $row = $query->row();
                return $row->id;
        } 
        else
        {
                return false;
        }   
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
        $this->db->from('dental_health');
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