<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package  	PyroCMS
 * @subpackage  Diagnosis
 * @category  	Module
 * @author  	PyroCMS Dev Team
 */
class Admin_Diagnosis extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var int
	 */
	protected $section = 'diagnosis';
	
	/**
	 * Array that contains the validation rules
	 * @access protected
	 * @var array
	 */
	protected $validation_rules = array(
		array(
			'field' => 'name',
			'label' => 'Diagnosis',
			'rules' => 'trim|required|max_length[40]|callback__check_diagnosis'
		),
		array(
			'field' => 'description',
			'label' => 'Description',
			'rules' => 'xss_clean'
		),
		array(
			'field' => 'classification',
			'label' => 'Classification',
			'rules' => 'required'
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
	
	protected $validation_rules_edit = array(
		array(
			'field' => 'name',
			'label' => 'Diagnosis',
			'rules' => 'trim|required|max_length[40]'
		),
		array(
			'field' => 'description',
			'label' => 'Description',
			'rules' => 'xss_clean'
		),
		array(
			'field' => 'classification',
			'label' => 'Classification',
			'rules' => 'required'
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
		
		$this->load->model('diagnosis_m');
		$this->lang->load(array('diagnosis', 'diagnose', 'referrer'));
		// Load the validation library along with the rules
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->validation_rules);
	}
	
	/**
	 * Index method, lists all diagnosis
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->pyrocache->delete_all('modules_m');
		
		// Using this data, get the relevant results
		$diagnosis = $this->diagnosis_m->get_list();

		$this->template
			->title($this->module_details['name'], lang('diagnosis_list_title'))
			->set('diagnosis', $diagnosis)
			->build('admin/diagnosis/index', $this->data);
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
			$this->diagnosis_m->insert($_POST)
				? $this->session->set_flashdata('success', sprintf( 'Diagnosis "%s" Successfully Created.', $this->input->post('name')) )
				: $this->session->set_flashdata('error', 'Error Adding Diagnosis');

			redirect('admin/diagnose/diagnosis');
		}
		
		// Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$diagnosis->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->template
			->title($this->module_details['name'], 'Add Diagnosis')
			->set('diagnosis', $diagnosis)
			->build('admin/diagnosis/form');	
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
		$diagnosis = $this->diagnosis_m->get($id);
		
		// ID specified?
		$diagnosis or redirect('admin/diagnose/diagnosis/index');
		$this->form_validation->set_rules($this->validation_rules_edit);
		// Validate the results
		if ($this->form_validation->run())
		{		
			$this->diagnosis_m->update($id, $_POST)
				? $this->session->set_flashdata('success', sprintf( lang('diagnosis_edit_success'), $this->input->post('name')) )
				: $this->session->set_flashdata('error', lang('diagnosis_edit_error'));
			
			redirect('admin/diagnose/diagnosis/index');
		}
		
		// Loop through each rule
		foreach ($this->validation_rules as $rule)
		{
			if ($this->input->post($rule['field']) !== FALSE)
			{
				$diagnosis->{$rule['field']} = $this->input->post($rule['field']);
			}
		}

		$this->template
			->title($this->module_details['name'], sprintf(lang('diagnosis_edit_title'), $diagnosis->name))
			->set('diagnosis', $diagnosis)
			->build('admin/diagnosis/form');
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
				if ($this->diagnosis_m->delete($id))
				{
					$deleted++;
				}
				else
				{
					$this->session->set_flashdata('error', sprintf(lang('diagnosis_mass_delete_error'), $id));
				}
				$to_delete++;
			}
			
			if ( $deleted > 0 )
			{
				$this->session->set_flashdata('success', sprintf(lang('diagnosis_mass_delete_success'), $deleted, $to_delete));
			}
		}		
		else
		{
			$this->session->set_flashdata('error', lang('diagnosis_no_select_error'));
		}
		
		redirect('admin/diagnose/diagnosis/index');
	}
		
	/**
	 * Callback method that checks the title of the category
	 * @access public
	 * @param string title The title to check
	 * @return bool
	 */
	public function _check_diagnosis($diagnosis = '')
	{
		if ($this->diagnosis_m->check_diagnosis($diagnosis))
		{
			$this->form_validation->set_message('_check_diagnosis', sprintf('Diagnosis already Exist "%s"', $diagnosis));
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
			$diagnosis->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->data->method = 'create';
		$this->data->diagnosis =& $diagnosis;
		
		if ($this->form_validation->run())
		{
			$id = $this->diagnosis_m->insert_ajax($_POST);
			
			if ($id > 0)
			{
				$message = sprintf( 'Diagnosis "%d" Successfully Added', $this->input->post('title'));
			}
			else
			{
				$message = 'Error Adding Diagnosis';
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
			$form = $this->load->view('admin/diagnosis/form_ajax', $this->data, TRUE);

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