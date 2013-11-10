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
class Prenatals_m extends MY_Model
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
                    'client_id'				=> $input['client_id'],
                    'last_menstrual_period'			=> $input['last_menstrual_period'] ? $input['last_menstrual_period'] : null,
                    'gravida'			        => $input['gravida'] ? $input['gravida'] : 0,
                    'para'				        => $input['para'] ? $input['para'] : 0,
                    'term'				        => $input['term'] ? $input['term'] : 0,
                    'abortion'				        => $input['abortion'] ? $input['abortion'] : 0,
                    'live'				        => $input['live'] ? $input['live'] : 0,
                    'estimated_date_confinement' 		=> $input['estimated_date_confinement'] ? $input['estimated_date_confinement'] : null,
                    'prenatal_tri1_v1'                       => $input['prenatal_tri1_v1'] ? $input['prenatal_tri1_v1'] : null,
                    'prenatal_tri1_v2'                       => $input['prenatal_tri1_v2'] ? $input['prenatal_tri1_v2'] : null,
                    'prenatal_tri1_v3'                       => $input['prenatal_tri1_v3'] ? $input['prenatal_tri1_v3'] : null,
                    'prenatal_tri2_v1'                       => $input['prenatal_tri2_v1'] ? $input['prenatal_tri2_v1'] : null,
                    'prenatal_tri2_v2'                       => $input['prenatal_tri2_v2'] ? $input['prenatal_tri2_v2'] : null,
                    'prenatal_tri2_v3'                       => $input['prenatal_tri2_v3'] ? $input['prenatal_tri2_v3'] : null,
                    'prenatal_tri3_v1'                       => $input['prenatal_tri3_v1'] ? $input['prenatal_tri3_v1'] : null,
                    'prenatal_tri3_v2'                       => $input['prenatal_tri3_v2'] ? $input['prenatal_tri3_v2'] : null,
                    'prenatal_tri3_v3'                       => $input['prenatal_tri3_v3'] ? $input['prenatal_tri3_v3'] : null,
                    'tetanus_status' 		        => $input['tetanus_status'] ? $input['tetanus_status'] : null,
                    'tt1'                                   => $input['tt1'] ? $input['tt1'] : null,
                    'tt2'                                   => $input['tt2'] ? $input['tt2'] : null,
                    'tt3'                                   => $input['tt3'] ? $input['tt3'] : null,
                    'tt4'                                   => $input['tt4'] ? $input['tt4'] : null,
                    'tt5'                                   => $input['tt5'] ? $input['tt5'] : null,
                    'iron1_date'                            => $input['iron1_date'] ? $input['iron1_date'] : null,
                    'iron2_date'                            => $input['iron2_date'] ? $input['iron2_date'] : null,
                    'iron3_date'                            => $input['iron3_date'] ? $input['iron3_date'] : null,
                    'iron4_date'                            => $input['iron4_date'] ? $input['iron4_date'] : null,
                    'iron5_date'                            => $input['iron5_date'] ? $input['iron5_date'] : null,
                    'iron6_date'                            => $input['iron6_date'] ? $input['iron6_date'] : null,
                    'iron1_number'                          => $input['iron1_number'] ? $input['iron1_number'] : null,
                    'iron2_number'                          => $input['iron2_number'] ? $input['iron2_number'] : null,
                    'iron3_number'                          => $input['iron3_number'] ? $input['iron3_number'] : null,
                    'iron4_number'                          => $input['iron4_number'] ? $input['iron4_number'] : null,
                    'iron5_number'                          => $input['iron5_number'] ? $input['iron5_number'] : null,
                    'iron6_number'                          => $input['iron6_number'] ? $input['iron6_number'] : null,
                    'date_given_vit_a' 			=> $input['date_given_vit_a'] ? $input['date_given_vit_a'] : null,  
                    'risk_code' 		                => $input['risk_code'],
                    'risk_date_detected' 		        => $input['risk_date_detected'] ? $input['risk_date_detected'] : null,
                    'pregnancy_date_terminated' 		=> $input['pregnancy_date_terminated'] ? $input['pregnancy_date_terminated'] : null,
                    'pregnancy_outcome' 		        => $input['pregnancy_outcome'],
                    'livebirths_birth_weight'		=> $input['livebirths_birth_weight'] ? $input['livebirths_birth_weight'] : null,
                    'livebirths_place_delivery'		=> $input['livebirths_place_delivery'] ? $input['livebirths_place_delivery'] : null,
                    'livebirths_type_delivery'		=> $input['livebirths_type_delivery'] ? $input['livebirths_type_delivery'] : null,
                    'livebirths_attended_by	'		=> $input['livebirths_attended_by'],
                    'date_added'		            => now(),
                    'remarks'		                => $input['remarks'] ? $input['remarks'] : null		
                );
                
                $this->db->insert('prenatals', $data);
	
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
	public function update($pnid = 0, $input = array())
	{
           
    
            $data =array(
                    'last_menstrual_period'			=> $input['last_menstrual_period'] ? $input['last_menstrual_period'] : null,
                    'gravida'			        => $input['gravida'] ? $input['gravida'] : 0,
                    'para'				        => $input['para'] ? $input['para'] : 0,
                    'term'				        => $input['term'] ? $input['term'] : 0,
                    'abortion'				        => $input['abortion'] ? $input['abortion'] : 0,
                    'live'				        => $input['live'] ? $input['live'] : 0,
                    'estimated_date_confinement' 		=> $input['estimated_date_confinement'] ? $input['estimated_date_confinement'] : null,
                    'prenatal_tri1_v1'                       => $input['prenatal_tri1_v1'] ? $input['prenatal_tri1_v1'] : null,
                    'prenatal_tri1_v2'                       => $input['prenatal_tri1_v2'] ? $input['prenatal_tri1_v2'] : null,
                    'prenatal_tri1_v3'                       => $input['prenatal_tri1_v3'] ? $input['prenatal_tri1_v3'] : null,
                    'prenatal_tri2_v1'                       => $input['prenatal_tri2_v1'] ? $input['prenatal_tri2_v1'] : null,
                    'prenatal_tri2_v2'                       => $input['prenatal_tri2_v2'] ? $input['prenatal_tri2_v2'] : null,
                    'prenatal_tri2_v3'                       => $input['prenatal_tri2_v3'] ? $input['prenatal_tri2_v3'] : null,
                    'prenatal_tri3_v1'                       => $input['prenatal_tri3_v1'] ? $input['prenatal_tri3_v1'] : null,
                    'prenatal_tri3_v2'                       => $input['prenatal_tri3_v2'] ? $input['prenatal_tri3_v2'] : null,
                    'prenatal_tri3_v3'                       => $input['prenatal_tri3_v3'] ? $input['prenatal_tri3_v3'] : null,
                    'tetanus_status' 		        => $input['tetanus_status'] ? $input['tetanus_status'] : null,
                    'tt1'                                   => $input['tt1'] ? $input['tt1'] : null,
                    'tt2'                                   => $input['tt2'] ? $input['tt2'] : null,
                    'tt3'                                   => $input['tt3'] ? $input['tt3'] : null,
                    'tt4'                                   => $input['tt4'] ? $input['tt4'] : null,
                    'tt5'                                   => $input['tt5'] ? $input['tt5'] : null,
                    'iron1_date'                            => $input['iron1_date'] ? $input['iron1_date'] : null,
                    'iron2_date'                            => $input['iron2_date'] ? $input['iron2_date'] : null,
                    'iron3_date'                            => $input['iron3_date'] ? $input['iron3_date'] : null,
                    'iron4_date'                            => $input['iron4_date'] ? $input['iron4_date'] : null,
                    'iron5_date'                            => $input['iron5_date'] ? $input['iron5_date'] : null,
                    'iron6_date'                            => $input['iron6_date'] ? $input['iron6_date'] : null,
                    'iron1_number'                          => $input['iron1_number'] ? $input['iron1_number'] : null,
                    'iron2_number'                          => $input['iron2_number'] ? $input['iron2_number'] : null,
                    'iron3_number'                          => $input['iron3_number'] ? $input['iron3_number'] : null,
                    'iron4_number'                          => $input['iron4_number'] ? $input['iron4_number'] : null,
                    'iron5_number'                          => $input['iron5_number'] ? $input['iron5_number'] : null,
                    'iron6_number'                          => $input['iron6_number'] ? $input['iron6_number'] : null,
                    'date_given_vit_a' 			=> $input['date_given_vit_a'] ? $input['date_given_vit_a'] : null,  
                    'risk_code' 		                => $input['risk_code'],
                    'risk_date_detected' 		        => $input['risk_date_detected'] ? $input['risk_date_detected'] : null,
                    'pregnancy_date_terminated' 		=> $input['pregnancy_date_terminated'] ? $input['pregnancy_date_terminated'] : null,
                    'pregnancy_outcome' 		        => $input['pregnancy_outcome'],
                    'livebirths_birth_weight'		=> $input['livebirths_birth_weight'] ? $input['livebirths_birth_weight'] : null,
                    'livebirths_place_delivery'		=> $input['livebirths_place_delivery'] ? $input['livebirths_place_delivery'] : null,
                    'livebirths_type_delivery'		=> $input['livebirths_type_delivery'] ? $input['livebirths_type_delivery'] : null,
                    'livebirths_attended_by	'		=> $input['livebirths_attended_by'],
                    'remarks'		                => $input['remarks'] ? $input['remarks'] : null		
            );
            
            $this->db->where('id', $pnid);
            $this->db->update('prenatals', $data);
        
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
		return parent::delete($id);
	}
	
	
    public function count_by($params = array())
    {
                
                $this->db->select('a.id as pn_id, a.client_id, a.last_menstrual_period, a.gravida, a.para, a.estimated_date_confinement, a.prenatal_tri1_v1, a.prenatal_tri1_v2, a.prenatal_tri1_v3, a.prenatal_tri2_v1, a.prenatal_tri2_v2, a.prenatal_tri2_v3, a.prenatal_tri3_v1, a.prenatal_tri3_v2, a.prenatal_tri3_v3, a.tetanus_status, a.date_given_vit_a, a.risk_code, a.risk_date_detected, a.pregnancy_date_terminated, a.pregnancy_outcome, a.livebirths_birth_weight, a.livebirths_place_delivery, a.livebirths_type_delivery, a.livebirths_attended_by, a.date_added, a.remarks, b.id, b.serial_number, b.form_number, b.last_name, b.first_name, b.middle_name, b.age, b.gender, b.dob, b.relation, b.history, b.registration_date, b.facility, b.residence, b.address, b.barangay_id, b.city_id, b.province_id, b.region_id, b.last_user_trans, b.ip_address, b.last_update');
                $this->db->from('prenatals a');     
                $this->db->join('clients b', 'a.client_id = b.id');
                $this->db->order_by('a.id', 'ASC');
                
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
            		    $this->db->where('year(from_unixtime(a.date_added))', $params['by_year']);
            		}
            		
            		if ( ! empty($params['gender']))
            		{
            		    $this->db->where('b.gender', $params['gender']);
            		}
            		
            		if ( ! empty($params['age']))
            		{
            		    $this->db->where('b.age', $params['age']);
            		}
            
            		if ( ! empty($params['name']))
            		{
            		    $this->db
            				->or_like('b.last_name', trim($params['name']))
            				->or_like('b.first_name', trim($params['name']))
            				->or_like('b.middle_name', trim($params['name']))
            				->or_like('b.serial_number', trim($params['name']))
            				->or_like('b.form_number', trim($params['name']));
            		}
                    
                    if (! empty($params['risk_code']))
            		{
            			$this->db->where('a.risk_code', $params['risk_code']);
            		}
                    
                    if ( ! empty($params['prenatals_filters']))
            		{		    
                        if ($params['prenatals_filters']=="first")
                        {
                            $this->db
                                ->where('a.prenatal_tri1_v1 !=','')
                                ->where('a.prenatal_tri1_v2 !=','')
                                ->where('a.prenatal_tri1_v3 !=','');
                        }
                        if ($params['prenatals_filters']=="second")
                        {
                            $this->db
                                ->where('a.prenatal_tri2_v1 !=','')
                                ->where('a.prenatal_tri2_v2 !=','')
                                ->where('a.prenatal_tri2_v3 !=','');
                        }
                        if ($params['prenatals_filters']=="third")
                        {
                            $this->db
                                ->where('a.prenatal_tri3_v1 !=','')
                                ->where('a.prenatal_tri3_v2 !=','')
                                ->where('a.prenatal_tri3_v3 !=','');
                        }                       
                        if ($params['prenatals_filters']=="complete")
                        {
                            $this->db
                                ->where('a.prenatal_tri1_v1 !=','')
                                ->where('a.prenatal_tri1_v2 !=','')
                                ->where('a.prenatal_tri1_v3 !=','')
                                ->where('a.prenatal_tri2_v1 !=','')
                                ->where('a.prenatal_tri2_v2 !=','')
                                ->where('a.prenatal_tri2_v3 !=','')
                                ->where('a.prenatal_tri3_v1 !=','')
                                ->where('a.prenatal_tri3_v2 !=','')
                                ->where('a.prenatal_tri3_v3 !=','');
                        }                       
            		}
            
            		return $this->db->count_all_results();
    }       
    
    public function get_prenatal_details($id = null)
    {
          $this->db->select('*');
          $this->db->from('prenatals');
      		$this->db->where('id', $id);

      		$query = $this->db->get();
      		
      		return $query->row();
    }
    
    public function check_prenatal_exist($client_id = null)
    {
          $this->db->select('id');
          $this->db->from('prenatals');
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
		$this->db->select('a.id as pn_id, a.client_id, a.last_menstrual_period, a.gravida, a.para, a.term, a.estimated_date_confinement, a.tetanus_status, a.date_given_vit_a, a.risk_code, a.risk_date_detected, a.pregnancy_date_terminated, a.pregnancy_outcome, a.livebirths_birth_weight, a.livebirths_place_delivery, a.livebirths_type_delivery, a.livebirths_attended_by, a.remarks, b.id, b.serial_number, b.form_number, b.last_name, b.first_name, b.middle_name, b.age, b.gender, b.dob, b.relation, b.history, b.registration_date, b.facility, b.residence, b.address, b.barangay_id, b.city_id, b.province_id, b.region_id, b.last_user_trans, b.ip_address, b.last_update');
                $this->db->from('prenatals a');     
                $this->db->join('clients b', 'a.client_id = b.id');
                $this->db->order_by('a.id', 'ASC');       
                if ( ! empty($params['gender']))
		{
		    $this->db->where('b.gender', $params['gender']);
		}
		
		if ( ! empty($params['age']))
		{
		    $this->db->where('b.age', $params['age']);
		}

		if ( ! empty($params['name']))
		{
		    $this->db
				->or_like('b.last_name', trim($params['name']))
				->or_like('b.first_name', trim($params['name']))
				->or_like('b.middle_name', trim($params['name']))
				->or_like('b.serial_number', trim($params['name']))
				->or_like('b.form_number', trim($params['name']));
		}

		return $this->get_all();
    }
    
        public function get_results($limit = array(),$params = array())
        {              
                $this->db->select('*, a.id as pn_id, a.prenatal_tri1_v1, a.prenatal_tri1_v2, a.prenatal_tri1_v3, a.prenatal_tri2_v1, a.prenatal_tri2_v2, a.prenatal_tri2_v3, a.prenatal_tri3_v1, a.prenatal_tri3_v2, a.prenatal_tri3_v3, b.id, b.serial_number, b.form_number, b.last_name, b.first_name, b.middle_name, b.age, b.gender, b.dob, b.relation, b.history, b.registration_date, b.facility, b.residence, b.address, b.barangay_id, b.city_id, b.province_id, b.region_id, b.last_user_trans, b.ip_address, b.last_update');
                $this->db->from('prenatals a');     
                $this->db->join('clients b', 'b.id = a.client_id');
                $this->db->order_by('a.id', 'ASC');
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
        		    $this->db->where('year(from_unixtime(a.date_added))', $params['by_year']);
        		}
            		
              	if ( ! empty($params['gender']))
                      {
            		    $this->db->where('b.gender', $params['gender']);
            		}
            		
            		if ( ! empty($params['age']))
            		{
            		    $this->db->where('b.age', $params['age']);
            		}
            
            		if ( ! empty($params['name']))
            		{
            		    $this->db
            				->or_like('b.last_name', trim($params['name']))
            				->or_like('b.first_name', trim($params['name']))
            				->or_like('b.middle_name', trim($params['name']))
            				->or_like('b.serial_number', trim($params['name']))
            				->or_like('b.form_number', trim($params['name']));
            		}
                    if ( isset($params['except']) )
            		{
            			$this->db
                            ->where_not_in('b.last_name', $params['except'])
                            ->where_not_in('b.first_name', $params['except'])
                            ->where_not_in('b.middle_name', $params['except']);
            		}
                    
                    if ( ! empty($params['risk_code']))
            		{
            			$this->db->where('a.risk_code', $params['risk_code']);
            		}
                    
                    if ( ! empty($params['prenatals_filters']))
            		{		    
                        if ($params['prenatals_filters']=="first")
                        {
                            $this->db
                                ->where('a.prenatal_tri1_v1 !=','')
                                ->where('a.prenatal_tri1_v2 !=','')
                                ->where('a.prenatal_tri1_v3 !=','');
                        }
                        if ($params['prenatals_filters']=="second")
                        {
                            $this->db
                                ->where('a.prenatal_tri2_v1 !=','')
                                ->where('a.prenatal_tri2_v2 !=','')
                                ->where('a.prenatal_tri2_v3 !=','');
                        }
                        if ($params['prenatals_filters']=="third")
                        {
                            $this->db
                                ->where('a.prenatal_tri3_v1 !=','')
                                ->where('a.prenatal_tri3_v2 !=','')
                                ->where('a.prenatal_tri3_v3 !=','');
                        }                       
                        if ($params['prenatals_filters']=="complete")
                        {
                            $this->db
                                ->where('a.prenatal_tri1_v1 !=','')
                                ->where('a.prenatal_tri1_v2 !=','')
                                ->where('a.prenatal_tri1_v3 !=','')
                                ->where('a.prenatal_tri2_v1 !=','')
                                ->where('a.prenatal_tri2_v2 !=','')
                                ->where('a.prenatal_tri2_v3 !=','')
                                ->where('a.prenatal_tri3_v1 !=','')
                                ->where('a.prenatal_tri3_v2 !=','')
                                ->where('a.prenatal_tri3_v3 !=','');
                        }                         
            		}
      		 
            		$query = $this->db->get();
            		
            		return $query->result();	
        }
        
        public function get_results_pdf($limit = array(),$params = array())
        {              
                $this->db->select('*, a.id as pn_id, a.prenatal_tri1_v1, a.prenatal_tri1_v2, a.prenatal_tri1_v3, a.prenatal_tri2_v1, a.prenatal_tri2_v2, a.prenatal_tri2_v3, a.prenatal_tri3_v1, a.prenatal_tri3_v2, a.prenatal_tri3_v3, b.id, b.serial_number, b.form_number, b.last_name, b.first_name, b.middle_name, b.age, b.gender, b.dob, b.relation, b.history, b.registration_date, b.facility, b.residence, b.address, b.barangay_id, b.city_id, b.province_id, b.region_id, b.last_user_trans, b.ip_address, b.last_update');
                $this->db->from('prenatals a');     
                $this->db->join('clients b', 'b.id = a.client_id');
                $this->db->order_by('a.id', 'ASC');
                $this->db->limit($limit[0], $limit[1]);
                
                if ( ! empty($params['client_id']))
            		{
            		    $this->db->where('client_id', $params['client_id']);
            		}
                    
                if ( ! empty($params['by_year']))
        		{
        		    $this->db->where('year(from_unixtime(a.date_added))', $params['by_year']);
        		}
            		
              	if ( ! empty($params['gender']))
                      {
            		    $this->db->where('b.gender', $params['gender']);
            		}
            		
            		if ( ! empty($params['age']))
            		{
            		    $this->db->where('b.age', $params['age']);
            		}
            
            		if ( ! empty($params['name']))
            		{
            		    $this->db
            				->or_like('b.last_name', trim($params['name']))
            				->or_like('b.first_name', trim($params['name']))
            				->or_like('b.middle_name', trim($params['name']))
            				->or_like('b.serial_number', trim($params['name']))
            				->or_like('b.form_number', trim($params['name']));
            		}
                    if ( isset($params['except']) )
            		{
            			$this->db
                            ->where_not_in('b.last_name', $params['except'])
                            ->where_not_in('b.first_name', $params['except'])
                            ->where_not_in('b.middle_name', $params['except']);
            		}
                    
                    if ( ! empty($params['risk_code']))
            		{
            			$this->db->where('a.risk_code', $params['risk_code']);
            		}
                    
                    if ( ! empty($params['prenatals_filters']))
            		{		    
                        if ($params['prenatals_filters']=="first")
                        {
                            $this->db
                                ->where('a.prenatal_tri1_v1 !=','')
                                ->where('a.prenatal_tri1_v2 !=','')
                                ->where('a.prenatal_tri1_v3 !=','');
                        }
                        if ($params['prenatals_filters']=="second")
                        {
                            $this->db
                                ->where('a.prenatal_tri2_v1 !=','')
                                ->where('a.prenatal_tri2_v2 !=','')
                                ->where('a.prenatal_tri2_v3 !=','');
                        }
                        if ($params['prenatals_filters']=="third")
                        {
                            $this->db
                                ->where('a.prenatal_tri3_v1 !=','')
                                ->where('a.prenatal_tri3_v2 !=','')
                                ->where('a.prenatal_tri3_v3 !=','');
                        }                       
                        if ($params['prenatals_filters']=="complete")
                        {
                            $this->db
                                ->where('a.prenatal_tri1_v1 !=','')
                                ->where('a.prenatal_tri1_v2 !=','')
                                ->where('a.prenatal_tri1_v3 !=','')
                                ->where('a.prenatal_tri2_v1 !=','')
                                ->where('a.prenatal_tri2_v2 !=','')
                                ->where('a.prenatal_tri2_v3 !=','')
                                ->where('a.prenatal_tri3_v1 !=','')
                                ->where('a.prenatal_tri3_v2 !=','')
                                ->where('a.prenatal_tri3_v3 !=','');
                        }                                 
            		}
      		 
            		$query = $this->db->get();
            		return $query->result();	
        }
        
        public function get_client($cid)
        {
                $this->db->select('*');
                $this->db->from('clients a');     
        	$this->db->where('a.id', $cid);
		
      		$query = $this->db->get();
      		
      		return $query->row();	
        }
        
        public function get_client_pn($pnid)
        {
                $this->db->select('*, b.id as pn_id');
                $this->db->from('clients a');     
                $this->db->join('prenatals b', 'a.id = b.client_id','left');
        	$this->db->where('b.id', $pnid);
		
      		$query = $this->db->get();
      		
      		return $query->row();	
        }
        
        public function check_record($client_id)
        {
                $this->db->from('prenatals');
        		    $this->db->where('client_id', $client_id);
        		    return $this->db->count_all_results();
        }

}