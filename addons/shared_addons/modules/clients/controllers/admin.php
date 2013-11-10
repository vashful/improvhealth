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
			'rules' => 'trim|max_length[16]|required'
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
			'rules' => 'required|utf8'
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
			'field' => 'dob',
			'label' => 'Date of Birth',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'email',
			'label' => 'Email Address',
			'rules' => 'trim|valid_email'
		),
		array(
			'field' => 'phonenumber',
			'label' => 'Phone Number/Mobile Number',
			'rules' => 'trim'
		),
		array(
			'field' => 'bloodtype',
			'label' => 'Blood Type',
			'rules' => 'trim'
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
			'field' => 'philhealth',
			'label' => 'Philhealth',
			'rules' => ''
		),
		array(
			'field' => 'philhealth_type',
			'label' => 'Philhealth Type',
			'rules' => ''
		),
		    array(
			'field' => 'philhealth_sponsor',
			'label' => 'Philhealth Sponsor',
			'rules' => ''
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
		$this->load->model(array('clients_m', 'clients_region_m', 'clients_province_m', 'clients_city_m', 'clients_barangay_m','consultations/consultations_m'));
		$this->load->library('form_validation');
        $this->load->library('fpdf');
		$this->lang->load(array('clients', 'region', 'province', 'city', 'barangay'));
		$this->output->enable_profiler(FALSE);
		$this->data->regions = array();
		if ($regions = $this->clients_region_m->order_by('id')->get_all())
		{
			foreach ($regions as $region)
			{
				$this->data->regions[$region->id] = $region->name;
			}
		}
		
		$this->data->provinces = array();
		if ($provinces = $this->clients_province_m->order_by('id')->get_all())
		{
			foreach ($provinces as $province)
			{
				$this->data->provinces[$province->id] = $province->name;
			}
		}
		
		$this->data->cities = array();
		if ($cities = $this->clients_city_m->order_by('id')->get_all())
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
    
    //barangay param   
    $base_where = $this->input->post('f_barangays') ? $base_where + array('barangay' => $this->input->post('f_barangays')) : $base_where;

		//determine group param
	    //	$base_where = $this->input->post('f_group') ? $base_where + array('group_id' => (int) $this->input->post('f_group')) : $base_where;
		//age param
		$base_where = $this->input->post('f_age') ? $base_where + array('age' => $this->input->post('f_age')) : $base_where;

		//year param    
		$base_where = $this->input->post('by_year') ? $base_where + array('by_year' => $this->input->post('by_year')) : $base_where;
        
        //keyphrase param
		$base_where = $this->input->post('f_keywords') ? $base_where + array('name' => $this->input->post('f_keywords')) : $base_where;
        //$this->load->library('pagination');
    
        //Set parameters session
        $this->session->set_userdata('base_where',$base_where);

		// Create pagination links
		$pagination = create_pagination('admin/clients/index', $this->clients_m->count_by($base_where));
        
        $year = date("Y");
         for($i=$year; $i >= $year - 10; $i--)
        {
            $years[$i]  = $i;
        }
        $this->data->year = $year;
		$this->data->years= $years;

		// Using this data, get the relevant results
		$clients = $this->clients_m
			->order_by('last_name', 'ASC')
			->limit($pagination['limit'])
			->get_many_by($base_where);
            
        $this->data->totalitems = $this->clients_m->count_by($base_where);
        $lastpage = $pagination['current_page']+$pagination['per_page'];
        
        $this->data->item_start = $pagination['current_page']+1;
        
        if($lastpage>$this->data->totalitems)
        {
            $lastpagediff = $lastpage-$this->data->totalitems;
            $this->data->item_end = ($pagination['current_page']+$pagination['per_page'])-$lastpagediff;
        }
        else    
        {
            $this->data->item_end = $pagination['current_page']+$pagination['per_page'];
        }
            
        if($this->session->userdata('on_search'))
		  if(count($clients))
		      if(count($clients)>1)
		          $this->data->messages = array('success' => 'There are '.count($clients).' Result(s) Found.');
		      else
		          $this->data->messages = array('success' => '1 Client Found.');
		  else
               $this->data->messages = array('error' => 'No Record of "'.$this->input->post('f_keywords').'" Found. Click '."<a href='admin/clients/add' class='add'>here</a>".' to Register a New Client');
		
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
		  if ($this->input->post('dob'))
  		{
  			$secure_post['dob'] = strtotime($this->input->post('dob'));  
  		}
  		else
  		{
  			$secure_post['dob'] = '';  
  		}

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

		// Render the view
		$this->data->client = & $client;
		
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
		  if ($this->input->post('dob'))
  		{
  			$secure_post['dob'] = strtotime($this->input->post('dob'));  
  		}
  		else
  		{
  			$secure_post['dob'] = '';  
  		}
			
      // Set the validation rules
  		$this->form_validation->set_rules($this->validation_rules);
  
  		if ($this->form_validation->run() !== FALSE)
  		{ 
          $this->clients_m->update($id, $secure_post)
  					? $this->session->set_flashdata('success', 'Client Saved!')
  					: $this->session->set_flashdata('error', 'Error Saving Client');
  
  				redirect('admin/clients/view/'.$id);
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
      $client->dob= $client->dob ? date('Y-m-d', $client->dob) : ''; 
    }
      
		// Render the view
		$this->data->client = & $client;
		
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
		
    $consultations = $this->consultations_m->get_all_consultations($id);
    $this->data->consultations = & $consultations; 
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
		if($id == 0)
		{
        $ids = $this->input->post('action_to');
    
    		if (!empty($ids))
    		{
    			$deleted = 0;
    			$to_delete = 0;
    			foreach ($ids as $id)
    			{
    				if ($this->clients_m->delete($id))
    				{
    					$deleted++;
    				}
    				$to_delete++;
    			}
    
    			if ($to_delete > 0)
    			{
    				$this->session->set_flashdata('success', sprintf($this->lang->line('clients_mass_delete_success'), $deleted, $to_delete));
    			}
    		}
    		// The array of id's to delete is empty
    		else
    			$this->session->set_flashdata('error', $this->lang->line('clients.delete_error'));
    }
    else
    {
        $this->clients_m->delete($id)
    			? $this->session->set_flashdata('success', lang('clients.delete_success'))
    			: $this->session->set_flashdata('error', lang('clients.delete_error'));
		}
		redirect('admin/clients');
	}
    
    /**
	 * Print a client list
	 *
	 * @access public
	 * @param int $id The ID of the client to edit
	 * @return void
	 */
    
    public function print_page()
    {
        $base_where = $this->session->userdata('base_where');
            
        // Using this data, get the relevant results
        $clients = $this->clients_m
        ->order_by('last_name', 'ASC')
        ->get_many_by($base_where);
        
        $totalitems = count($clients);     
        
        $this->fpdf->Open();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
                            
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
        $this->fpdf->SetY(10);
        $this->fpdf->SetFont('Helvetica','B',16);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(0,4,strtoupper('Target Client List'),0,0,'C');
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFillColor(208,208,255);
        $fill = true;
        $this->fpdf->SetFont('Helvetica','B',8);
        $this->fpdf->Cell(12,4,'NO.','LTRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(25,4,'FAMILY','LTRb',0,'C',$fill);
        $this->fpdf->Cell(50,4,'NAME','LTRb',0,'C',$fill);
        $this->fpdf->Cell(50,4,'ADDRESS','LTRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'AGE','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'GENDER','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'DATE OF','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'DATE','LTRb',0,'C',$fill);
        $this->fpdf->Cell(30,4,'RELATION','LTRb',0,'C',$fill);
        $this->fpdf->Cell(25,4,'PHILHEALTH','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'PHILHEALTH','LTRb',1,'C',$fill);  
        
        $this->fpdf->SetFont('Helvetica','B',8);
        $this->fpdf->Cell(12,4,'','LtRB',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(25,4,'SERIAL NO.','LtRB',0,'C',$fill);
        $this->fpdf->Cell(50,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(50,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(10,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'BIRTH','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'REGISTERED','LtRB',0,'C',$fill);
        $this->fpdf->Cell(30,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(25,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'TYPE','LtRB',1,'C',$fill);    
    
        $i = 1;
        $x = 1;
        foreach ($clients as $client)
        {                             
            $age = floor((time() - $client->dob)/31556926); 
            if(!$age)
                {$age = '-';}
                
            $brgy = $this->clients_barangay_m->get_name($client->barangay_id);   
            if(!$client->address)
                    {$address = $brgy;} 
            elseif(!$brgy)
                    {$address = $client->address;} 
            else 
                    {$address = $brgy.', '.$client->address;}
            
            $dob = $client->dob ? date('M j, Y', $client->dob): ''; 
            $registration_date = $client->registration_date ? date('M j, Y', $client->registration_date): '';         
            
            $this->fpdf->SetFillColor(230,230,230);             
            $modx = $x%2;
            if($modx==0) 
            {$fill=false;}
            else
            {$fill=true;}
            
            $this->fpdf->SetFont('Helvetica','B',7.5);
            $this->fpdf->Cell(12,8,$x.'.',1,0,'L',$fill);
            $this->fpdf->SetFont('Helvetica','',7.5);
            $this->fpdf->Cell(25,8,$client->serial_number,1,0,'L',$fill);
            $this->fpdf->Cell(50,8,trim($client->last_name).', '.trim($client->first_name).' '.trim($client->middle_name),1,0,'L',$fill);
            $this->fpdf->Cell(50,8,$address,1,0,'L',$fill);
            $this->fpdf->Cell(10,8,$age,1,0,'C',$fill);
            $this->fpdf->Cell(15,8,ucfirst($client->gender),1,0,'L',$fill);
            $this->fpdf->Cell(20,8,$dob,1,0,'R',$fill);
            $this->fpdf->Cell(20,8,$registration_date,1,0,'R',$fill);         
            $this->fpdf->Cell(30,8,ucwords($client->relation),1,0,'L',$fill);
            $this->fpdf->Cell(25,8,$client->philhealth,1,0,'L',$fill);
            $this->fpdf->Cell(20,8,$client->philhealth_type,1,1,'L',$fill);
            
            if($i==20)
            {                       
                if($x<$totalitems)
                {
                    $this->fpdf->SetY(-15);
                    $this->fpdf->SetTextColor(128);
                    $this->fpdf->SetFont('Helvetica','I',7);
                    $this->fpdf->Cell(10,4,'Page '.$this->fpdf->PageNo().'/{nb}',0,0,'C');
                    $this->fpdf->SetFont('Helvetica','',7);
                    $this->fpdf->Cell(0,4,'Copyright © 2013 IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
                  
                    $this->fpdf->AddPage('L','A4');
                    $i = 1;              
                    $this->fpdf->SetTextColor(0,0,0);
                    
                    $this->fpdf->SetFont('Helvetica','',8);
        
                    $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
                    $this->fpdf->SetY(10);
                    $this->fpdf->SetFont('Helvetica','B',16);
                    $this->fpdf->SetTextColor(0,0,0);
                    $this->fpdf->Cell(0,4,strtoupper('Target Client List'),0,0,'C');
                    $this->fpdf->SetFont('Helvetica','',8);
                    
                    $this->fpdf->SetY(15);
                    $this->fpdf->SetFillColor(208,208,255);
                    $fill = true;
                    $this->fpdf->SetFont('Helvetica','B',8);
                    $this->fpdf->Cell(12,4,'NO.','LTRb',0,'C',$fill);
                    $this->fpdf->SetFont('Helvetica','',8);
                    $this->fpdf->Cell(25,4,'FAMILY','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(50,4,'NAME','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(50,4,'ADDRESS','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(10,4,'AGE','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(15,4,'GENDER','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'DATE OF','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'DATE','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(30,4,'RELATION','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(25,4,'PHILHEALTH','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'PHILHEALTH','LTRb',1,'C',$fill);  
                    
                    $this->fpdf->SetFont('Helvetica','B',8);
                    $this->fpdf->Cell(12,4,'','LtRB',0,'C',$fill);
                    $this->fpdf->SetFont('Helvetica','',8);
                    $this->fpdf->Cell(25,4,'SERIAL NO.','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(50,4,'','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(50,4,'','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(10,4,'','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'BIRTH','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'REGISTERED','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(30,4,'','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(25,4,'','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'TYPE','LtRB',1,'C',$fill);                  
                }
            }
            else
            {
                $i=$i+1;                             
            }       
            $x=$x+1;     
        }
        
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',7);
        $this->fpdf->Cell(10,4,'Page '.$this->fpdf->PageNo().'/{nb}',0,0,'C');
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(0,4,'Copyright © 2013 IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
    
        $this->fpdf->Output('Target_Client_List.pdf','I');                  
    }
}
