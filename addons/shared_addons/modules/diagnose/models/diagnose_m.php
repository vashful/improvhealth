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
class Diagnose_m extends MY_Model
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

          $this->db->select('*');
          $this->db->from('clients');
      		$this->db->where('id', $input['client_id']);

      		$query = $this->db->get();
      		
      		$row = $query->row();
                
          $data =array(
                  'client_id' => $input['client_id'],
                  'diagnosis_id' => $input['diagnosis_id'],
                  'referrer_id' => $input['referrer_id'],   
                  'therapy' => $input['therapy'],
                  'client_age' => floor((time() - $row->dob)/31556926),
                  'date_diagnose' => $input['date_diagnose'],
                  'date_added' => now(),
                  'added_by' => $this->session->userdata("user_id")
          );
        
      $this->db->insert('diagnoses', $data); 
      
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
          $this->db->select('*');
          $this->db->from('clients');
      		$this->db->where('id', $input['client_id']);

      		$query = $this->db->get();
      		
      		$row = $query->row();
                
          $data =array(
                  'client_id' => $input['client_id'],
                  'diagnosis_id' => $input['diagnosis_id'],
                  'referrer_id' => $input['referrer_id'],   
                  'therapy' => $input['therapy'],
                  'client_age' => floor((time() - $row->dob)/31556926),
                  'date_diagnose' => $input['date_diagnose'],
                  'last_update' => now(),
                  'last_update_by' => $this->session->userdata("user_id")
          );
        
      $this->db->where('id', $id);
      $this->db->update('diagnoses', $data); 
       
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
    $this->db->delete('diagnoses', array('id' => $id)); 
		return true;
	}
	
	
    public function count_by($params = array())
    {

    $this->db->from('diagnoses');
    $this->db->join('clients', 'clients.id = diagnoses.client_id');
    
    if ( ! empty($params['client_id']))
		{
		    $this->db->where('client_id', $params['client_id']);
		}
		
		if ( ! empty($params['diagnosis']))
		{
		    $this->db->where('diagnosis_id', $params['diagnosis']);
		}
		
		if ( ! empty($params['age']))
		{
		    $this->db->where('client_age', $params['age']);
		}
		
  	if ( ! empty($params['gender']))
		{
		    $this->db->where('gender', $params['gender']);
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
          $this->db->select('*, diagnoses.id as diagnoses_id');
          $this->db->from('diagnoses');
          $this->db->join('clients', 'clients.id = diagnoses.client_id','left');
          $this->db->order_by('last_name', 'ASC');
          $this->db->order_by('date_diagnose', 'DESC');
          $this->db->limit($limit[0], $limit[1]);
          
          if ( ! empty($params['client_id']))
      		{
      		    $this->db->where('client_id', $params['client_id']);
      		}
      		
      		if ( ! empty($params['diagnosis']))
      		{
      		    $this->db->where('diagnosis_id', $params['diagnosis']);
      		}
		      
          if ( ! empty($params['age']))
      		{
      		    $this->db->where('client_age', $params['age']);
      		}
      		
        	if ( ! empty($params['gender']))
      		{
      		    $this->db->where('gender', $params['gender']);
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
    
      
     
    public function get_all_diagnose($id = null)
    {
          $this->db->select('diagnoses.date_diagnose, diagnoses.therapy, diagnosis.name, referrer.firstname, referrer.lastname, referrer.middlename,');
          $this->db->from('diagnoses');
          $this->db->join('diagnosis', 'diagnoses.diagnosis_id = diagnosis.id','left');
          $this->db->join('referrer', 'diagnoses.referrer_id = referrer.id','left');
      		$this->db->where('diagnoses.client_id', $id);
          $this->db->order_by('date_diagnose', 'ASC');
      		$query = $this->db->get();
      		
      		return $query->result();
    }
    
    public function get_diagnose_details($id = null)
    {
          $this->db->select('*, diagnoses.id as diagnoses_id');
          $this->db->from('diagnoses');
          $this->db->join('clients', 'clients.id = diagnoses.client_id','left');
      		$this->db->where('diagnoses.id', $id);

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
        $this->db->from('diagnoses');
		    $this->db->where('client_id', $client_id);
		    return $this->db->count_all_results();
    }
    
    public function get_record($client_id)
    {
    return 1;
    }
    
    
    
}