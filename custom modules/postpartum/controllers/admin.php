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
	
  	protected $section = 'clients';
  	
    private $validation_rules = array(
		array(
			'field' => 'serial_number',
			'label' => 'Family Serial Number',
			'rules' => 'trim|max_length[12]'
		),
		array(
			'field' => 'form_number',
			'label' => 'Form Numbr',
			'rules' => 'trim|max_length[12]'
		),
    array(
			'field' => 'first_name',
			'label' => 'Firstname',
			'rules' => 'required|utf8'
		),
		array(
			'field' => 'last_name',
			'label' => 'Lastname',
			'rules' => 'required|utf8'
		),
		array(
			'field' => 'middle_name',
			'label' => 'Middle Name',
			'rules' => 'utf8'
		),
		array(
			'field' => 'age',
			'label' => 'Age',
			'rules' => 'xss_clean|trim|numeric|max_length[3]'
		),
		array(
			'field' => 'gender',
			'label' => 'Gender',
			'rules' => ''
		),
		array(
			'field' => 'dob_day',
			'label' => 'Day',
			'rules' => 'xss_clean|trim|numeric|max_length[2]|required'
		),
		array(
			'field' => 'dob_month',
			'label' => 'Month',
			'rules' => 'xss_clean|trim|numeric|max_length[2]|required'
		),
		array(
			'field' => 'dob_year',
			'label' => 'Year',
			'rules' => 'xss_clean|trim|numeric|max_length[4]|required'
		),
		array(
			'field' => 'relation',
			'label' => 'Relation',
			'rules' => 'trim'
		),
		array(
			'field' => 'history',
			'label' => 'History',
			'rules' => 'xss_clean'
		),
    array(
			'field' => 'facility',
			'label' => 'Facility',
			'rules' => 'trim'
		),
		array(
			'field' => 'residence',
			'label' => 'Residence',
			'rules' => 'trim'
		),
		array(
			'field' => 'address',
			'label' => 'Address',
			'rules' => 'required'
		),
    array(
			'field' => 'barangay_id',
			'label' => 'Barangay',
			'rules' => 'required'
		),
		array(
			'field' => 'city_id',
			'label' => 'City/Municipality',
			'rules' => 'required'
		),
    array(
			'field' => 'province_id',
			'label' => 'Province',
			'rules' => 'required'
		),
    array(
			'field' => 'region_id',
			'label' => 'Region',
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
		$this->load->model(array('clients_m', 'clients_region_m', 'clients_province_m', 'clients_city_m', 'clients_barangay_m'));
		$this->load->library('form_validation');
		$this->lang->load(array('postpartum', 'clients', 'region', 'province', 'city', 'barangay'));
		$this->output->enable_profiler(TRUE);
		$this->data->regions = array();
		if ($regions = $this->clients_region_m->order_by('name')->get_all())
		{
			foreach ($regions as $region)
			{
				$this->data->regions[$region->id] = $region->name;
			}
		}
		
		$this->data->provinces = array();
		if ($provinces = $this->clients_province_m->order_by('name')->get_all())
		{
			foreach ($provinces as $province)
			{
				$this->data->provinces[$province->id] = $province->name;
			}
		}
		
		$this->data->cities = array();
		if ($cities = $this->clients_city_m->order_by('name')->get_all())
		{
			foreach ($cities as $city)
			{
				$this->data->cities[$city->id] = $city->name;
			}
		}
		
		$this->data->barangays = array();
		if ($barangays = $this->clients_barangay_m->order_by('name')->get_all())
		{
			foreach ($barangays as $barangay)
			{
				$this->data->barangays[$barangay->id] = $barangay->name;
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
		//determine active param
		$base_where['gender'] = $this->input->post('f_module') ? $this->input->post('f_gender') : '';

		//determine group param
	//	$base_where = $this->input->post('f_group') ? $base_where + array('group_id' => (int) $this->input->post('f_group')) : $base_where;
		//age param
		$base_where = $this->input->post('f_age') ? $base_where + array('age' => $this->input->post('f_age')) : $base_where;

		//keyphrase param
		$base_where = $this->input->post('f_keywords') ? $base_where + array('name' => $this->input->post('f_keywords')) : $base_where;
    $this->load->library('pagination');

		// Create pagination links
		$pagination = create_pagination('admin/clients/index', $this->clients_m->count_by($base_where));


		// Using this data, get the relevant results
		$clients = $this->clients_m
			->order_by('last_name', 'ASC')
			->limit($pagination['limit'])
			->get_many_by($base_where);

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);

		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)
			->set_partial('filters', 'admin/partials/filters')  
			->append_metadata(js('admin/filter.js'))
      ->set_partial('clients-list', 'admin/tables/clients');      
				
		$this->input->is_ajax_request() ? $this->template->build('admin/tables/clients', $this->data) : $this->template->build('admin/index', $this->data);
	 
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

	/**
	 * Create a new client role
	 *
	 * @access public
	 * @return void
	 */
	public function add()
	{		
		
    $this->form_validation->set_rules($this->validation_rules);
    if ($_POST)
		{
			
      // Loop through each POST item and add it to the secure_post array
			$secure_post = $this->input->post();

			// Set the full date of birth
			$secure_post['dob'] = mktime(0, 0, 0, $secure_post['dob_month'], $secure_post['dob_day'], $secure_post['dob_year']);

			// Unset the data that's no longer required
			unset($secure_post['dob_month']);
			unset($secure_post['dob_day']);
			unset($secure_post['dob_year']);
    // Set the validation rules
		

		if ($this->form_validation->run() !== FALSE)
		{ 
        $this->clients_m->insert($secure_post)
					? $this->session->set_flashdata('success', 'New Client Added')
					: $this->session->set_flashdata('error', 'Error Adding Client');

				redirect('admin/clients');
		}
		else
		{
			// Dirty hack that fixes the issue of having to re-add all data upon an error
			if ($_POST)
			{
				$client = (object) $_POST;
			}
		}
		}
    // Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$client->{$rule['field']} = set_value($rule['field']);
		}
	
		
    // Fix the months
		$this->lang->load('calendar');
		
		$month_names = array(
			lang('cal_january'),
			lang('cal_february'),
			lang('cal_march'),
			lang('cal_april'),
			lang('cal_mayl'),
			lang('cal_june'),
			lang('cal_july'),
			lang('cal_august'),
			lang('cal_september'),
			lang('cal_october'),
			lang('cal_november'),
			lang('cal_december'),
		);
		
	  $days 	= array_combine($days 	= range(1, 31), $days);
		$months = array_combine($months = range(1, 12), $month_names);
	  $years 	= array_combine($years 	= range(date('Y'), date('Y')-120), $years);
	  

		// Render the view
		$this->data->client = & $client;
		$this->data->days = & $days;
		$this->data->months = & $months;
		$this->data->years = & $years;
		
		$this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->append_metadata(js('clients_form.js', 'clients'))
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
		$client = $this->clients_m->get($id);
		// Make sure we found something
		$client or redirect('admin/clients');

		if ($_POST)
		{
			
      // Loop through each POST item and add it to the secure_post array
			$secure_post = $this->input->post();

			// Set the full date of birth
			$secure_post['dob'] = mktime(0, 0, 0, $secure_post['dob_month'], $secure_post['dob_day'], $secure_post['dob_year']);

			// Unset the data that's no longer required
			unset($secure_post['dob_month']);
			unset($secure_post['dob_day']);
			unset($secure_post['dob_year']);
			
      // Set the validation rules
  		$this->form_validation->set_rules($this->validation_rules);
  
  		if ($this->form_validation->run() !== FALSE)
  		{ 
          $this->clients_m->update($id, $secure_post)
  					? $this->session->set_flashdata('success', 'Client Saved!')
  					: $this->session->set_flashdata('error', 'Error Saving Client');
  
  				redirect('admin/clients');
  		}
  		else
  		{
  				$client = (object) $_POST;
  		}
  		// Loop through each validation rule
  		foreach ($this->validation_rules as $rule)
  		{
  			$client->{$rule['field']} = set_value($rule['field']);
  		}
		}
    else
    {
      if ($client->dob > 0)
  		{
  		    $client->dob_day 	= date('j', $client->dob);
  		    $client->dob_month = date('n', $client->dob);
  		    $client->dob_year = date('Y', $client->dob);
  		}
    }
    // Fix the months
		$this->lang->load('calendar');
		
		$month_names = array(
			lang('cal_january'),
			lang('cal_february'),
			lang('cal_march'),
			lang('cal_april'),
			lang('cal_mayl'),
			lang('cal_june'),
			lang('cal_july'),
			lang('cal_august'),
			lang('cal_september'),
			lang('cal_october'),
			lang('cal_november'),
			lang('cal_december'),
		);
		
	  $days 	= array_combine($days 	= range(1, 31), $days);
		$months = array_combine($months = range(1, 12), $month_names);
	  $years 	= array_combine($years 	= range(date('Y'), date('Y')-120), $years);
	    
	    
		// Render the view
		$this->data->client = & $client;
		$this->data->days = & $days;
		$this->data->months = & $months;
		$this->data->years = & $years;
		
		$this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->append_metadata(js('clients_form.js', 'clients'))
				->build('admin/form', $this->data);
	}



  /**
	 * View a client
	 *
	 * @access public
	 * @param int $id The ID of the client to edit
	 * @return void
	 */
	public function view($id = 0)
	{
		$client = $this->clients_m->get($id);
		// Make sure we found something
		$client or redirect('admin/clients');
                $barangay_name = $this->clients_barangay_m->get_name($client->barangay_id);
		// Render the view
		$this->data->client = & $client;
		$this->data->client->barangay_name = & $this->clients_barangay_m->get_name($client->barangay_id);;
		$this->data->client->province_name = & $this->clients_province_m->get_name($client->province_id);;
		$this->data->client->region_name = & $this->clients_region_m->get_name($client->region_id);;
		$this->data->client->city_name = & $this->clients_city_m->get_name($client->city_id);;
		$this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->build('admin/view', $this->data);
	}



	/**
	 * Delete client 
	 * @access public
	 * @param int $id The ID of the client to delete
	 * @return void
	 */
	public function delete($id = 0)
	{
		$this->clients_m->delete($id)
			? $this->session->set_flashdata('success', lang('clients.delete_success'))
			: $this->session->set_flashdata('error', lang('clients.delete_error'));

		redirect('admin/clients');
	}
}
