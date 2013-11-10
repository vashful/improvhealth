<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Roles controller for the clients module
 *
 * @author Phil Sturgeon - PyroCMS Dev Team
 * @package PyroCMS
 * @subpackage Groups Module
 * @category Modules
 *
 */
class Admin extends Admin_Controller
{
	
  	protected $section = 'diagnose';
  	
    private $validation_rules = array(
    array(
			'field' => 'diagnosis_id',
			'label' => 'Dianosis',
			'rules' => 'required'
		),
		array(
			'field' => 'date_diagnose',
			'label' => 'Date Diagnose',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'referrer_id',
			'label' => 'Referred By',
			'rules' => 'required'
		),
    array(
			'field' => 'therapy',
			'label' => 'Treatment',
			'rules' => 'required'
		)
	);


  /**
	 * Constructor method
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// Load the required classes
		$this->load->model(array('diagnose_m', 'diagnosis_m', 'referrer_m','clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m'));
		$this->load->library('form_validation');
		$this->lang->load(array('diagnose', 'diagnosis', 'referrer'));
		//$this->output->enable_profiler(TRUE);
		$this->data->list_diagnosis = array();
		if ($list_diagnosis = $this->diagnosis_m->get_list())
		{
      foreach ($list_diagnosis as $diagnosis)
			{
				$this->data->list_diagnosis[$diagnosis->id] = $diagnosis->name;
			}
		}
		
		$this->data->referrer = array();
		if ($referrers = $this->referrer_m->get_list())
		{
			foreach ($referrers as $referrer)
			{
				$this->data->referrers[$referrer->id] = $referrer->lastname.', '.$referrer->firstname.' '.$referrer->middlename;
			}
		}
		
	}

	/**
	 * Create a new client role
	 *
	 * @access public
	 * @return void
	 */
	public function index()
	{
		if($this->session->userdata('selected_client_id'))
		redirect('admin/diagnose/my_list');
		
   	$base_where['gender'] = $this->input->post('f_module') ? $this->input->post('f_gender') : '';

		//diagnosis param
		$base_where = $this->input->post('f_diagnosis') ? $base_where + array('diagnosis' => $this->input->post('f_diagnosis')) : $base_where;
	
		//age param
		$base_where = $this->input->post('f_age') ? $base_where + array('age' => $this->input->post('f_age')) : $base_where;

		//keyphrase param
		$base_where = $this->input->post('f_keywords') ? $base_where + array('name' => $this->input->post('f_keywords')) : $base_where;

		// Create pagination links
		$pagination = create_pagination('admin/diagnose/index', $this->diagnose_m->count_by($base_where));

		// Using this data, get the relevant results
		$clients = $this->diagnose_m->get_results($pagination['limit'],$base_where);

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);

		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)      
			->set_partial('filters', 'admin/partials/filters')   
			->append_metadata(js('admin/filter.js'))
      ->set_partial('diagnose-list', 'admin/tables/clients');
       				
		$this->input->is_ajax_request() ? $this->template->build('admin/tables/clients', $this->data) : $this->template->build('admin/index', $this->data);	 
	 
	}
	
	
			/**
	 * Create a new client role
	 *
	 * @access public
	 * @return void
	 */
	public function my_list()
	{
		$selected_client = $this->clients_m->get($this->session->userdata('selected_client_id'));
		$selected_client or redirect('admin/diagnose');
		
    //determine active param
		$base_where['client_id'] = $this->session->userdata('selected_client_id');
		
		// Create pagination links
		$pagination = create_pagination('admin/diagnose/my_list', $this->diagnose_m->count_by($base_where));

		// Using this data, get the relevant results
		$clients = $this->diagnose_m->get_results($pagination['limit'],$base_where);

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);
		
    $this->data->selected_client = & $selected_client;
    
		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)      
      ->set_partial('diagnose-my_list', 'admin/tables/my_list');
       				
		$this->input->is_ajax_request() ? $this->template->build('admin/tables/my_list', $this->data) : $this->template->build('admin/list', $this->data);	 
	 	 
	}
		/**
	 * Method for handling different form actions
	 * @access public
	 * @return void
	 */
	public function action()
	{
		// Determine the type of action
		switch ($this->input->post('btnAction'))
		{
			case 'delete':
				$this->delete();
				break;
			default:
				redirect('admin/clients');
				break;
		} 
	}
	
  public function add_client()
	{		
                $this->session->set_flashdata('warning', 'Search and Select a Client to be Diagnose Or <a href="admin/clients/add" class="success-link">Register</a> a New Client.');
                $this->session->set_flashdata('current_action', 'diagnose'); 
                redirect('admin/clients');
	}

	/**
	 * Create a new client role
	 *
	 * @access public
	 * @return void
	 */
	public function add($client_id)
	{		
		$client = $this->clients_m->get($client_id);
    $client or redirect('admin/diagnose');
    
    $this->form_validation->set_rules($this->validation_rules);
    
    if ($this->input->post('date_diagnose'))
		{
			$date_diagnose = strtotime($this->input->post('date_diagnose'));  
		}
		else
		{
			$date_diagnose = '';  
		}
		
    if ($_POST)
		{
			
                // Loop through each POST item and add it to the secure_post array
			$secure_post = $this->input->post();

			// Set the full date of birth
  			$secure_post['date_diagnose'] = $date_diagnose;



                // Set the validation rules
		

		if ($this->form_validation->run() !== FALSE)
		{ 
          $this->diagnose_m->insert($secure_post)
					? $this->session->set_flashdata('success', 'New Diagnosis Added on this Client.')
					: $this->session->set_flashdata('error', 'Error Adding Record');

				  redirect('admin/diagnose');
		}
		else
		{
			// Dirty hack that fixes the issue of having to re-add all data upon an error
			if ($_POST)
			{
				
        $diagnose = (object) $_POST;
				print_r($diagnose);     
			}
			else
			{
      	$diagnose->date_diagnose = $date_diagnose;
      }
		}
		}
		
                // Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$diagnose->{$rule['field']} = set_value($rule['field']);
		}

		// Render the view
	
		$diagnose->method_action = 'add';
		$diagnose->client_id = $client_id;
		
		$this->data->diagnose = & $diagnose;
		$this->data->client = & $client;
	
		
		$this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->append_metadata(js('diagnose_form.js', 'diagnose'))
				->build('admin/form',$this->data);
	}


	/**
	 * Edit a client
	 *
	 * @access public
	 * @param int $id The ID of the client to edit
	 * @return void
	 */
	public function edit($id = 0)
	{
		$diagnose = $this->diagnose_m->get_diagnose_details($id);
	  
		$client = $this->clients_m->get($diagnose->client_id);
		// Make sure we found something
		$client or redirect('admin/diagnose');
		
    if ($this->input->post('date_diagnose'))
		{
			$date_diagnose = strtotime($this->input->post('date_diagnose'));  
		}
		else
		{
			$date_diagnose = '';  
			$diagnose->date_diagnose = $diagnose->date_diagnose ? date('Y-m-d', $diagnose->date_diagnose) : ''; 
		}
		
		if ($_POST)
		{
			
      // Loop through each POST item and add it to the secure_post array
			$secure_post = $this->input->post();
			$secure_post['date_diagnose'] = $date_diagnose;
			
      // Set the validation rules
  		$this->form_validation->set_rules($this->validation_rules);
  
  		if ($this->form_validation->run() !== FALSE)
  		{ 
              if($this->diagnose_m->update($id, $secure_post))
              {
                $this->session->set_flashdata('success', 'Client successfully updated');
                if($this->session->userdata('selected_client_id'))
                redirect('admin/diagnose/my_list');
                else
                redirect('admin/diagnose');
              }
              else 
              {
                $this->session->set_flashdata('error', 'Error updating Client/No Data has been entered.');
                redirect("admin/diagnose/edit/$id");
              }
  		}
  		else
  		{
  				$diagnose = (object) $_POST;
  		}
  		// Loop through each validation rule
  		foreach ($this->validation_rules as $rule)
  		{
  			$diagnose->{$rule['field']} = set_value($rule['field']);
  		}
		}
    else
    {
      $client->dob= $client->dob ? date('Y-m-d', $client->dob) : ''; 
    }
	    
		$sc->method_action = 'edit';
		// Render the view
		$this->data->diagnose = & $diagnose;
		$this->data->client = & $client;
		$this->data->client->barangay_name = & $this->clients_barangay_m->get_name($client->barangay_id);;
		$this->data->client->province_name = & $this->clients_province_m->get_name($client->province_id);;
		$this->data->client->region_name = & $this->clients_region_m->get_name($client->region_id);;
		$this->data->client->city_name = & $this->clients_city_m->get_name($client->city_id);;
		
		$this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->append_metadata(js('diagnose_form.js', 'diagnose'))
				->build('admin/form',$this->data);
	}


  public function set($type = 'default', $client_id = 0)
  {
    if($type == 'default')
    {
        $this->session->set_userdata('selected_client_id','');
        $this->session->set_userdata('set', $type);
        redirect("admin/diagnose");
    }
    $count = $this->diagnose_m->check_record($client_id);
    if($count > 0)
    {
        $this->session->set_flashdata('warning', 'Please Select on the List or '."<a href='admin/diagnose/add/$client_id' class='add'>Diagnose</a>".' a this Client');
        $this->session->set_userdata('selected_client_id',$client_id);
        $this->session->set_userdata('set', $type);
        redirect("admin/diagnose/my_list");
    }
    else
    {
        $this->session->set_flashdata('error', 'This Client has not yet diagnose. Click '."<a href='admin/diagnose/add/$client_id' class='add'>here</a>".' to Diagnose this Client');
        $this->session->set_userdata('selected_client_id',$client_id);
        $this->session->set_userdata('set', $type);
        redirect("admin/diagnose/my_list");
    }
  }

	/**
	 * Delete client 
	 * @access public
	 * @param int $id The ID of the client to delete
	 * @return void
	 */	
  public function delete($id = 0)
	{
		if($id == 0)
		{
        $ids = $this->input->post('action_to');
    
    		if (!empty($ids))
    		{
    			$deleted = 0;
    			$to_delete = 0;
    			foreach ($ids as $id)
    			{
    				if ($this->diagnose_m->delete($id))
    				{
    					$deleted++;
    				}
    				$to_delete++;
    			}
    
    			if ($to_delete > 0)
    			{
    				$this->session->set_flashdata('success', sprintf($this->lang->line('diagnose_mass_delete_success'), $deleted, $to_delete));
    			}
    		}
    		// The array of id's to delete is empty
    		else
    			$this->session->set_flashdata('error', $this->lang->line('diagnose.delete_error'));
    }
    else
    {
        $this->diagnose_m->delete($id)
    			? $this->session->set_flashdata('success', lang('diagnose.delete_success'))
    			: $this->session->set_flashdata('error', lang('diagnose.delete_error'));
		}
		redirect('admin/diagnose');
	}
}
