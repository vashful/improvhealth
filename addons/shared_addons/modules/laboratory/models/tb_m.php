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
class Tb_m extends MY_Model
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

		return $this->db->get('diagnose');
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
               
                $insert = false;
                /*$vitamin_insert = false;
                $anemic_insert = false;
                $diarrhea_insert = false;
                $pneumonia_insert = false;*/
                
                /*if(!empty($input['six_months']) || !empty($input['twelve_months']) || !empty($input['sixty_months']) || !empty($input['diagnosis']))
                {
                $insert = true;
                $vitamin_insert = true;
                }
                
                if(!empty($input['anemic_age']) || !empty($input['date_started']))
                {
                $insert = true;
                $anemic_insert = true;
                }
                
                if(!empty($input['diarrhea_age']) || !empty($input['ort']) || !empty($input['ors']) || !empty($input['ors_zinc']))
                {
                $insert = true;
                $diarrhea_insert = true;
                }
                
                if(!empty($input['pneumonia_age']) || !empty($input['date_given_treatment']))
                {
                $insert = true;
                $pneumonia_insert = true;
                }*/
                
                if(!empty($input['remarks']))
                {
                $insert = true;
                }
                
                if($insert)
                {                
                  $data =array(
                        'client_id' => $input['client_id'],
                        'remarks' => $input['remarks'],
                        'diagnosis' => $input['diagnosis'],
                        /* for indicator
                        A = TB symptomatics who underwent DSSM
                        B = Smear (+) discovered
                        C = New smear (+) case initiated treatment
                        D = New smear (+) case cured
                        E = Smear (+) retreatment case initiated treatment
                        F = Smear (+) retreatment case got cured
                        */
                        //'date_added' => now()
                        'date_added' => $input['date_given']
                        );
                
                  $this->db->insert('tb', $data); 

                  $tb_id = $this->db->insert_id();
                }
                else
                {
                  return false;
                }
                
                /*if($vitamin_insert)
                {
                  $data =array(
                        'sc_id' => $sc_id,
                        'six_months' => $input['six_months'] ? $input['six_months'] : '0',
                        'twelve_months' => $input['twelve_months'] ? $input['twelve_months'] : '0',
                        'sixty_months' => $input['sixty_months'] ? $input['sixty_months'] : '0',
                        'diagnosis' => $input['diagnosis'],
                        'date_given' => $input['date_given'] ? $input['date_given'] : null 
                        );
                
                  $this->db->insert('sc_vitamin_a', $data); 
                }
                
                if($anemic_insert)
                {
                  $data =array(
                        'sc_id' => $sc_id,
                        'anemic_age' => $input['anemic_age'],
                        'date_started' => $input['date_started'] ? $input['date_started'] : null, 
                        'date_completed' => $input['date_completed'] ? $input['date_completed'] : null
                        );
                
                  $this->db->insert('sc_anemic_children_iron', $data); 
                }
                
                if($diarrhea_insert)
                {
                  $data =array(
                        'sc_id' => $sc_id,
                        'diarrhea_age' => $input['diarrhea_age'],
                        'ort' => $input['ort'] ? $input['ort'] : null, 
                        'ors' => $input['ors'] ? $input['ors'] : null,
                        'ors_zinc' => $input['ors_zinc'] ? $input['ors_zinc'] : null
                        );
                
                  $this->db->insert('sc_diarrhea_cases', $data); 
                }
                
                if($pneumonia_insert)
                {
                  $data =array(
                        'sc_id' => $sc_id,
                        'pneumonia_age' => $input['pneumonia_age'],
                        'date_given_treatment' => $input['date_given_treatment'] ? $input['date_given_treatment'] : null
                        );
                
                  $this->db->insert('sc_pneumonia_cases', $data); 
                }*/
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
                               
//               print_r($input);die();
                  $data =array(
                        'remarks' => $input['remarks'],
                        'diagnosis' => $input['diagnosis'],
                        'date_added' => ($input['date_given'])?$input['date_given']:now()
                        );
                
                  $this->db->where('id', $id);
                  $this->db->update('tb', $data); 
       
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
		$this->db->delete('tb', array('id' => $id)); 								
		return true;
	}
	
	
    public function count_by($params = array())
    {

    $this->db->from('tb');
    $this->db->join('clients', 'clients.id = tb.client_id');
    
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
	
  	if ( ! empty($params['cases']))
		{
    		    $this->db->where($params['cases'], '1');
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
    
    
    public function count_rows_on_table($table = 'tb', $field = 'id' ,$value = 0)
    {

    $this->db->from($table);
		$this->db->where($field, $value);

		return $this->db->count_all_results();
    }
    
    public function get_results($limit = array(),$params = array())
    {
        $this->db->select('*, tb.id as tb_id');
        $this->db->from('tb');
        $this->db->join('clients', 'clients.id = tb.client_id');
          
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
      	
    	if ( ! empty($params['cases']))
  		{
      		    $this->db->where($params['cases'], '1');
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
        if ( isset($params['except']) )
        {
        	$this->db->where_not_in('name', $params['except']);
        }
        
        $query = $this->db->get();
        
        return $query->result();
			
    }
    
    public function get_tb_details($id = null)
    {
          $this->db->select('*, tb.id as tb_id');
          $this->db->from('tb');
      		$this->db->where('tb.id', $id);

      		$query = $this->db->get();
      		
      		return $query->row();
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
        $this->db->from('tb');
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