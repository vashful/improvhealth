<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Roles controller for the family module
 *
 * @author Phil Sturgeon - PyroCMS Dev Team
 * @package PyroCMS
 * @subpackage Groups Module
 * @category Modules
 *
 */
class Admin extends Admin_Controller
{
	
  	protected $section = 'filariasis';
  	
    private $validation_rules = array(
    array(
			'field' => 'client_id',
			'label' => 'Client ID',
			'rules' => 'required'
		),
		/*array(
			'field' => 'six_months',
			'label' => 'Six Months',
			'rules' => 'callback_check_data_vitamin[diagnosis,date_given]'
		),
		array(
			'field' => 'twelve_months',
			'label' => 'Twelve Months',
			'rules' => ''
		),
		array(
			'field' => 'sixty_months',
			'label' => 'Sixty Months',
			'rules' => ''
		),*/
		array(
			'field' => 'examined',
			'label' => 'Case examined',
			'rules' => ''
		),
		array(
			'field' => 'positive',
			'label' => 'Case positive',
			'rules' => ''
		),
		array(
			'field' => 'mf',
			'label' => 'MF in the slides found (+)',
			'rules' => ''
		),
		array(
			'field' => 'given_mda',
			'label' => 'Person given MDA',
			'rules' => ''
		),
		array(
			'field' => 'adeno',
			'label' => 'Adenolymphangitis Case',
			'rules' => ''
		),
		array(
			'field' => 'date_given',
			'label' => 'Date',
			'rules' => ''
		),
		/*array(
			'field' => 'anemic_age',
			'label' => 'Age in Months (Anemic Children)',
			'rules' => 'trim|numeric|max_length[3]|callback_check_data[date_started,date_completed]'
		),
		array(
			'field' => 'date_started',
			'label' => 'Date Stared',
			'rules' => ''
		),
		array(
			'field' => 'date_completed',
			'label' => 'Date Completed',
			'rules' => ''
		),
		array(
			'field' => 'diarrhea_age',
			'label' => 'Age in Months (Diarrhea Cases)',
			'rules' => 'trim|numeric|max_length[3]|callback_check_data[ort,ors,ors_zinc]'
		),
		array(
			'field' => 'ort',
			'label' => 'Date Given ORT',
			'rules' => ''
		),
		array(
			'field' => 'ors',
			'label' => 'Date Given ORS',
			'rules' => ''
		),
		array(
			'field' => 'ors_zinc',
			'label' => 'Date Given ORS with Zinc',
			'rules' => ''
		),
		array(
			'field' => 'pneumonia_age',
			'label' => 'Age in Months (Pneumonia Cases)',
			'rules' => 'trim|numeric|max_length[3]|callback_check_data[date_given_treatment]'
		),
		array(
			'field' => 'date_given_treatment',
			'label' => 'Date Given Treatment',
			'rules' => ''
		),*/
		array(
			'field' => 'remarks',
			'label' => 'Remarks',
			'rules' => 'xss_clean|trim'
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
		$this->load->model(array('filariasis_m','clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m'));
		$this->load->library('form_validation');
        $this->load->library('fpdf'); // Load FPDF library
		$this->lang->load(array('filariasis','clients/clients'));     
		//$this->output->enable_profiler(TRUE);
		
		$this->data->client_types = array(
                  'CU'  => 'Current Users',
                  'NA'  => 'New Acceptors',
                  'CM'  => 'Changing Method',
                  'CC'  => 'Changing Clinic',
                  'RS'  => 'Restart');
    
		$this->data->diagnosis_list = array(
						      ''  => '-- Please Select --',
                  'A'  => 'TB symptomatics who underwent DSSM',
                  'B'    => 'Smear (+) discovered',
                  'C'    => 'New smear (+) case initiated treatment',
                  'D'    => 'New smear (+) case got cured',
                  'E'    => 'Smear (+) retreatment case initiated treatment',
                  'F'    => 'Smear (+) retreatment case got cured'
                );
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
		if($this->session->userdata('selected_client_id'))
		redirect('admin/filariasis/my_list');
		
    //determine active param
		//gender param
        $base_where['gender'] = $this->input->post('f_module') ? $this->input->post('f_gender') : '';
    //barangay param   
    $base_where = $this->input->post('f_barangays') ? $base_where + array('barangay' => $this->input->post('f_barangays')) : $base_where;
       
		//year param    
        $base_where = $this->input->post('by_year') ? $base_where + array('by_year' => $this->input->post('by_year')) : $base_where;
        
        //status param
		$base_where = $this->input->post('f_cases') ? $base_where + array('cases' => $this->input->post('f_cases')) : $base_where;
    
        //age param
        $base_where = $this->input->post('f_age') ? $base_where + array('age' => $this->input->post('f_age')) : $base_where;
    
		//keyphrase param
		$base_where = $this->input->post('f_keywords') ? $base_where + array('name' => $this->input->post('f_keywords')) : $base_where;

		//Set parameters session
       $this->session->set_userdata('base_where',$base_where);
       
        // Create pagination links
		$pagination = create_pagination('admin/filariasis/index', $this->filariasis_m->count_by($base_where));
        
        $year = date("Y");
        for($i=$year; $i >= $year - 10; $i--)
        {
            $years[$i]  = $i;
        }
        $this->data->year = $year;
		$this->data->years= $years;

		// Using this data, get the relevant results
		$clients = $this->filariasis_m->get_results($pagination['limit'],$base_where);
        
        $this->data->totalitems = $this->filariasis_m->count_by($base_where);
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

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);



		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)      
			->set_partial('filters', 'admin/partials/filters')   
			->append_metadata(js('admin/filter.js'))
                        ->set_partial('filariasis-list', 'admin/tables/clients');
       				
