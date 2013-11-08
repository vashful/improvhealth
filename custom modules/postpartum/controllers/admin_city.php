<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package  	PyroCMS
 * @subpackage  City
 * @category  	Module
 * @author  	PyroCMS Dev Team
 */
class Admin_City extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var int
	 */
	protected $section = 'city';
	
	/**
	 * Array that contains the validation rules
	 * @access protected
	 * @var array
	 */
	protected $validation_rules = array(
		array(
			'field' => 'name',
			'label' => 'City',
			'rules' => 'trim|required|max_length[40]|callback__check_city'
		),
	);
	
	/**
	 * The constructor
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('clients_city_m');
		$this->lang->load(array('clients', 'region', 'province', 'city', 'barangay'));
		
		// Load the validation library along with the rules
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->validation_rules);
	}
	
	/**
	 * Index method, lists all city
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->pyrocache->delete_all('modules_m');
		
		// Create pagination links
		$total_rows = $this->clients_city_m->count_all();
		$pagination = create_pagination('admin/clients/city/index', $total_rows, NULL, 5);
			
		// Using this data, get the relevant results
		$cities = $this->clients_city_m->order_by('name')->limit($pagination['limit'])->get_all();

		$this->template
			->title($this->module_details['name'], lang('city_list_title'))
			->set('cities', $cities)
			->set('pagination', $pagination)
			->build('admin/city/index', $this->data);
	}
	
	/**
	 * Create method, creates a new category
	 * @access public
	 * @return void
	 */
	public function create()
	{
		// Validate the data
		if ($this->form_validation->run())
		{
			$this->clients_city_m->insert($_POST)
				? $this->session->set_flashdata('success', sprintf( 'City "%s" Successfully Created.', $this->input->post('name')) )
				: $this->session->set_flashdata('error', 'Error Adding City');

			redirect('admin/clients/city');
		}
		
		// Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$city->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->template
			->title($this->module_details['name'], 'Add City')
			->set('city', $city)
			->build('admin/city/form');	
	}
	
	/**
	 * Edit method, edits an existing category
	 * @access public
	 * @param int id The ID of the category to edit
	 * @return void
	 */
	public function edit($id = 0)
	{	
		// Get the category
		$city = $this->clients_city_m->get($id);
		
		// ID specified?
		$city or redirect('admin/clients/city/index');
		
		// Validate the results
		if ($this->form_validation->run())
		{		
			$this->clients_city_m->update($id, $_POST)
				? $this->session->set_flashdata('success', sprintf( lang('city_edit_success'), $this->input->post('name')) )
				: $this->session->set_flashdata('error', lang('city_edit_error'));
			
			redirect('admin/clients/city/index');
		}
		
		// Loop through each rule
		foreach ($this->validation_rules as $rule)
		{
			if ($this->input->post($rule['field']) !== FALSE)
			{
				$city->{$rule['field']} = $this->input->post($rule['field']);
			}
		}

		$this->template
			->title($this->module_details['name'], sprintf(lang('city_edit_title'), $city->name))
			->set('city', $city)
			->build('admin/city/form');
	}	

	/**
	 * Delete method, deletes an existing category (obvious isn't it?)
	 * @access public
	 * @param int id The ID of the category to edit
	 * @return void
	 */
	public function delete($id = 0)
	{	
		$id_array = (!empty($id)) ? array($id) : $this->input->post('action_to');
		
		// Delete multiple
		if (!empty($id_array))
		{
			$deleted = 0;
			$to_delete = 0;
			foreach ($id_array as $id)
			{
				if ($this->clients_city_m->delete($id))
				{
					$deleted++;
				}
				else
				{
					$this->session->set_flashdata('error', sprintf(lang('city_mass_delete_error'), $id));
				}
				$to_delete++;
			}
			
			if ( $deleted > 0 )
			{
				$this->session->set_flashdata('success', sprintf(lang('city_mass_delete_success'), $deleted, $to_delete));
			}
		}		
		else
		{
			$this->session->set_flashdata('error', lang('city_no_select_error'));
		}
		
		redirect('admin/clients/city/index');
	}
		
	/**
	 * Callback method that checks the title of the category
	 * @access public
	 * @param string title The title to check
	 * @return bool
	 */
	public function _check_city($city = '')
	{
		if ($this->clients_city_m->check_city($city))
		{
			$this->form_validation->set_message('_check_city', sprintf('City already Exist "%s"', $city));
			return FALSE;
		}

		return TRUE;
	}
	
	/**
	 * Create method, creates a new category via ajax
	 * @access public
	 * @return void
	 */
	public function create_ajax()
	{
		// Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$city->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->data->method = 'create';
		$this->data->city =& $city;
		
		if ($this->form_validation->run())
		{
			$id = $this->clients_city_m->insert_ajax($_POST);
			
			if ($id > 0)
			{
				$message = sprintf( 'City "%d" Successfully Added', $this->input->post('title'));
			}
			else
			{
				$message = 'Error Adding City';
			}

			return $this->template->build_json(array(
				'message'		=> $message,
				'title'			=> $this->input->post('name'),
				'id'	=> $id,
				'status'		=> 'ok'
			));
		}	
		else
		{
			// Render the view
			$form = $this->load->view('admin/city/form', $this->data, TRUE);

			if ($errors = validation_errors())
			{
				return $this->template->build_json(array(
					'message'	=> $errors,
					'status'	=> 'error',
					'form'		=> $form
				));
			}

			echo $form;
		}
	}
}