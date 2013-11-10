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
class Consultations_m extends MY_Model
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
          $client_age = floor((time() - $row->dob)/31556926);      
          $data =array(
                  'date_consultations' => $input['date_consultations'],
                  'client_id' => $input['client_id'],
                  'cc' => $input['cc'],
                  'wt' => $input['wt'],
                  'ht' => $input['ht'],
                  'bp' => $input['bp'],
                  'temp' => $input['temp'],
                  'pr' => $input['pr'],
                  'rr' => $input['rr'],
                  'objective' => $input['objective'],
                  'referrer_id' => $input['referrer_id'],   
                  'plan' => $input['plan'],
                  'client_age' => $client_age,
                  'date_added' => now(),
                  'added_by' => $this->session->userdata("user_id")
          );
        
      $this->db->insert('consultations', $data); 
      $consultation_id = $this->db->insert_id();
      
      foreach($input['assessment_ids'] as $assessment_id)
      {
          $data =array(
          'disease_id' => $assessment_id,
          'consultation_id' => $consultation_id, 
          'gender' =>  $row->gender,
          'client_age' => $client_age,
          'client_id' => $input['client_id'],
          'date_added' => $input['date_consultations']
          );
        
          $this->db->insert('assessment', $data); 
      }
      
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
          $client_age = floor((time() - $row->dob)/31556926);            
          $data =array(
                  'date_consultations' => $input['date_consultations'],
                  'client_id' => $input['client_id'],
                  'cc' => $input['cc'],
                  'wt' => $input['wt'],
                  'ht' => $input['ht'],
                  'bp' => $input['bp'],
                  'temp' => $input['temp'],
                  'pr' => $input['pr'],
                  'rr' => $input['rr'],
                  'objective' => $input['objective'],
                  'referrer_id' => $input['referrer_id'],   
                  'plan' => $input['plan'],
                  'client_age' => $client_age,
                  'last_update' => now(),
                  'last_update_by' => $this->session->userdata("user_id")
          );
        
      $this->db->where('id', $id);
      $this->db->update('consultations', $data); 
      
      $this->db->delete('assessment', array('consultation_id' => $id)); 
               
      foreach($input['assessment_ids'] as $assessment_id)
      {
          $data =array(
          'disease_id' => $assessment_id,
          'consultation_id' => $id, 
          'gender' =>  $row->gender,
          'client_age' => $client_age,
          'client_id' => $input['client_id'],
          'date_added' => $input['date_consultations']
          );
        
          $this->db->insert('assessment', $data); 
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
    $this->db->delete('consultations', array('id' => $id)); 
		return true;
	}
	
	
    public function count_by($params = array())
    {

    $this->db->from('consultations');
    $this->db->join('clients', 'clients.id = consultations.client_id');
    $this->db->join('assessment', 'assessment.consultation_id = consultations.id');
    
        if ( ! empty($params['client_id']))
		{
		    $this->db->where('clients.id', $params['client_id']);
		}
    
        if ( ! empty($params['barangay']))
		{
		    $this->db->where('barangay_id', $params['barangay']);
		}
        
        if ( ! empty($params['by_year']))
        {
            $this->db->where('year(from_unixtime(date_consultations))', $params['by_year']);
        }
		
		if ( ! empty($params['diseases']))
		{
		    $this->db->where('assessment.disease_id', $params['diseases']);
		}
		
		if ( ! empty($params['age']))
		{
		    $this->db->where('consultations.client_age', $params['age']);
		}
		
        if ( ! empty($params['gender']))
		{
		    $this->db->where('clients.gender', $params['gender']);
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
        $this->db->select("*, consultations.id as consultations_id
              , referrer.firstname,referrer.middlename, referrer.lastname, referrer.profession, clients.residence, clients.address");
        $this->db->from('consultations');
        $this->db->join('clients', 'clients.id = consultations.client_id','left');
        $this->db->join('assessment', 'assessment.consultation_id = consultations.id');
        $this->db->join('referrer', 'referrer.id = consultations.referrer_id','left');  
        $this->db->group_by('consultations.id');                                        
        $this->db->order_by('last_name', 'ASC');
        $this->db->order_by('date_consultations', 'DESC');
        $this->db->limit($limit[0], $limit[1]);
        
        if ( ! empty($params['client_id']))
        {
            $this->db->where('clients.id', $params['client_id']);
        }
        if ( ! empty($params['barangay']))
    		{
    		    $this->db->where('barangay_id', $params['barangay']);
    		}
        
        if ( ! empty($params['by_year']))
        {
            $this->db->where('year(from_unixtime(date_consultations))', $params['by_year']);
        }
      		
        if ( ! empty($params['diseases']))
        {
        $this->db->where('assessment.disease_id', $params['diseases']);
        }
        
        if ( ! empty($params['age']))
        {
            $this->db->where('consultations.client_age', $params['age']);
        }
        
        if ( ! empty($params['gender']))
        {
            $this->db->where('clients.gender', $params['gender']);
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
        $rows  = $query->result();
        $consultations = array();
        foreach($rows as $row )
        {
              $cons = new stdClass();
              $this->db->select('diseases.name');
              $this->db->from('diseases');
              $this->db->join('assessment', 'assessment.disease_id = diseases.id','left');
          		$this->db->where('assessment.consultation_id', $row->consultations_id);
          		$query = $this->db->get();
              $rows2  = $query->result();
              $diseases = array();
          		foreach($rows2 as $row2)
              {
                  $diseases[]= $row2->name;
              }              
              $cons->diseases =  implode(', ',$diseases);
              $cons->consultations_id =  $row->consultations_id;
              $cons->date_consultations =  $row->date_consultations;
              $cons->cc =  $row->cc;
              $cons->client_age =  $row->client_age; 
              $cons->date_consultations =  $row->date_consultations; 
              $cons->plan =  $row->plan;
              $cons->wt =  $row->wt;
              $cons->ht =  $row->ht;
              $cons->bp =  $row->bp;
              $cons->temp =  $row->temp;
              $cons->pr =  $row->pr;
              $cons->rr =  $row->rr;
              $cons->firstname =  $row->firstname;
              $cons->lastname =  $row->lastname;
              $cons->middlename =  $row->middlename;
              $cons->residence =  $row->residence;
              $cons->address =  $row->address;
              $cons->first_name =  $row->first_name;
              $cons->last_name =  $row->last_name;
              $cons->middle_name =  $row->middle_name;
              $cons->dob =  $row->dob;
              $cons->id =  $row->client_id;
              $cons->gender =  $row->gender;
              $cons->referrer_id =  $row->referrer_id;
              $consultations[] = $cons;
          }
          return $consultations;    	
    }  
    
    public function get_results_pdf($params = array())
    {
          $this->db->select("*, consultations.id as consultations_id
                  , referrer.firstname,referrer.middlename, referrer.lastname, referrer.profession");
          $this->db->from('consultations');
          $this->db->join('clients', 'clients.id = consultations.client_id','left');
          $this->db->join('assessment', 'assessment.consultation_id = consultations.id');
          $this->db->join('referrer', 'referrer.id = consultations.referrer_id','left');  
          $this->db->group_by('consultations.id');                                        
          $this->db->order_by('last_name', 'ASC');
          $this->db->order_by('date_consultations', 'DESC');
          
          if ( ! empty($params['client_id']))
      		{
      		    $this->db->where('clients.id', $params['client_id']);
      		}      
      		
      		if ( ! empty($params['diseases']))
      		{
      		    $this->db->where('assessment.disease_id', $params['diseases']);
      		}
		      
          if ( ! empty($params['age']))
      		{
      		    $this->db->where('consultations.client_age', $params['age']);
      		}
      		
        	if ( ! empty($params['gender']))
      		{
      		    $this->db->where('clients.gender', $params['gender']);
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
      		$rows  = $query->result();
          $consultations = array();
      		foreach($rows as $row )
          {
              $cons = new stdClass();
              $this->db->select('diseases.name');
              $this->db->from('diseases');
              $this->db->join('assessment', 'assessment.disease_id = diseases.id','left');
          		$this->db->where('assessment.consultation_id', $row->consultations_id);
          		$query = $this->db->get();
              $rows2  = $query->result();
              $diseases = array();
          		foreach($rows2 as $row2)
              {
                  $diseases[]= $row2->name;
              }
              $cons->diseases =  implode(', ',$diseases);
              $cons->consultations_id =  $row->consultations_id;
              $cons->date_consultations =  $row->date_consultations;
              $cons->cc =  $row->cc;
              $cons->client_age =  $row->client_age; 
              $cons->date_consultations =  $row->date_consultations; 
              $cons->plan =  $row->plan;
              $cons->firstname =  $row->firstname;
              $cons->lastname =  $row->lastname;
              $cons->middlename =  $row->middlename;
              $cons->first_name =  $row->first_name;
              $cons->last_name =  $row->last_name;
              $cons->middle_name =  $row->middle_name;
              $cons->dob =  $row->dob;
              $cons->id =  $row->client_id;
              $cons->gender =  $row->gender;
              $cons->referrer_id =  $row->referrer_id;
              $consultations[] = $cons;
          }
          return $consultations;    	
    }    
     
    public function get_all_consultations($id = null)
    {
          $this->db->select('consultations.id, consultations.date_consultations, consultations.plan, referrer.firstname, referrer.lastname, referrer.middlename,');
          $this->db->from('consultations');
          $this->db->join('referrer', 'consultations.referrer_id = referrer.id','left');
      		$this->db->where('consultations.client_id', $id);
          $this->db->order_by('date_consultations', 'ASC');
      		$query = $this->db->get();
      		$rows  = $query->result();
          $consultations = array();
      		foreach($rows as $row )
          {
              $cons = new stdClass();
              $this->db->select('diseases.name');
              $this->db->from('diseases');
              $this->db->join('assessment', 'assessment.disease_id = diseases.id','left');
          		$this->db->where('assessment.consultation_id', $row->id);
          		$query = $this->db->get();
              $rows2  = $query->result();
              $diseases = array();
          		foreach($rows2 as $row2)
              {
                  $diseases[]= $row2->name;
              }
              $cons->diseases =  implode(', ',$diseases);
              $cons->id =  $row->id;
              $cons->date_consultations =  $row->date_consultations;
              $cons->plan =  $row->plan;
              $cons->firstname =  $row->firstname;
              $cons->lastname =  $row->lastname;
              $cons->middlename =  $row->middlename;
              $consultations[] = $cons;
          }
          return $consultations;    
    }
    
    public function get_consultations_details($id = null)
    {
          $this->db->select('*, consultations.id as consultations_id');
          $this->db->from('consultations');
          $this->db->join('clients', 'clients.id = consultations.client_id','left');
      		$this->db->where('consultations.id', $id);

      		$query = $this->db->get();
      		
      		return $query->row();
    }
    
    public function get_diseases($id = null)
    {
          $this->db->select('assessment.disease_id');
          $this->db->from('assessment');
      		$this->db->where('assessment.consultation_id', $id);

      		$query = $this->db->get();
      		//echo $this->db->last_query();
          //exit();
          if($query->num_rows()>0)
          {
      		foreach($query->result() as $row)
          {
            $assessment[] = $row->disease_id; 
          }
          return $assessment;
          }
          else
          {
          $assessment[]=0;
          return $assessment;
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
        $this->db->from('consultations');   
		    $this->db->where('client_id', $client_id);
		    return $this->db->count_all_results();
    }
    
    public function get_record($client_id)
    {
    return 1;
    }
    
    public function get_referrer($id)
    {
          $this->db->select("*");
          $this->db->from('referrer');
          $this->db->where('id',$id);                                        
          $this->db->limit(1);
          $query = $this->db->get();
      	  return $query->row_array();        
    }
     
}