<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package  	PyroCMS
 * @subpackage  Diseases
 * @category  	Module
 * @author  	PyroCMS Dev Team
 */
class Admin_Diseases extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var int
	 */
	protected $section = 'diseases';
	
	/**
	 * Array that contains the validation rules
	 * @access protected
	 * @var array
	 */
	protected $validation_rules = array(
		array(
			'field' => 'name',
			'label' => 'Diseases',
			'rules' => 'trim|required|max_length[40]|callback__check_diseases'
		),
		array(
			'field' => 'category',
			'label' => 'Category',
			'rules' => ''
		)
	);
	
	protected $validation_rules_edit = array(
		array(
			'field' => 'name',
			'label' => 'Diseases',
			'rules' => 'trim|required|max_length[40]'
		),
		array(
			'field' => 'description',
			'label' => 'Description',
			'rules' => 'xss_clean'
		),
		array(
			'field' => 'symptoms',
			'label' => 'Symptoms',
			'rules' => 'xss_clean'
		),
		array(
			'field' => 'treatment',
			'label' => 'Treatment',
			'rules' => 'xss_clean'
		)
	);
	
	/**
	 * The constructor
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('diseases_m');
		$this->lang->load(array('diseases', 'consultations', 'referrer'));
		// Load the validation library along with the rules
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->validation_rules);
	}
	
	/**
	 * Index method, lists all diseases
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->pyrocache->delete_all('modules_m');
		
		// Using this data, get the relevant results
		$diseases = $this->diseases_m->get_list();

		$this->template
			->title($this->module_details['name'], lang('diseases_list_title'))
			->set('diseases', $diseases)
			->build('admin/diseases/index', $this->data);
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
			$this->diseases_m->insert($_POST)
				? $this->session->set_flashdata('success', sprintf( 'Disease "%s" Successfully Created.', $this->input->post('name')) )
				: $this->session->set_flashdata('error', 'Error Adding Diseases');

			redirect('admin/consultations/diseases');
		}
		
		// Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$diseases->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->template
			->title($this->module_details['name'], 'Add Diseases')
			->set('diseases', $diseases)
			->build('admin/diseases/form');	
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
		$diseases = $this->diseases_m->get($id);
		
		// ID specified?
		$diseases or redirect('admin/consultations/diseases/index');
		$this->form_validation->set_rules($this->validation_rules_edit);
		// Validate the results
		if ($this->form_validation->run())
		{		
			$this->diseases_m->update($id, $_POST)
				? $this->session->set_flashdata('success', sprintf( lang('diseases_edit_success'), $this->input->post('name')) )
				: $this->session->set_flashdata('error', lang('diseases_edit_error'));
			
			redirect('admin/consultations/diseases/index');
		}
		
		// Loop through each rule
		foreach ($this->validation_rules as $rule)
		{
			if ($this->input->post($rule['field']) !== FALSE)
			{
				$diseases->{$rule['field']} = $this->input->post($rule['field']);
			}
		}

		$this->template
			->title($this->module_details['name'], sprintf(lang('diseases_edit_title'), $diseases->name))
			->set('diseases', $diseases)
			->build('admin/diseases/form');
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
				if ($this->diseases_m->delete($id))
				{
					$deleted++;
				}
				else
				{
					$this->session->set_flashdata('error', sprintf(lang('diseases_mass_delete_error'), $id));
				}
				$to_delete++;
			}
			
			if ( $deleted > 0 )
			{
				$this->session->set_flashdata('success', sprintf(lang('diseases_mass_delete_success'), $deleted, $to_delete));
			}
		}		
		else
		{
			$this->session->set_flashdata('error', lang('diseases_no_select_error'));
		}
		
		redirect('admin/consultations/diseases/index');
	}
		
	/**
	 * Callback method that checks the title of the category
	 * @access public
	 * @param string title The title to check
	 * @return bool
	 */
	public function _check_diseases($diseases = '')
	{
		if ($this->diseases_m->check_diseases($diseases))
		{
			$this->form_validation->set_message('_check_diseases', sprintf('Diseases already Exist "%s"', $diseases));
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
			$diseases->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->data->method = 'create';
		$this->data->diseases =& $diseases;
		
		if ($this->form_validation->run())
		{
			$id = $this->diseases_m->insert_ajax($_POST);
			
			if ($id > 0)
			{
				$message = sprintf( 'Diseases "%d" Successfully Added', $this->input->post('title'));
			}
			else
			{
				$message = 'Error Adding Diseases';
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
			$form = $this->load->view('admin/diseases/form_ajax', $this->data, TRUE);

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