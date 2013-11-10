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
class Bhs_m2_m extends MY_Model
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
	 
    public function get_morbidity_cases_under1($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and b.client_age = 0) as total_cases_under1_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and b.client_age = 0) as total_cases_under1_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_1to4($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 1 and b.client_age <= 4)) as total_cases_1to4_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 1 and b.client_age <= 4)) as total_cases_1to4_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_5to9($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 9 and b.client_age <= 9)) as total_cases_5to9_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 5 and b.client_age <= 9)) as total_cases_5to9_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_10to14($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 10 and b.client_age <= 14)) as total_cases_10to14_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 10 and b.client_age <= 14)) as total_cases_10to14_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_15to19($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 15 and b.client_age <= 19)) as total_cases_15to19_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 15 and b.client_age <= 19)) as total_cases_15to19_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_20to24($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 20 and b.client_age <= 24)) as total_cases_20to24_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 20 and b.client_age <= 24)) as total_cases_20to24_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_25to29($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 25 and b.client_age <= 29)) as total_cases_25to29_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 25 and b.client_age <= 29)) as total_cases_25to29_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_30to34($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 30 and b.client_age <= 34)) as total_cases_30to34_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 30 and b.client_age <= 34)) as total_cases_30to34_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_35to39($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 35 and b.client_age <= 39)) as total_cases_35to39_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 35 and b.client_age <= 39)) as total_cases_35to39_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_40to44($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 40 and b.client_age <= 44)) as total_cases_40to44_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 40 and b.client_age <= 44)) as total_cases_40to44_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_45to49($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 45 and b.client_age <= 49)) as total_cases_45to49_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 45 and b.client_age <= 49)) as total_cases_45to49_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_50to54($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 50 and b.client_age <= 54)) as total_cases_50to54_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 50 and b.client_age <= 54)) as total_cases_50to54_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_55to59($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 55 and b.client_age <= 59)) as total_cases_55to59_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 55 and b.client_age <= 59)) as total_cases_55to59_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_60to64($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 60 and b.client_age <= 64)) as total_cases_60to64_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 60 and b.client_age <= 64)) as total_cases_60to64_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_65up($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male' and (b.client_age >= 65)) as total_cases_65up_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female' and (b.client_age >= 65)) as total_cases_65up_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
    
    public function get_morbidity_cases_total($m, $y)
    {
            $query = $this->db->query("SELECT a.name, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'male') as total_cases_total_m, (SELECT COUNT(b.id) FROM default_assessment b INNER JOIN default_clients c ON b.client_id = c.id WHERE b.disease_id = a.id and (MONTH(FROM_UNIXTIME(b.date_added))=$m and YEAR(FROM_UNIXTIME(b.date_added))=$y) and c.gender = 'female') as total_cases_total_f FROM default_diseases a WHERE a.category < 3 ORDER BY a.name");
            $rows = $query->result_array();
            return $rows;
    }  
}