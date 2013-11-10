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
class Family_m extends MY_Model
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
                if(!empty($input['drop_out_reason']))
		{
                        $drop = $input['drop_out_reason'];
                        $drop_date = now();
                }
                else
                {
                        $drop = NULL;
                        $drop_date = NULL;
                }
      
                $data =array(
                        'client_id' => $input['client_id'],
                        'client_type' => $input['client_type'],
                        'previous_method_id' => $input['previous_method_id'],
                        'method_id' => $input['method_id'],
                        'date_started' => now(),
                        'drop_out_reason' => $drop,
                        'drop_out_date' => $drop_date,
                        'remarks' => $input['remarks']
                );
        
      $this->db->insert('family_planning', $data); 
      
      if($input['client_type'] == 'NA')
      {
           $category = 1; 
      }
      elseif($input['client_type'] == 'CM' || $input['client_type'] == 'CC' || $input['client_type'] == 'RS')
      {
           $category = 2; 
      }
      elseif($input['client_type'] == 'CU')
      {
           $category = 4; 
      }
      else
      {
           $category = 3; 
      }
      
        $fp_id = $this->db->insert_id();
      
        $data =array(
                'fp_id'	=> $fp_id,
                'category' => $category,
                'service_date' => now(),
                'accomplished_date' =>  now(),
                'method_id' =>  $input['method_id']
        );
        
        $this->db->insert('family_planning_visits', $data); 
      
        $data =array(
                'fp_id'	=> $fp_id,
                'category' => $category,
                'service_date'	=> $input['service_date'],
                'method_id' =>  $input['method_id']
        );
        
        $this->db->insert('family_planning_visits', $data); 
        
        $data =array(
                'fp_id'	=> $fp_id,
                'category' => $category,
                'method_id' =>  $input['method_id']
        );
        
        $this->db->insert('family_planning_visits', $data); 
        
        $data =array(
                'fp_id'	=> $fp_id,
                'category' => $category,
                'method_id' =>  $input['method_id']
        );    
        
        $this->db->insert('family_planning_visits', $data);     

        $data =array(
                'fp_id'	=> $fp_id,
                'category' => $category,
                'method_id' =>  $input['method_id']
        ); 
        
        $this->db->insert('family_planning_visits', $data); 

        $data =array(
                'fp_id'	=> $fp_id,
                'category' => $category,
                'method_id' =>  $input['method_id']
        ); 
        
        $this->db->insert('family_planning_visits', $data); 

        $data =array(
                'fp_id'	=> $fp_id,
                'category' => $category,
                'method_id' =>  $input['method_id']
        );  
        
        $this->db->insert('family_planning_visits', $data); 
                               
        $data =array(
                'fp_id'	=> $fp_id,
                'category' => $category,
                'method_id' =>  $input['method_id']
        ); 
        
        $this->db->insert('family_planning_visits', $data); 

        $data =array(
                'fp_id'	=> $fp_id,
                'category' => $category,
                'method_id' =>  $input['method_id']
        ); 
        
        $this->db->insert('family_planning_visits', $data); 

        $data =array(
                'fp_id'	=> $fp_id,
                'category' => $category,
                'method_id' =>  $input['method_id']
        );
        
        $this->db->insert('family_planning_visits', $data); 

        $data =array(
                'fp_id'	=> $fp_id,
                'category' => $category,
                'method_id' =>  $input['method_id']
        );                        
        
        $this->db->insert('family_planning_visits', $data); 
        
        $data =array(
                'fp_id'	=> $fp_id,
                'category' => $category,
                'method_id' =>  $input['method_id']
        );                        
        
        $this->db->insert('family_planning_visits', $data);       
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
	    if(!empty($input['drop_out_reason']))
		  {
          $drop = $input['drop_out_reason'];
          if(!empty($input['drop_out_date']))
    		  {              
              $drop_date = strtotime($input['drop_out_date']);
          }
          else
          {
              $drop_date = now();
          }
      }else
      {
          $drop = NULL;
          $drop_date = NULL;
      }
      
      if($input['complete'] == '1')
           $completed = 1; 
      else
           $completed = 0; 
           
      
      $data =array(
			'drop_out_reason' 		=> $drop,
			'drop_out_date' 				=> $drop_date,
			'remarks' 		=> $input['remarks'],
			'completed'				=>  $completed
        );
        
      $this->db->where('id', $id);
      $this->db->update('family_planning', $data); 
      
      if($input['client_type'] == 'NA')
      {
           $category = 1; 
      }
      elseif($input['client_type'] == 'CM' || $input['client_type'] == 'CC' || $input['client_type'] == 'RS')
      {
           $category = 2; 
      }
      elseif($input['client_type'] == 'CU')
      {
           $category = 4; 
      }
      
      for($i=1;$i <=12; $i++)
      {
      $data =array(
		  'fp_id'				=> $id,
			'category'				=> $category,
			'service_date'			=> $input['service_date_'.$i] ? strtotime($input['service_date_'.$i]) : NULL,
			'accomplished_date' => $input['accomplished_date_'.$i] ? strtotime($input['accomplished_date_'.$i]) : NULL,
			'method_id' 		=>  $input['method_id']
        );
      $this->db->where('id', $input['fp_visits_id_'.$i]);
      $this->db->update('family_planning_visits', $data);   
      }

       
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
		$this->db->delete('family_planning', array('id' => $id)); 
		$this->db->delete('family_planning_visits', array('fp_id' => $id)); 
		return true;
	}
	
	
    public function count_by($params = array())
    {

    $this->db->from('family_planning');
    $this->db->join('clients', 'clients.id = family_planning.client_id');
    
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
        $this->db->where('year(from_unixtime(date_started))', $params['by_year']);
    }
		
  	if ( ! empty($params['gender']))
	{
	    $this->db->where('gender', $params['gender']);
	}
	
  	if ( ! empty($params['status']))
	{
		if ($params['status'] == "completed")
		{
		    $this->db->where('completed', 1);
		}  		
		if ($params['status'] == "drop")
		{
		    $this->db->where('drop_out_date >', 0);
		}
		if ($params['status'] == "normal")
		{
		    $this->db->where('drop_out_date is NULL');
		    $this->db->where('completed', 0);
		}
    }
    if ( ! empty($params['method']))
		{
		    $this->db->where('method_id', $params['method']);
		}
		
		if ( ! empty($params['client_type']))
		{
		    $this->db->where('client_type', $params['client_type']);
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
    
    public function get_results($limit = array(),$params = array())
    {
        $this->db->select('*, family_planning.id as fp_id');
        $this->db->from('family_planning');
        $this->db->join('clients', 'clients.id = family_planning.client_id','left');
        $this->db->order_by('last_name', 'ASC');
        $this->db->limit($limit[0], $limit[1]);
        
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
            $this->db->where('year(from_unixtime(date_started))', $params['by_year']);
        }
        
        if ( ! empty($params['gender']))
        {
            $this->db->where('gender', $params['gender']);
        }
      	
    	if ( ! empty($params['status']))
  		{
      		if ($params['status'] == "completed")
      		{
      		    $this->db->where('completed', 1);
      		}  		
      		if ($params['status'] == "drop")
      		{
      		    $this->db->where('drop_out_date >', 0);
      		}
      		if ($params['status'] == "normal")
      		{
      		    $this->db->where('drop_out_date is NULL');
      		    $this->db->where('completed', 0);
      		}
        }
        if ( ! empty($params['method']))
        {
            $this->db->where('method_id', $params['method']);
        }
        
        if ( ! empty($params['client_type']))
        {
            $this->db->where('client_type', $params['client_type']);
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
        if ( isset($params['except']) )
        {
        	$this->db->where_not_in('name', $params['except']);
        }
        
        $query = $this->db->get();
        
        return $query->result();
		
		
    }
    
    public function get_fp_details($id = null)
    {
          $this->db->select('*, family_planning.id as fp_id');
          $this->db->from('family_planning');
          $this->db->join('clients', 'clients.id = family_planning.client_id','left');
      		$this->db->where('family_planning.id', $id);

      		$query = $this->db->get();
      		
      		return $query->row();
    }
    
    
    public function get_fp_visits($id = null)
    {
          $this->db->select('*');
          $this->db->from('family_planning_visits');
      		$this->db->where('fp_id', $id);

      		$query = $this->db->get();
      		
      		return $query->result();
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
        $this->db->from('family_planning');
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