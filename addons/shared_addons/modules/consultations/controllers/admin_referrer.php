<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package  	PyroCMS
 * @subpackage  Referrer
 * @category  	Module
 * @author  	PyroCMS Dev Team
 */
class Admin_Referrer extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var int
	 */
	protected $section = 'referrer';
	
	/**
	 * Array that contains the validation rules
	 * @access protected
	 * @var array
	 */
	protected $validation_rules = array(
    array(
			'field' => 'lastname',
			'label' => 'Last Name',
			'rules' => 'trim|required|max_length[40]'
		),
		array(
			'field' => 'firstname',
			'label' => 'First Name',
			'rules' => 'trim|required|max_length[40]'
		),
		array(
			'field' => 'middlename',
			'label' => 'Middle Name',
			'rules' => 'trim|required|max_length[40]'
		),
		array(
			'field' => 'profession',
			'label' => 'Referrer',
			'rules' => 'trim|required'
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
		
		$this->load->model('referrer_m');
		$this->lang->load(array('diseases', 'consultations', 'referrer'));
		// Load the validation library along with the rules
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->validation_rules);
	}
	
	/**
	 * Index method, lists all referrer
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->pyrocache->delete_all('modules_m');
		
		// Using this data, get the relevant results
		$referrer = $this->referrer_m->get_list();

		$this->template
			->title($this->module_details['name'], lang('referrer_list_title'))
			->set('referrer', $referrer)
			->build('admin/referrer/index', $this->data);
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
			$this->referrer_m->insert($_POST)
				? $this->session->set_flashdata('success', sprintf( 'Referrer "%s" Successfully Created.', $this->input->post('lastname').', '.$this->input->post('firstname')) )
				: $this->session->set_flashdata('error', 'Error Adding Referrer');

			redirect('admin/consultations/referrer');
		}
		
		// Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$referrer->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->template
			->title($this->module_details['name'], 'Add Referrer')
			->set('referrer', $referrer)
			->build('admin/referrer/form');	
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
		$referrer = $this->referrer_m->get($id);
		
		// ID specified?
		$referrer or redirect('admin/consultations/referrer/index');

		// Validate the results
		if ($this->form_validation->run())
		{		
			$this->referrer_m->update($id, $_POST)
				? $this->session->set_flashdata('success', sprintf( lang('referrer_edit_success'), $this->input->post('lastname').', '.$this->input->post('firstname')) )
				: $this->session->set_flashdata('error', lang('referrer_edit_error'));
			
			redirect('admin/consultations/referrer/index');
		}
		
		// Loop through each rule
		foreach ($this->validation_rules as $rule)
		{
			if ($this->input->post($rule['field']) !== FALSE)
			{
				$referrer->{$rule['field']} = $this->input->post($rule['field']);
			}
		}

		$this->template
			->title($this->module_details['name'], sprintf(lang('referrer_edit_title'), $referrer->lastname.', '.$referrer->firstname.' '.$referrer->middlename))
			->set('referrer', $referrer)
			->build('admin/referrer/form');
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
				if ($this->referrer_m->delete($id))
				{
					$deleted++;
				}
				else
				{
					$this->session->set_flashdata('error', sprintf(lang('referrer_mass_delete_error'), $id));
				}
				$to_delete++;
			}
			
			if ( $deleted > 0 )
			{
				$this->session->set_flashdata('success', sprintf(lang('referrer_mass_delete_success'), $deleted, $to_delete));
			}
		}		
		else
		{
			$this->session->set_flashdata('error', lang('referrer_no_select_error'));
		}
		
		redirect('admin/consultations/referrer/index');
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
			$referrer->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->data->method = 'create';
		$this->data->referrer =& $referrer;
		
		if ($this->form_validation->run())
		{
			$id = $this->referrer_m->insert_ajax($_POST);
			
			if ($id > 0)
			{
				$message = sprintf( 'Personnel/Staff "%d" Successfully Added', $this->input->post('title'));
			}
			else
			{
				$message = 'Error Adding Personnel/Staff';
			}

			return $this->template->build_json(array(
				'message'		=> $message,
				'title'			=> $this->input->post('lastname').', '.$this->input->post('firstname').' '.$this->input->post('middlename'),
				'id'	=> $id,
				'status'		=> 'ok'
			));
		}	
		else
		{
			// Render the view
			$form = $this->load->view('admin/referrer/form_ajax', $this->data, TRUE);

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