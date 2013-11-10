<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Categories model
 *
 * @package		BHS
 * @subpackage	Region Module
 * @category	Modules
 * @author		Ronald Rhey P. Minoza - Kobusoft
 */
class Diagnosis_m extends MY_Model
{
	/**
	 * Insert a new category into the database
	 * @access public
	 * @param array $input The data to insert
	 * @return string
	 */
	public function insert($input = array())
	{
      $data =array(
              'name' => $input['name'],
              'description' => $input['description'],
              'classification' => $input['classification'],
              'symptoms' => $input['symptoms'],
              'treatment' => $input['treatment'],
              'date_added' => now(),
              'added_by' => $this->session->userdata("user_id")
      );
        
      $this->db->insert('diagnosis', $data); 
      
      return $input['name']; 
	}
	
		/**
	* Get the barangay name
	*
	* @access public
	* @param string  The uri of the page
	* @return object
	*/
	public function get_name($id)
	{
		if(!$id)
		return;
    $query = $this->db->query("SELECT name FROM default_clients_regions where id = $id LIMIT 1");
		$row = $query->row_array();
    return $row['name'];
	}
	
	public function get($id)
	{
		if(!$id)
		return;
    $query = $this->db->query("SELECT * FROM default_diagnosis where id = $id LIMIT 1");
    return $query->row();
	}
	
	public function get_list()
	{
		$query = $this->db->query("SELECT * FROM default_diagnosis order by name");

		return $query->result();
	}

	/**
	 * Update an existing category
	 * @access public
	 * @param int $id The ID of the category
	 * @param array $input The data to update
	 * @return bool
	 */
	public function update($id, $input)
	{
      $data =array(
              'name' => $input['name'],
              'description' => $input['description'],
              'classification' => $input['classification'],
              'symptoms' => $input['symptoms'],
              'treatment' => $input['treatment'],
              'last_update' => now(),
              'last_updated_by' => $this->session->userdata("user_id")
      );
        
      $this->db->where('id', $id);
      return $this->db->update('diagnosis', $data); 
	}

	/**
	 * Callback method for validating the title
	 * @access public
	 * @param string $title The title to validate
	 * @return mixed
	 */
	public function check_diagnosis($diagnosis = '')
	{
		$query = $this->db->query("SELECT * FROM default_diagnosis where trim(lower(name)) = trim(lower('$diagnosis'))");
		return $query->num_rows();
	}
	
	public function delete($id)
	{
    $this->db->delete('default_diagnosis', array('id' => $id)); 
		return true;
  }
	
	/**
	 * Insert a new category into the database via ajax
	 * @access public
	 * @param array $input The data to insert
	 * @return int
	 */
	public function insert_ajax($input = array())
	{
		  $data =array(
              'name' => $input['name'],
              'description' => $input['description'],
              'classification' => $input['classification'],
              'symptoms' => $input['symptoms'],
              'treatment' => $input['treatment'],
              'date_added' => now(),
              'added_by' => $this->session->userdata("user_id")
      );
        
      $this->db->insert('diagnosis', $data); 
      
      return $this->db->insert_id();
	}
}