		$this->input->is_ajax_request() ? $this->template->build('admin/tables/clients', $this->data) : $this->template->build('admin/index', $this->data);	 
	}
	
	
	public function my_list()
	{
		$selected_client = $this->clients_m->get($this->session->userdata('selected_client_id'));
		$selected_client or redirect('admin/filariasis');
		
    //determine active param
		$base_where['client_id'] = $this->session->userdata('selected_client_id');
		
				// Create pagination links
		$pagination = create_pagination('admin/filariasis/my_list', $this->filariasis_m->count_by($base_where));

		// Using this data, get the relevant results
		$clients = $this->filariasis_m->get_results($pagination['limit'],$base_where);

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);
		
    $this->data->selected_client = & $selected_client;


		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)      
      ->set_partial('filariasis-my_list', 'admin/tables/my_list');
       				
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
				redirect('admin/filariasis');
				break;
		}
	}

  public function add_client()
	{		
                $this->session->set_flashdata('warning', 'Search and Select a Client to be Added to "Filariasis" Or <a href="admin/clients/add" class="success-link">Register</a> a New Client.');
                $this->session->set_userdata('current_action', 'filariasis'); 
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
    $this->session->unset_userdata('current_action'); 
    if($this->session->userdata('consultation_id') != $client_id)
    {
     $this->session->set_flashdata('warning', 'Please Add a New Consultation for this Client before Adding a Record for Filariasis');
     redirect('admin/consultations/add/'.$client_id);
    } 
    
    $client = $this->clients_m->get($client_id);
    $client or redirect('admin/filariasis');
    
    $this->form_validation->set_rules($this->validation_rules);
    if ($this->input->post('date_given'))
		{
			$date_given = strtotime($this->input->post('date_given'));  
		}
		else
		{
			$date_given = '';  
		}
		
		    
    if ($_POST)
		{
      // Loop through each POST item and add it to the secure_post array
      $secure_post = $this->input->post();
      
      // Set the full date of birth
      $secure_post['date_given'] = $date_given;
      

          		if ($this->form_validation->run() !== FALSE)
          		{ 
                                if($this->filariasis_m->insert($secure_post))
                                {
                                  $this->session->set_flashdata('success', 'New Filariasis cient added');
                                  $this->session->set_userdata('consultation_id', 0);
                                  if($this->session->userdata('selected_client_id'))
                                    redirect('admin/filariasis/my_list');
                                  else
                                    redirect('admin/filariasis');
                                }
                                else 
                                {
                                $this->session->set_flashdata('error', 'Error adding Filariasis client/ No data has been entered. Be sure to fill up remarks');
                                redirect("admin/filariasis/add/$client_id");
                                }
                                
                                
          		}
          		else
          		{
          			// Dirty hack that fixes the issue of having to re-add all data upon an error
          			$sc = (object) $_POST;
          		}      
		}
                // Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$sc->{$rule['field']} = set_value($rule['field']);
		}
		

		$sc->method_action = 'add';
		$sc->client_id = $client_id;
    $sc->date_given = $date_given;

		// Render the view
		$this->data->sc = & $sc;
		$this->data->client = & $client;
		$this->data->client->barangay_name = & $this->clients_barangay_m->get_name($client->barangay_id);;
		$this->data->client->province_name = & $this->clients_province_m->get_name($client->province_id);;
		$this->data->client->region_name = & $this->clients_region_m->get_name($client->region_id);;
		$this->data->client->city_name = & $this->clients_city_m->get_name($client->city_id);;
		

		$this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->build('admin/form',$this->data);
	}

  public function set($type = 'default', $client_id = 0)
  { 
    if($type == 'default')
    {
        $this->session->set_userdata('selected_client_id','');
        $this->session->set_userdata('set', $type);
        redirect("admin/filariasis");
    }
    $count = $this->filariasis_m->check_record($client_id);
    if($count > 0)
    {
        $this->session->set_flashdata('warning', 'Please Select on the List or '."<a href='admin/filariasis/add/$client_id' class='add'>Add</a>".' New Record Under Filariasis for this Client');
        $this->session->set_userdata('selected_client_id',$client_id);
        $this->session->set_userdata('set', $type);
        redirect("admin/filariasis/my_list");
    }
    else
    {
        $this->session->set_flashdata('error', 'No Record Under Filariasis Found for this Client. Click '."<a href='admin/filariasis/add/$client_id' class='add'>here</a>".' to Add New Record for this Client Under Filariasis');
        $this->session->set_userdata('selected_client_id',$client_id);
        $this->session->set_userdata('set', $type);
        redirect("admin/filariasis/my_list");
    }
    
  
  }

	/**
	 * Edit a client role
	 *
	 * @access public
	 * @param int $id The ID of the client to edit
	 * @return void
	 */
	public function edit($id = 0)
	{
	  $sc = $this->filariasis_m->get_filariasis_details($id);
	  
		$client = $this->clients_m->get($sc->client_id);
    $client or redirect('admin/filariasis');
    
    $this->form_validation->set_rules($this->validation_rules);
    if ($this->input->post('date_given'))
		{
			$date_given = strtotime($this->input->post('date_given'));  
		}
		else
		{
			$date_given = '';  
			$sc->date_given = $sc->date_added ? date('Y-m-d',$sc->date_added) : ''; 
		}
		
		    
    if ($_POST)
		{
      // Loop through each POST item and add it to the secure_post array
      $secure_post = $this->input->post();
      
      // Set the full date of birth
      $secure_post['date_given'] = $date_given;
                
          		if ($this->form_validation->run() !== FALSE)
          		{ 
                                if($this->filariasis_m->update($id, $secure_post))
                                {
                                  $this->session->set_flashdata('success', 'Filariasis Client successfully updated');
                                  if($this->session->userdata('selected_client_id'))
                                  redirect('admin/filariasis/my_list');
                                  else
                                  redirect('admin/filariasis');
                                }
                                else 
                                {
                                  $this->session->set_flashdata('error', 'Error updating Filariasis Client/No Data has been entered.');
                                  redirect("admin/filariasis/edit/$id");
                                }
                                
                                
          		}
          		else
          		{
          			// Dirty hack that fixes the issue of having to re-add all data upon an error
          			$sc = (object) $_POST;
          		}
          		foreach ($this->validation_rules as $rule)
          		{
          			$sc->{$rule['field']} = set_value($rule['field']);
          		}
		}

    	$sc->method_action = 'edit';
		// Render the view
		$this->data->sc = & $sc;
		$this->data->client = & $client;
		$this->data->client->barangay_name = & $this->clients_barangay_m->get_name($client->barangay_id);;
		$this->data->client->province_name = & $this->clients_province_m->get_name($client->province_id);;
		$this->data->client->region_name = & $this->clients_region_m->get_name($client->region_id);;
		$this->data->client->city_name = & $this->clients_city_m->get_name($client->city_id);;


		$this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->build('admin/form',$this->data);
	}

	/**
	 * Delete client role(s)
	 *
	 * @access public
	 * @param int $id The ID of the client to delete
	 * @return void
	 */
	public function delete($id = 0)
	{
		if($id == 0)
		{
        $ids = $this->input->post('action_to');
    	echo $ids;die();
    		if (!empty($ids))
    		{
    			$deleted = 0;
    			$to_delete = 0;
    			foreach ($ids as $id)
    			{
    				if ($this->filariasis_m->delete($id))
    				{
    					$deleted++;
    				}
    				$to_delete++;
    			}
    
    			if ($to_delete > 0)
    			{
    				$this->session->set_flashdata('success', sprintf($this->lang->line('filariasis_mass_delete_success'), $deleted, $to_delete));
    			}
    		}
    		// The array of id's to delete is empty
    		else
    			$this->session->set_flashdata('error', $this->lang->line('filariasis.delete_error'));
    }
    else
    {
        $this->filariasis_m->delete($id)
    			? $this->session->set_flashdata('success', lang('filariasis.delete_success'))
    			: $this->session->set_flashdata('error', lang('filariasis.delete_error'));
		}
	  if($this->session->userdata('selected_client_id'))
      redirect('admin/filariasis/my_list');
    else
		  redirect('admin/filariasis');
	}
	
	public function check_data_vitamin($str, $fields)
	{
    foreach (explode(',', $fields) as $field)
  	{
  		if (empty($_POST['six_months']) && empty($_POST['twelve_months']) && empty($_POST['sixty_months']) && !empty($_POST[$field]))
  		{
  			$this->form_validation->set_message('check_data_vitamin', 'Please check the corresponding month for Vitamin A supplementation or Unselect Diagnosis and remove Date Given.');
  			return FALSE;
  		}
  	}
		return TRUE;
	}
	
	public function check_data($str, $fields)
	{
    foreach (explode(',', $fields) as $field)
  	{
  		if (!$str && !empty($_POST[$field]))
  		{
  			$this->form_validation->set_message('check_data', 'The %s field is required.');
  			return FALSE;
  		}
  	}
		return TRUE;
	}
    
    public function print_page()
	{
		$base_where = $this->session->userdata('base_where');
  
		// Create pagination links
		$pagination = create_pagination('admin/sick_children/index', $this->sc_m->count_by($base_where));

		// Using this data, get the relevant results
		$clients = $this->sc_m->get_results($pagination['limit'],$base_where);
        $totalitems = count($clients);
        if($totalitems<1)
            {$totalitems = 1;}
        $totalpage = $totalitems/16;
        $remainder = $totalitems%16;
        if($remainder!=0)
        {
            if($remainder>=8)
            {$totalpages=number_format($totalpage);}
            else
            {$totalpages=number_format($totalpage)+1;}
        }      
        
        $this->fpdf->Open();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
                            
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
        $this->fpdf->SetY(10);
        $this->fpdf->SetFont('Helvetica','B',16);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(0,4,strtoupper('Target Client List for Sick Children'),0,0,'C');
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->header_page1();        
        
        $i = 1;
        $x = 1;
        $currentpage = 1;
        foreach ($clients as $client)        
        {                             
                $age = floor((time() - $client->dob)/31556926); 
                if(!$age)
                    {$age = '-';} 
                
                $brgy = $this->clients_barangay_m->get_name($client->barangay_id);
                
                if(!$client->address)
                        {$address = $client->$brgy;} 
                elseif(!$client->residence)
                        {$address = $brgy;} 
                else 
                        {$address = $brgy.','.$client->address;} 
                
                $dob = $client->dob ? date('m-d-Y', $client->dob): '-';
                $registration_date = $client->registration_date ? date('m-d-Y', $client->registration_date): '-'; 
                
                $date_added = $client->date_added ? date('Y-m-d', $client->date_added): '-';  
                $date_given = $client->date_given ? date('Y-m-d', $client->date_given): '-';  
                $date_started = $client->date_started ? date('Y-m-d', $client->date_started): '-';    
                $date_completed = $client->date_completed ? date('Y-m-d', $client->date_completed): '-';    
                $ort = $client->ort ? date('Y-m-d', $client->ort): '-';    
                $ors = $client->ors ? date('Y-m-d', $client->ors): '-';
                $ors_zinc = $client->ors_zinc ? date('Y-m-d', $client->ors_zinc): '-';    
                $date_given_treatment = $client->date_given_treatment ? date('Y-m-d', $client->date_given_treatment): '-';    
                
                if($client->six_months==1)
                    {$six_months = "Y";} 
                else
                    {$six_months = "";} 
                if($client->twelve_months==1)
                    {$twelve_months = "Y";} 
                else
                    {$twelve_months = " ";}   
                if($client->sixty_months==1)
                    {$sixty_months = "Y";}   
                else
                    {$sixty_months = " ";}   
                
                $diagnosis = $client->diagnosis ? $this->data->diagnosis_list[$client->diagnosis]: '-';  
                
                $this->fpdf->SetFillColor(230,230,230);             
                $modx = $x%2;
                if($modx==0) 
                {$fill=false;}
                else
                {$fill=true;} 
                
                $this->fpdf->SetFont('Helvetica','B',7.5,$fill);
                $this->fpdf->Cell(12,8,$i.'.',1,0,'L',$fill);
                $this->fpdf->SetFont('Helvetica','',7.5,$fill);
                $this->fpdf->Cell(20,8,$registration_date,1,0,'C',$fill); 
                $this->fpdf->Cell(20,8,$client->serial_number,1,0,'C',$fill); 
                $this->fpdf->Cell(60,8,trim($client->last_name).', '.trim($client->first_name).' '.trim($client->middle_name),1,0,'L',$fill);
                $this->fpdf->Cell(20,8,$dob,1,0,'C',$fill);
                $this->fpdf->Cell(10,8,$age,1,0,'C',$fill);
                $this->fpdf->Cell(60,8,$address,1,0,'L',$fill); 
                $this->fpdf->Cell(10,8,$six_months,1,0,'C',$fill);
                $this->fpdf->Cell(10,8,$twelve_months,1,0,'C',$fill);
                $this->fpdf->Cell(10,8,$sixty_months,1,0,'C',$fill); 
                $this->fpdf->Cell(25,8,$diagnosis,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$date_given,1,1,'C',$fill);  
                
                if($i==17)
                {
                    if($x<$totalitems)
                    {
                        $this->tcl_header();
                        $this->legend_page1();
                        $this->footer();
                        $this->tcl_header();
                        $this->legend_page1();
                        $this->footer();
                        $this->fpdf->SetY(-15);
                        $this->fpdf->SetTextColor(128);
                        $this->fpdf->SetFont('Helvetica','I',7);
                        $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
                        
                        $i = 1;
                        $currentpage = $currentpage + 1;
                        $this->fpdf->AddPage('L','A4');
                        $this->fpdf->SetAutoPageBreak(true, 10);
                                            
                        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
                        $this->fpdf->SetY(10);
                        $this->fpdf->SetFont('Helvetica','B',16);
                        $this->fpdf->SetTextColor(0,0,0);
                        $this->fpdf->Cell(0,4,strtoupper('Sick Children Client\'s List'),0,0,'C');
                        $this->fpdf->SetFont('Helvetica','',8);
                        
                        $this->header_page1();
                                                
                    }
                }
                else
                {
                    $i=$i+1;
                }  
                $x=$x+1;         
        }
        
        $this->tcl_header();
        $this->legend_page1();
        $this->footer();
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',7);
        $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
        
        //============================= Second Page ============================//
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
                            
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
        $this->fpdf->SetY(10);
        $this->fpdf->SetFont('Helvetica','B',16);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(0,4,strtoupper('Target Client List for Sick Children'),0,0,'C');
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->header_page2();        
        
        $i = 1;
        $x = 1;
        $currentpage = 1;
        foreach ($clients as $client)        
        {                              
                
                $date_added = $client->date_added ? date('Y-m-d', $client->date_added): '-';  
                $date_given = $client->date_given ? date('Y-m-d', $client->date_given): '-';  
                $date_started = $client->date_started ? date('Y-m-d', $client->date_started): '-';    
                $date_completed = $client->date_completed ? date('Y-m-d', $client->date_completed): '-';    
                $ort = $client->ort ? date('Y-m-d', $client->ort): '-';    
                $ors = $client->ors ? date('Y-m-d', $client->ors): '-';
                $ors_zinc = $client->ors_zinc ? date('Y-m-d', $client->ors_zinc): '-';    
                $date_given_treatment = $client->date_given_treatment ? date('Y-m-d', $client->date_given_treatment): '-';    
                
                $diagnosis = $client->diagnosis ? $this->data->diagnosis_list[$client->diagnosis]: '-';  
                
                $this->fpdf->SetFillColor(230,230,230);             
                $modx = $x%2;
                if($modx==0) 
                {$fill=false;}
                else
                {$fill=true;} 
                
                $this->fpdf->SetFont('Helvetica','B',7.5,$fill);
                $this->fpdf->Cell(12,8,$i.'.',1,0,'L',$fill);
                $this->fpdf->SetFont('Helvetica','',7.5,$fill);
                $this->fpdf->Cell(20,8,$age,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$date_started,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$date_completed,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$age,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$ort,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$ors,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$ors_zinc,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$age,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$date_given_treatment,1,0,'C',$fill);
                $this->fpdf->Cell(85,8,$client->remarks,1,1,'L',$fill);
    
                if($i==17)
                {
                    if($x<$totalitems)
                    {
                        $this->legend_page2();
                        $this->footer();
                        $this->fpdf->SetY(-15);
                        $this->fpdf->SetTextColor(128);
                        $this->fpdf->SetFont('Helvetica','I',6);
                        $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
                        
                        $i = 1;
                        $currentpage = $currentpage + 1;
                        $this->fpdf->AddPage('L','A4');
                        $this->fpdf->SetAutoPageBreak(true, 10);
                                            
                        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
                        $this->fpdf->SetY(10);
                        $this->fpdf->SetFont('Helvetica','B',16);
                        $this->fpdf->SetTextColor(0,0,0);
                        $this->fpdf->Cell(0,4,strtoupper('Sick Children Client\'s List'),0,0,'C');
                        $this->fpdf->SetFont('Helvetica','',8);
                        
                        $this->header_page2();
                                                
                    }
                }
                else
                {
                    $i=$i+1;
                }  
                $x=$x+1;         
        }
        
        $this->legend_page2();
        $this->footer();
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',7);
        $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
        
        $this->fpdf->Output('TCP_Sick_Children.pdf','I');       
	} 
    
    function footer()
    {
        $year = date('Y');
        //Draw a line
        $this->fpdf->SetY(-15);
        
        //Page footer
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->Cell(0,4,'Copyright © '.$year.' IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
    } 
    
    function tcl_header()
    {
        //Draw a line
        $this->fpdf->SetY(5);
        $this->fpdf->SetX(255);
        
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','B',20);
        $this->fpdf->Cell(30,10,'TCL - SICK',0,1,'C');
    } 
    
    function header_page1()
    {
        $this->fpdf->SetY(15);
        $this->fpdf->SetFillColor(208,208,255);
        $fill = true;
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(12,4,'',1,0,'L',$fill);
        $this->fpdf->Cell(20,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'',1,0,'L',$fill);
        $this->fpdf->Cell(20,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'',1,0,'L',$fill);
        $this->fpdf->Cell(60,4,'',1,0,'L',$fill);
        $this->fpdf->Cell(75,4,'VITAMIN A SUPPLEMENTATION','LTRb',1,'C',$fill); 
        
        $this->fpdf->SetFont('Helvetica','B',8);
        $this->fpdf->Cell(12,4,'NO.','LtRb',0,'L',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(20,4,'DATE OF','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'FAMILY','LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'NAME OF CHILD','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'DATE OF','LtRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'AGE','LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'COMPLETE ADDRESS','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->Cell(75,4,'(8)','LtRB',1,'C',$fill); 
         
        
        $this->fpdf->Cell(12,4,'','LtRb',0,'L',$fill);
        $this->fpdf->Cell(20,4,'REGIS-','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'SERIAL','LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'','LtRb',0,'L',$fill);
        $this->fpdf->Cell(20,4,'BIRTH','LtRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'','LtRb',0,'L',$fill);
        $this->fpdf->Cell(60,4,'','LtRb',0,'L',$fill);
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->Cell(30,4,'Put (Y) if Yes','LTRB',0,'C',$fill);        
        $this->fpdf->Cell(25,4,'DIAGNOSIS/','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'DATE','LTRb',1,'C',$fill);   
        
        $this->fpdf->Cell(12,4,'','LtRb',0,'L',$fill);
        $this->fpdf->Cell(20,4,'TRATION','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'NUMBER','LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->Cell(10,4,'6-11','LTtRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'12-59','LTtRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'60-71','LTtRb',0,'C',$fill);
        $this->fpdf->Cell(25,4,'FINDINGS*','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'GIVEN**','LtRb',1,'C',$fill);         
        
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->Cell(12,4,'(1)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'(2)','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'(3)','LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'(4)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'(5)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(10,4,'(6)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(60,4,'(7)','LtRB',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->Cell(10,4,'MOS.','LtRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'MOS.','LtRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'MOS.','LtRb',0,'C',$fill);
        $this->fpdf->Cell(25,4,'(use code)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LtRB',1,'C',$fill);        
            
    }          
    
    function header_page2()
    {
        $this->fpdf->SetY(15);
        $this->fpdf->SetFillColor(208,208,255);
        $fill = true;
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(12,5,'','LTRb',0,'L',$fill); 
        $this->fpdf->Cell(60,5,'ANEMIC CHILDREN GIVEN','LTRb',0,'C',$fill);
        $this->fpdf->Cell(80,5,'DIARRHEA CASES','LTRb',0,'C',$fill);
        $this->fpdf->Cell(40,5,'PNEUMONIA CASES','LTRb',0,'C',$fill);
        $this->fpdf->Cell(85,5,'','LTRb',1,'C',$fill);
        
        $this->fpdf->Cell(12,5,'NO.','LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,5,'IRON SUPPLEMENTATION***','LtRb',0,'C',$fill);
        $this->fpdf->Cell(80,5,'(12)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(40,5,'SEEN','LtRb',0,'C',$fill); 
        $this->fpdf->Cell(85,5,'REMARKS','LtRb',1,'C',$fill);    
        
        $this->fpdf->Cell(12,5,'','LtRb',0,'L',$fill);
        $this->fpdf->Cell(60,5,'(11)','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,5,'AGE IN','LTRb',0,'C',$fill);
        $this->fpdf->Cell(60,5,'DATE GIVEN','LTRb',0,'C',$fill);
        $this->fpdf->Cell(40,5,'(13)','LtRb',0,'C',$fill);
        $this->fpdf->Cell(85,5,'','LtRb',1,'C',$fill);
        
        $this->fpdf->Cell(12,5,'','LtRb',0,'L',$fill);
        $this->fpdf->Cell(20,5,'AGE IN','LTtRb',0,'C',$fill);
        $this->fpdf->Cell(40,5,'DATE','LTRB',0,'C',$fill);
        $this->fpdf->Cell(20,5,'MONTHS','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,5,'DATE GIVEN','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,5,'DATE GIVEN','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,5,'DATE GIVEN','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,5,'AGE IN','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,5,'DATE GIVEN','LTRb',0,'C',$fill);
        $this->fpdf->Cell(85,5,'','LtRb',1,'C',$fill);   
        
        $this->fpdf->Cell(12,5,'(10)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,5,'MONTHS','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,5,'STARTED','LTRB',0,'C',$fill);
        $this->fpdf->Cell(20,5,'COMPLETED','LTRB',0,'C',$fill);
        $this->fpdf->Cell(20,5,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,5,'ORT','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,5,'ORS','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,5,'ORS W/ Zinc','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,5,'MONTHS','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,5,'TREATMENT','LtRB',0,'C',$fill);  
        $this->fpdf->Cell(85,5,'(14)','LtRB',1,'C',$fill);     
    }  
    
    function legend_page1()
    {
        $this->fpdf->SetY(164);
        $this->fpdf->SetX(15);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','BI',7.5);
        $this->fpdf->Cell(35,3.5,'*Diagnosis/Findings:',0,0,'L');
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->Cell(35,3.5,'',0,0,'L');
        $this->fpdf->SetFont('Helvetica','BI',7.5);
        $this->fpdf->Cell(202,3.5,'**Recommended Vitamin A Supplementation Given to High Risk/Sick Children',0,1,'L');
        $this->fpdf->SetFont('Helvetica','',7.5);          
        $this->fpdf->SetX(15);
        $this->fpdf->Cell(35,3.5,'A = Measles',0,0,'L');
        $this->fpdf->Cell(35,3.5,'H = Corneal Xerosis',0,0,'L');  
        $this->fpdf->Cell(65,3.5,'DIAGNOSIS',1,0,'C');
        $this->fpdf->Cell(60,3.5,'PREPARATION PER CAPSULE',1,0,'C');
        $this->fpdf->Cell(77,3.5,'VIT. A DOSAGE AND SCHEDULE OF ADMINSTRATION',1,1,'C');
        $this->fpdf->SetX(15);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(35,3.5,'B = Severe Pneumonia',0,0,'L');
        $this->fpdf->Cell(35,3.5,'I = Corneal Ulcerations',0,0,'L');
        $this->fpdf->Cell(65,3.5,'Measles Cases','LTRb',0,'L');
        $this->fpdf->Cell(60,3.5,'100,000 UI for infants 6-11 months old','LTRb',0,'L');
        $this->fpdf->Cell(77,3.5,'Give one capsule upon diagnosis regardless of when the last','LTRb',1,'L');
        $this->fpdf->SetX(15);
        $this->fpdf->Cell(35,3.5,'C = Persistent Diarrhea',0,0,'L');
        $this->fpdf->Cell(35,3.5,'J = Keratomalacia',0,0,'L');
        $this->fpdf->Cell(65,3.5,'','LtRB',0,'L');
        $this->fpdf->Cell(60,3.5,'200,000 UI for infants 12-71 months old ','LtRB',0,'L');
        $this->fpdf->Cell(77,3.5,'dose of Vitamin A capsule(VAC) was given.','LtRB',1,'L');
        $this->fpdf->SetX(15);
        $this->fpdf->Cell(35,3.5,'D = Malnutrition',0,0,'L');
        $this->fpdf->Cell(35,3.5,'',0,0,'L');
        $this->fpdf->Cell(65,3.5,'Severe pneumonia, persistent diarrhea or','LTRb',0,'L');
        $this->fpdf->Cell(60,3.5,'100,000 UI for infants 6-11 months old','LTRb',0,'L');
        $this->fpdf->Cell(77,3.5,'Give one capsule upon diagnosis, except when the child','LTRb',1,'L');
        $this->fpdf->SetX(15);
        $this->fpdf->Cell(35,3.5,'E = Xerophthalmia',0,0,'L');
        $this->fpdf->Cell(35,3.5,'',0,0,'L');
        $this->fpdf->Cell(65,3.5,'underweight','LtRB',0,'L');
        $this->fpdf->Cell(60,3.5,'200,000 UI for infants 12-71 months old ','LtRB',0,'L');
        $this->fpdf->Cell(77,3.5,'was given VAC less than 4 weeks before diagnosis.','LtRB',1,'L');
        $this->fpdf->SetX(15);
        $this->fpdf->Cell(35,3.5,'F = Night Blindedness',0,0,'L');
        $this->fpdf->Cell(35,3.5,'',0,0,'L');
        $this->fpdf->Cell(65,3.5,'Cases with Xerophthalmia, including night blindedness,','LTRb',0,'L');
        $this->fpdf->Cell(60,3.5,'100,000 UI for infants 6-11 months old','LTRb',0,'L');
        $this->fpdf->Cell(77,3.5,'Give one capsule immediately upon diagnosis. Give one','LTRb',1,'L');
        $this->fpdf->SetX(15);
        $this->fpdf->Cell(35,3.5,'G = Bitot\'s Spot',0,0,'L');
        $this->fpdf->Cell(35,3.5,'',0,0,'L');
        $this->fpdf->Cell(65,3.5,'corneal xerosis, corneal ulcerations and kerotomalacia','LtRB',0,'L');
        $this->fpdf->Cell(60,3.5,'200,000 UI for infants 12-71 months old ','LtRB',0,'L');
        $this->fpdf->Cell(77,3.5,'capsule the next day, and on capsule 2 weeks after.','LtRB',1,'L');
            
    } 
    
    function legend_page2()
    {
        $this->fpdf->SetY(164);
        $this->fpdf->SetX(15);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','BI',7.5);
        $this->fpdf->Cell(33,4,'***Iron Supplementation:',0,0,'L');
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->Cell(62,4,'Dosage is 1 tbsp once a day for 3 months or',0,1,'L');
        $this->fpdf->SetX(30);
        $this->fpdf->Cell(80,4,'30 mg once a week for 6 months with supervised administration',0,1,'L');  
    }        
	
}
