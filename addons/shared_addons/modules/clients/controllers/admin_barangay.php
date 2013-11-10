<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package  	PyroCMS
 * @subpackage  Barangay
 * @category  	Module
 * @author  	PyroCMS Dev Team
 */
class Admin_Barangay extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var int
	 */
	protected $section = 'barangay';
	
	/**
	 * Array that contains the validation rules
	 * @access protected
	 * @var array
	 */
	protected $validation_rules = array(
		array(
			'field' => 'name',
			'label' => 'Barangay',
			'rules' => 'trim|required|max_length[40]|callback__check_barangay'
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
		
		$this->load->model('clients_barangay_m');
		$this->lang->load(array('clients', 'region', 'province', 'city', 'barangay'));
		
		// Load the validation library along with the rules
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->validation_rules);
	}
	
	/**
	 * Index method, lists all barangay
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->pyrocache->delete_all('modules_m');
		
		// Create pagination links
		$total_rows = $this->clients_barangay_m->count_all();
		$pagination = create_pagination('admin/clients/barangay/index', $total_rows, NULL, 5);
			
		// Using this data, get the relevant results
		$barangays = $this->clients_barangay_m->order_by('name')->limit($pagination['limit'])->get_all();

		$this->template
			->title($this->module_details['name'], lang('barangay_list_title'))
			->set('barangays', $barangays)
			->set('pagination', $pagination)
			->build('admin/barangay/index', $this->data);
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
			$this->clients_barangay_m->insert($_POST)
				? $this->session->set_flashdata('success', sprintf( 'Barangay "%s" Successfully Created.', $this->input->post('name')) )
				: $this->session->set_flashdata('error', 'Error Adding Barangay');

			redirect('admin/clients/barangay');
		}
		
		// Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$barangay->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->template
			->title($this->module_details['name'], 'Add Barangay')
			->set('barangay', $barangay)
			->build('admin/barangay/form');	
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
		$barangay = $this->clients_barangay_m->get($id);
		
		// ID specified?
		$barangay or redirect('admin/clients/barangay/index');
		
		// Validate the results
		if ($this->form_validation->run())
		{		
			$this->clients_barangay_m->update($id, $_POST)
				? $this->session->set_flashdata('success', sprintf( lang('barangay_edit_success'), $this->input->post('name')) )
				: $this->session->set_flashdata('error', lang('barangay_edit_error'));
			
			redirect('admin/clients/barangay/index');
		}
		
		// Loop through each rule
		foreach ($this->validation_rules as $rule)
		{
			if ($this->input->post($rule['field']) !== FALSE)
			{
				$barangay->{$rule['field']} = $this->input->post($rule['field']);
			}
		}

		$this->template
			->title($this->module_details['name'], sprintf(lang('barangay_edit_title'), $barangay->name))
			->set('barangay', $barangay)
			->build('admin/barangay/form');
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
				if ($this->clients_barangay_m->delete($id))
				{
					$deleted++;
				}
				else
				{
					$this->session->set_flashdata('error', sprintf(lang('barangay_mass_delete_error'), $id));
				}
				$to_delete++;
			}
			
			if ( $deleted > 0 )
			{
				$this->session->set_flashdata('success', sprintf(lang('barangay_mass_delete_success'), $deleted, $to_delete));
			}
		}		
		else
		{
			$this->session->set_flashdata('error', lang('barangay_no_select_error'));
		}
		
		redirect('admin/clients/barangay/index');
	}
		
	/**
	 * Callback method that checks the title of the category
	 * @access public
	 * @param string title The title to check
	 * @return bool
	 */
	public function _check_barangay($barangay = '')
	{
		if ($this->clients_barangay_m->check_barangay($barangay))
		{
			$this->form_validation->set_message('_check_barangay', sprintf('Barangay already Exist "%s"', $barangay));
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
			$barangay->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->data->method = 'create';
		$this->data->barangay =& $barangay;
		
		if ($this->form_validation->run())
		{
			$id = $this->clients_barangay_m->insert_ajax($_POST);
			
			if ($id > 0)
			{
				$message = sprintf( 'Barangay "%d" Successfully Added', $this->input->post('title'));
			}
			else
			{
				$message = 'Error Adding Barangay';
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
			$form = $this->load->view('admin/barangay/form', $this->data, TRUE);

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