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
	
  	protected $section = 'consultations';
  	
    private $validation_rules = array(
    
		array(
			'field' => 'date_consultations',
			'label' => 'Date of Consultations',
			'rules' => 'trim|required'
		),
    array(
			'field' => 'cc',
			'label' => 'Chief Complaint',
			'rules' => 'required|xss_clean'
		),
    array(
			'field' => 'wt',
			'label' => 'Weight',
			'rules' => 'required|numeric'
		),
    array(
			'field' => 'ht',
			'label' => 'Height',
			'rules' => 'required|numeric'
		),
    array(
			'field' => 'bp',
			'label' => 'Blood Pressure',
			'rules' => 'required'
		),
    array(
			'field' => 'temp',
			'label' => 'Temperature',
			'rules' => 'required|numeric'
		),
    array(
			'field' => 'pr',
			'label' => 'Pulse Rate',
			'rules' => 'required|integer'
		),
    array(
			'field' => 'rr',
			'label' => 'Respiratory Rate',
			'rules' => 'required|integer'
		)
		,
    array(
			'field' => 'objective',
			'label' => 'Objective Description',
			'rules' => 'xss_clean'
		),
    array(
			'field' => 'assessment_ids',
			'label' => 'Assessment',
			'rules' => 'required'
		),
		array(
			'field' => 'referrer_id',
			'label' => 'Seen By',
			'rules' => 'required'
		),
    array(
			'field' => 'plan',
			'label' => 'Plan of Intervention',
			'rules' => 'required|xss_clean'
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
		$this->load->model(array('consultations_m', 'diseases_m', 'referrer_m','clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m'));
		$this->load->library('form_validation');
        $this->load->library('fpdf');
		$this->lang->load(array('consultations', 'diseases', 'referrer'));
		//$this->output->enable_profiler(TRUE);
		$this->data->list_diseases = array();
		if ($list_diseases = $this->diseases_m->get_list())
		{
      foreach ($list_diseases as $diseases)
			{
				$this->data->list_diseases[$diseases->id] = $diseases->name;
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
		
   	    $base_where['gender'] = $this->input->post('f_module') ? $this->input->post('f_gender') : '';
        //barangay param   
    $base_where = $this->input->post('f_barangays') ? $base_where + array('barangay' => $this->input->post('f_barangays')) : $base_where;
  
		//year param    
		$base_where = $this->input->post('by_year') ? $base_where + array('by_year' => $this->input->post('by_year')) : $base_where;
        
        //diseases param
		$base_where = $this->input->post('f_diseases') ? $base_where + array('diseases' => $this->input->post('f_diseases')) : $base_where;
	
		//age param
		$base_where = $this->input->post('f_age') ? $base_where + array('age' => $this->input->post('f_age')) : $base_where;

		//keyphrase param
		$base_where = $this->input->post('f_keywords') ? $base_where + array('name' => $this->input->post('f_keywords')) : $base_where;

		// Create pagination links
		$pagination = create_pagination('admin/consultations/index', $this->consultations_m->count_by($base_where));
    
        $year = date("Y");
        for($i=$year; $i >= $year - 10; $i--)
        {
            $years[$i]  = $i;
        }
        $this->data->year = $year;
		$this->data->years= $years;
        
        //Set parameters session
        $this->session->set_userdata('base_where',$base_where);
    
		// Using this data, get the relevant results
		$clients = $this->consultations_m->get_results($pagination['limit'],$base_where);
        
        $this->data->totalitems = $this->consultations_m->count_by($base_where);
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
      ->set_partial('consultations-list', 'admin/tables/clients');
       				
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
		$selected_client or redirect('admin/consultations');
		
    //determine active param
		$base_where['client_id'] = $this->session->userdata('selected_client_id');
		
		// Create pagination links
		$pagination = create_pagination('admin/consultations/my_list', $this->consultations_m->count_by($base_where));

		// Using this data, get the relevant results
		$clients = $this->consultations_m->get_results($pagination['limit'],$base_where);

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);
		
    $this->data->selected_client = & $selected_client;
    
		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)      
      ->set_partial('consultations-my_list', 'admin/tables/my_list');
       				
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
				redirect('admin/consultations');
				break;
		} 
	}
	
  public function add_client()
	{		
                $this->session->set_flashdata('warning', 'Search and Select a Client to Create New Consultation Or <a href="admin/clients/add" class="success-link">Register</a> a New Client.');
                $this->session->set_flashdata('current_action', 'consultations'); 
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
    $client or redirect('admin/consultations');
    
    $this->form_validation->set_rules($this->validation_rules);
    
    if ($this->input->post('date_consultations'))
		{
			$date_consultations = strtotime($this->input->post('date_consultations'));  
		}
		else
		{
			$date_consultations = '';  
		}
		
    if ($_POST)
		{
			
                // Loop through each POST item and add it to the secure_post array
			$secure_post = $this->input->post();

			// Set the full date of birth
  			$secure_post['date_consultations'] = $date_consultations;



                // Set the validation rules
		

		if ($this->form_validation->run() !== FALSE)
		{ 
          $this->consultations_m->insert($secure_post)
					? $this->session->set_flashdata('success', 'New Consultation Added on this Client.')
					: $this->session->set_flashdata('error', 'Error Adding Record');
          
          $this->session->unset_userdata('selected_client_id');
          $this->session->set_userdata('consultation_id', $client_id);
          $this->session->set_userdata('consultation_created', true);
				  redirect('admin/clients/view/'.$client_id);
		}
		else
		{
			// Dirty hack that fixes the issue of having to re-add all data upon an error
			if ($_POST)
			{
				
        $consultations = (object) $_POST;
				//print_r($consultations);    
        //exit(); 
			}
			else
			{
      	$consultations->date_consultations = $date_consultations;
      }
		}
		}
		
  	if (!$_POST)
		{           // Loop through each validation rule
  		foreach ($this->validation_rules as $rule)
  		{
  			$consultations->{$rule['field']} = set_value($rule['field']);
  		}
    }

		// Render the view
	
		$consultations->method_action = 'add';
		$consultations->client_id = $client_id;
		
		$this->data->consultations = & $consultations;
		$this->data->client = & $client;
	
		
		$this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->append_metadata(js('consultations_form.js', 'consultations'))
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
		$consultations = $this->consultations_m->get_consultations_details($id);
    
		$client = $this->clients_m->get($consultations->client_id);
		// Make sure we found something
		$client or redirect('admin/consultations');
		
    if ($this->input->post('date_consultations'))
		{
			$date_consultations = strtotime($this->input->post('date_consultations'));  
		}
		else
		{
			$date_consultations = '';  
			$consultations->date_consultations = $consultations->date_consultations ? date('Y-m-d', $consultations->date_consultations) : ''; 
		}
		
		if ($_POST)
		{
			
      // Loop through each POST item and add it to the secure_post array
			$secure_post = $this->input->post();
			$secure_post['date_consultations'] = $date_consultations;
			
      // Set the validation rules
  		$this->form_validation->set_rules($this->validation_rules);
  
  		if ($this->form_validation->run() !== FALSE)
  		{ 
              if($this->consultations_m->update($id, $secure_post))
              {
                $this->session->set_flashdata('success', 'Client\'s Consultations successfully updated');
                if($this->session->userdata('selected_client_id'))
                redirect('admin/consultations/my_list');
                else
                redirect('admin/consultations');
              }
              else 
              {
                $this->session->set_flashdata('error', 'Error updating Client/No Data has been entered.');
                redirect("admin/consultations/edit/$id");
              }
  		}
  		else
  		{
  				$consultations = (object) $_POST;
  		}
  		
      if (!$_POST)
		  {           // Loop through each validation rule
  		foreach ($this->validation_rules as $rule)
  		{
  			$consultations->{$rule['field']} = set_value($rule['field']);
  		}
      }
		}
    else
    {
      $client->dob= $client->dob ? date('Y-m-d', $client->dob) : ''; 
      foreach( $this->consultations_m->get_diseases($id) as $disease)
      {
      $consultations->assessment_ids[] =$disease;
      }
    }
	    
		$sc->method_action = 'edit';
		// Render the view
		$this->data->consultations = & $consultations;
		$this->data->client = & $client;
		$this->data->client->barangay_name = & $this->clients_barangay_m->get_name($client->barangay_id);;
		$this->data->client->province_name = & $this->clients_province_m->get_name($client->province_id);;
		$this->data->client->region_name = & $this->clients_region_m->get_name($client->region_id);;
		$this->data->client->city_name = & $this->clients_city_m->get_name($client->city_id);;
		
		$this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->append_metadata(js('consultations_form.js', 'consultations'))
				->build('admin/form',$this->data);
	}


  public function set($type = 'default', $client_id = 0)
  {
    if($type == 'default')
    {
        $this->session->set_userdata('selected_client_id','');
        $this->session->set_userdata('set', $type);
        redirect("admin/consultations");
    }
    $count = $this->consultations_m->check_record($client_id);
    if($count > 0)
    {
        $this->session->set_flashdata('warning', 'Please Select on the List or '."<a href='admin/consultations/add/$client_id' class='add'>Create New Consultation</a>".' for this Client');
        $this->session->set_userdata('selected_client_id',$client_id);
        $this->session->set_userdata('set', $type);
        redirect("admin/consultations/my_list");
    }
    else
    {
        $this->session->set_flashdata('error', 'This Client has not yet visited. Click '."<a href='admin/consultations/add/$client_id' class='add'>here</a>".' to Create New Consultation for this Client');
        $this->session->set_userdata('selected_client_id',$client_id);
        $this->session->set_userdata('set', $type);
        redirect("admin/consultations/my_list");
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
    				if ($this->consultations_m->delete($id))
    				{
    					$deleted++;
    				}
    				$to_delete++;
    			}
    
    			if ($to_delete > 0)
    			{
    				$this->session->set_flashdata('success', sprintf($this->lang->line('consultations_mass_delete_success'), $deleted, $to_delete));
    			}
    		}
    		// The array of id's to delete is empty
    		else
    			$this->session->set_flashdata('error', $this->lang->line('consultations.delete_error'));
    }
    else
    {
        $this->consultations_m->delete($id)
    			? $this->session->set_flashdata('success', lang('consultations.delete_success'))
    			: $this->session->set_flashdata('error', lang('consultations.delete_error'));
		}
		redirect('admin/consultations');
	}
    
    public function print_page()
	{
		$base_where = $this->session->userdata('base_where');
  
		// Create pagination links
		$pagination = create_pagination('admin/consultations/index', $this->consultations_m->count_by($base_where));

		// Using this data, get the relevant results
		$consultations = $this->consultations_m->get_results($pagination['limit'],$base_where);
        $totalitems = count($consultations);
        
        $this->fpdf->Open();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
                            
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
        $this->fpdf->SetY(10);
        $this->fpdf->SetFont('Helvetica','B',16);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(0,4,'CLIENT LIST FOR CONSULTATIONS',0,0,'C');
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFillColor(208,208,255);
        $fill = true;
        $this->fpdf->SetFont('Helvetica','B',8);
        $this->fpdf->Cell(12,8,'NO.',1,0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(45,8,'FULL NAME',1,0,'C',$fill);
        $this->fpdf->Cell(10,8,'AGE',1,0,'C',$fill);
        $this->fpdf->Cell(15,8,'GENDER',1,0,'C',$fill);
        $this->fpdf->Cell(50,8,'ASSESSMENT',1,0,'C',$fill);
        $this->fpdf->Cell(15,8,'WT(kg)',1,0,'C',$fill);
        $this->fpdf->Cell(15,8,'HT(cm)',1,0,'C',$fill);
        $this->fpdf->Cell(15,8,'BP',1,0,'C',$fill);
        $this->fpdf->Cell(15,8,'TEMP(C)',1,0,'C',$fill);
        $this->fpdf->Cell(10,8,'PR',1,0,'C',$fill);
        $this->fpdf->Cell(10,8,'RR',1,0,'C',$fill);
        $this->fpdf->Cell(20,8,'DATE',1,0,'C',$fill);
        $this->fpdf->Cell(45,8,'SEEN BY',1,1,'C',$fill);
        
        $i = 1;
        $x = 1;
        
        foreach ($consultations as $consultation)
        {                             
                $age = floor((time() - $consultation->dob)/31556926); 
                if(!$age)
                    {$age = '';}
                if(!$consultation->address)
                        {$address = $consultation->residence;} 
                elseif(!$consultation->residence)
                        {$address = $consultation->address;} 
                else 
                        {$address = $consultation->residence.', '.$consultation->address;} 
                
                if(strlen($consultation->diseases)>35)  
                    {$ass = ucfirst(substr($consultation->diseases, 0, 35))."...";}
                else
                    {$ass = ucfirst($consultation->diseases);}        
                    
                $referrer = $this->consultations_m->get_referrer($consultation->referrer_id);
                
                $this->fpdf->SetFillColor(230,230,230);             
                $modx = $x%2;
                if($modx==0) 
                {$fill=false;}
                else
                {$fill=true;}
                $this->fpdf->SetFont('Helvetica','B',7);
                $this->fpdf->Cell(12,8,$x.'.',1,0,'L',$fill);
                $this->fpdf->SetFont('Helvetica','',7);
                $this->fpdf->Cell(45,8,trim($consultation->last_name).', '.trim($consultation->first_name).' '.trim($consultation->middle_name),1,0,'L',$fill);
                $this->fpdf->Cell(10,8,$age,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,ucfirst($consultation->gender),1,0,'C',$fill);
                $this->fpdf->Cell(50,8,ucwords($ass),1,0,'L',$fill);
                $this->fpdf->Cell(15,8,$consultation->wt,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$consultation->ht,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$consultation->bp,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$consultation->temp,1,0,'C',$fill);
                $this->fpdf->Cell(10,8,$consultation->pr,1,0,'C',$fill);
                $this->fpdf->Cell(10,8,$consultation->rr,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,date('M j, Y', $consultation->date_consultations),1,0,'L',$fill);
                $this->fpdf->Cell(45,8,trim($consultation->lastname).', '.trim($consultation->firstname).' '.substr($consultation->middlename,0,1).'.',1,1,'L',$fill);
                if($i==20)
                {
                    if($x<$totalitems)
                    {                        
                        $this->footer();
                    
                        $this->fpdf->AliasNbPages();
                        $this->fpdf->AddPage('L','A4');
                        $i = 1;
                        $this->fpdf->SetTextColor(0,0,0);
                        
                        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
                        $this->fpdf->SetY(10);
                        $this->fpdf->SetFont('Helvetica','B',16);
                        $this->fpdf->SetTextColor(0,0,0);
                        $this->fpdf->Cell(0,4,'CLIENT LIST FOR CONSULTATIONS',0,0,'C');
                        $this->fpdf->SetFont('Helvetica','',8);
                        
                        $this->fpdf->SetY(15);
                        $this->fpdf->SetFillColor(208,208,255);
                        $fill = true;
                        $this->fpdf->SetFont('Helvetica','B',8);
                        $this->fpdf->Cell(12,8,'NO.',1,0,'C',$fill);
                        $this->fpdf->SetFont('Helvetica','',8);
                        $this->fpdf->Cell(45,8,'FULL NAME',1,0,'C',$fill);
                        $this->fpdf->Cell(10,8,'AGE',1,0,'C',$fill);
                        $this->fpdf->Cell(15,8,'GENDER',1,0,'C',$fill);
                        $this->fpdf->Cell(50,8,'ASSESSMENT',1,0,'C',$fill);
                        $this->fpdf->Cell(15,8,'WT(kg)',1,0,'C',$fill);
                        $this->fpdf->Cell(15,8,'HT(cm)',1,0,'C',$fill);
                        $this->fpdf->Cell(15,8,'BP',1,0,'C',$fill);
                        $this->fpdf->Cell(15,8,'TEMP(C)',1,0,'C',$fill);
                        $this->fpdf->Cell(10,8,'PR',1,0,'C',$fill);
                        $this->fpdf->Cell(10,8,'RR',1,0,'C',$fill);
                        $this->fpdf->Cell(20,8,'DATE',1,0,'C',$fill);
                        $this->fpdf->Cell(45,8,'SEEN BY',1,1,'C',$fill);
                    }
                }
                else
                {
                    $i=$i+1;
                } 
                $x=$x+1;            
        }                     
        
        $this->footer();
        
        $this->fpdf->Output('TCL_Consultations.pdf','I');        
	} 
    
    function footer()
    {
        $year = date('Y');
        //Draw a line
        $this->fpdf->SetY(-15);
        
        //Page footer
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',6);
        $this->fpdf->Cell(10,4,'Page '.$this->fpdf->PageNo().'/{nb}',0,0,'C');
        $this->fpdf->SetFont('Helvetica','',6);
        $this->fpdf->Cell(0,4,'Copyright © '.$year.' IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
    } 
}
