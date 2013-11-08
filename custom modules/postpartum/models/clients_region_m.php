<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Categories model
 *
 * @package		BHS
 * @subpackage	Region Module
 * @category	Modules
 * @author		Ronald Rhey P. Minoza - Kobusoft
 */
class Clients_region_m extends MY_Model
{
	/**
	 * Insert a new category into the database
	 * @access public
	 * @param array $input The data to insert
	 * @return string
	 */
	public function insert($input = array())
	{
		$this->load->helper('text');
		parent::insert(array(
			'name'=>$input['name']
		));
		
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

	/**
	 * Update an existing category
	 * @access public
	 * @param int $id The ID of the category
	 * @param array $input The data to update
	 * @return bool
	 */
	public function update($id, $input)
	{
		return parent::update($id, array(
			'name'	=> $input['name']
		));
	}

	/**
	 * Callback method for validating the title
	 * @access public
	 * @param string $title The title to validate
	 * @return mixed
	 */
	public function check_region($region = '')
	{
		return parent::count_by('name', $region) > 0;
	}
	
	/**
	 * Insert a new category into the database via ajax
	 * @access public
	 * @param array $input The data to insert
	 * @return int
	 */
	public function insert_ajax($input = array())
	{
		$this->load->helper('text');
		return parent::insert(array(
			'name'=>$input['name']
		));
	}
}