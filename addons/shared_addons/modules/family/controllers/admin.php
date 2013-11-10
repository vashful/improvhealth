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
	
  	protected $section = 'family';
  	
    private $validation_rules = array(
    array(
			'field' => 'client_id',
			'label' => 'Client ID',
			'rules' => 'required'
		),
		array(
			'field' => 'method_id',
			'label' => 'Family Planning Method',
			'rules' => 'required'
		),    
		array(
			'field' => 'client_type',
			'label' => 'Client Type',
			'rules' => 'required'
		),
		array(
			'field' => 'previous_method_id',
			'label' => 'Previous Method',
			'rules' => ''
		),
		array(
			'field' => 'drop_out_reason',
			'label' => 'Drop-out Reason',
			'rules' => 'utf8|xss_clean|trim'
		),
		array(
			'field' => 'drop_out_date',
			'label' => 'Drop-out Date',
			'rules' => ''
		),
		array(
			'field' => 'remarks',
			'label' => 'Remarks',
			'rules' => 'xss_clean|trim'
		),
		array(
			'field' => 'service_date',
			'label' => 'Service Date',
			'rules' => 'trim|required'
		)
	);
	
	private $validation_rules_edit = array(
		array(
			'field' => 'drop_out_reason',
			'label' => 'Drop-out Reason',
			'rules' => 'utf8|xss_clean|trim'
		),
		array(
			'field' => 'drop_out_date',
			'label' => 'Drop-out Date',
			'rules' => ''
		),
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
		$this->load->model(array('family_m','clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m'));
		$this->load->library('form_validation');
        $this->load->library('fpdf'); 
		$this->lang->load(array('family','clients/clients'));     
		//$this->output->enable_profiler(TRUE);
		
		$this->data->methods = array();
		if ($methods = $this->family_m->get_method())
		{
			foreach ($methods as $method)
			{
				$this->data->methods[$method->id] = $method->method;
			}
		}		
		
		$this->data->method_codes = array();
		if ($methods = $this->family_m->get_method())
		{
			foreach ($methods as $method)
			{
				$this->data->method_codes[$method->id] = $method->code;
			}
		}		
		
		$this->data->client_types = array(
                  'CU'  => 'Current Users',
                  'NA'  => 'New Acceptors',
                  'CM'  => 'Changing Method',
                  'CC'  => 'Changing Clinic',
                  'RS'  => 'Restart');
    
		$this->data->drop_out_reasons = array(
						      ''  => '-- Not Yet Drop-Out --',
                  'A'  => 'Pregnant',
                  'B'    => 'Desire to become pregnant',
                  'C'    => 'Medical complications',
                  'D'    => 'Fear of side effects',
                  'E'    => 'Changed Clinic',
                  'F'    => 'Husband disapproves',
                  'G'    => 'Menopause',
                  'H'    => 'Lost/Moved out of area/residence',
                  'I'    => 'Failed to get supply',
                  'J'    => 'IUD expelled',
                  'K'    => 'Lack of Supply',
                  'L'    => 'Unknown'
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
		redirect('admin/family/my_list');
        //determine active param
		$base_where['gender'] = $this->input->post('f_module') ? $this->input->post('f_gender') : '';
    
    //barangay param   
    $base_where = $this->input->post('f_barangays') ? $base_where + array('barangay' => $this->input->post('f_barangays')) : $base_where;
 
		//year param    
		$base_where = $this->input->post('by_year') ? $base_where + array('by_year' => $this->input->post('by_year')) : $base_where;
        
        //status param
		$base_where = $this->input->post('f_status') ? $base_where + array('status' => $this->input->post('f_status')) : $base_where;
		
		//method param
		$base_where = $this->input->post('f_method') ? $base_where + array('method' => $this->input->post('f_method')) : $base_where;
		
		//client_type param
		$base_where = $this->input->post('f_client_type') ? $base_where + array('client_type' => $this->input->post('f_client_type')) : $base_where;

		//keyphrase param
		$base_where = $this->input->post('f_keywords') ? $base_where + array('name' => $this->input->post('f_keywords')) : $base_where;

		//Set parameters session
        $this->session->set_userdata('base_where',$base_where);
       
        // Create pagination links
		$pagination = create_pagination('admin/family/index', $this->family_m->count_by($base_where));
        
        $year = date("Y");
        for($i=$year; $i >= $year - 10; $i--)
        {
            $years[$i]  = $i;
        }
        $this->data->year = $year;
		$this->data->years= $years;

		// Using this data, get the relevant results
		$clients = $this->family_m->get_results($pagination['limit'],$base_where);
        
        $this->data->totalitems = $this->family_m->count_by($base_where);
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
                        ->set_partial('family-list', 'admin/tables/clients');
       				
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
		$selected_client or redirect('admin/family');
		
    //determine active param
		$base_where['client_id'] = $this->session->userdata('selected_client_id');
		
		// Create pagination links
		$pagination = create_pagination("admin/family/my_list", $this->family_m->count_by($base_where));

		// Using this data, get the relevant results
		$clients = $this->family_m->get_results($pagination['limit'],$base_where);

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);
		
    $this->data->selected_client = & $selected_client;
		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)      
      ->set_partial('family-my_list', 'admin/tables/my_list');
       				
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
				redirect('admin/family');
				break;
		}
	}

  public function add_client()
	{		
                $this->session->set_flashdata('warning', 'Search and Select a Client to be Added to "Family Planning" Or <a href="admin/clients/add" class="success-link">Register</a> a New Client.');
                $this->session->set_userdata('current_action', 'fp'); 
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
                $client = $this->clients_m->get($client_id);
                $this->form_validation->set_rules($this->validation_rules);
    if ($this->input->post('service_date'))
		{
			$service_date = strtotime($this->input->post('service_date'));  
		}
		else
		{
			$service_date = now();    
		}
    
    if ($_POST)
		{
                        // Loop through each POST item and add it to the secure_post array
                        $secure_post = $this->input->post();
                        
                        // Set the full date of birth
                        $secure_post['service_date'] = $service_date;

          		if ($this->form_validation->run() !== FALSE)
          		{ 
                                $this->family_m->insert($secure_post)
                                ? $this->session->set_flashdata('success', 'New Client for Family Planning Added')
                                : $this->session->set_flashdata('error', 'Error Adding Client for Family Planning');
                                if($this->session->userdata('selected_client_id'))
                                  redirect('admin/family/my_list');
                                  else
                                redirect('admin/family');
          		}
          		else
          		{
          			// Dirty hack that fixes the issue of having to re-add all data upon an error
          			$fp = (object) $_POST;
          		}
		}
                // Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$fp->{$rule['field']} = set_value($rule['field']);
		}
		
		$fp->client_id = $client_id;
                $fp->service_date = $service_date;

		// Render the view
		$this->data->fp = & $fp;
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
        redirect("admin/family");
    }
    $count = $this->family_m->check_record($client_id);
    if($count > 0)
    {
        $this->session->set_flashdata('warning', 'Please Select on the List or '."<a href='admin/family/add/$client_id' class='add'>Add</a>".' New Family Planning Record for this Client');
        $this->session->set_userdata('selected_client_id',$client_id); 
        $this->session->set_userdata('set', $type);
        redirect("admin/family/my_list");
    }
    else
    {
        $this->session->set_flashdata('error', 'No Family Planning Record Found for this Client. Click '."<a href='admin/family/add/$client_id' class='add'>here</a>".' to Add New Family Planning Record for this Client');
        $this->session->set_userdata('selected_client_id',$client_id);   
        $this->session->set_userdata('set', $type);
        redirect("admin/family/my_list");
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
		$fp = $this->family_m->get_fp_details($id);
		$client = $this->clients_m->get($fp->client_id);
    $fp_visits = $this->family_m->get_fp_visits($id);
		// Make sure we found something
		$fp or redirect('admin/family');

		if ($_POST)
		{

      // Loop through each POST item and add it to the secure_post array
			$secure_post = $this->input->post();
		
      // Set the validation rules
  		$this->form_validation->set_rules($this->validation_rules_edit);
  
  		if ($this->form_validation->run() !== FALSE)
  		{ 
          $this->family_m->update($id, $secure_post)
  					? $this->session->set_flashdata('success', 'Family Planning Visits Saved!')
  					: $this->session->set_flashdata('error', 'Error Saving Family Planning Visits');
          if($this->session->userdata('selected_client_id'))
            redirect('admin/family/my_list');
          else
  				  redirect('admin/family');
  		}
  		else
  		{
  				$fp = (object) $_POST;
  		}
  		// Loop through each validation rule
  		foreach ($this->validation_rules as $rule)
  		{
  			$fp->{$rule['field']} = set_value($rule['field']);
  		}
		}
			     
		// Render the view
		$this->data->fp = & $fp;
		$this->data->fp_visits = & $fp_visits;
		$this->data->client = & $client;
		$this->data->client->barangay_name = & $this->clients_barangay_m->get_name($client->barangay_id);;
		$this->data->client->province_name = & $this->clients_province_m->get_name($client->province_id);;
		$this->data->client->region_name = & $this->clients_region_m->get_name($client->region_id);;
		$this->data->client->city_name = & $this->clients_city_m->get_name($client->city_id);;
		
		$this->template
				->title($this->module_details['name'], lang('user_edit_title'))
				->append_metadata(js('fp_form.js', 'family'))
				->build('admin/form_edit', $this->data);
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
    
    		if (!empty($ids))
    		{
    			$deleted = 0;
    			$to_delete = 0;
    			foreach ($ids as $id)
    			{
    				if ($this->family_m->delete($id))
    				{
    					$deleted++;
    				}
    				$to_delete++;
    			}
    
    			if ($to_delete > 0)
    			{
    				$this->session->set_flashdata('success', sprintf($this->lang->line('family_mass_delete_success'), $deleted, $to_delete));
    			}
    		}
    		// The array of id's to delete is empty
    		else
    			$this->session->set_flashdata('error', $this->lang->line('family.delete_error'));
    }
    else
    {
        $this->family_m->delete($id)
    			? $this->session->set_flashdata('success', lang('family.delete_success'))
    			: $this->session->set_flashdata('error', lang('family.delete_error'));
		}
    if($this->session->userdata('selected_client_id'))
      redirect('admin/family/my_list');
    else
		  redirect('admin/family');
	}
    
    public function print_page()
	{
		$base_where = $this->session->userdata('base_where');

		// Create pagination links
		$pagination = create_pagination('admin/family/index', $this->family_m->count_by($base_where));

		// Using this data, get the relevant results
		$clients = $this->family_m->get_results($pagination['limit'],$base_where);
		$totalitems = count($clients);
        if($totalitems<1)
            {$totalitems = 1;}
        $totalpage = number_format($totalitems/17);
        $remainder = $totalitems%17;
        if($remainder!=0)
        {
            if($remainder>=9)
            {$totalpages=number_format($totalpage);}
            else
            {$totalpages=number_format($totalpage)+1;}
        }   
		
		if ( ! empty($base_where['method']))
        {
          $fpmethod = $base_where['method'] ? $this->data->methods[$base_where['method']]: 'All';
        }
        else
        {
          $fpmethod = "All";
        } 
        
        $this->fpdf->Open();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
                            
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
        $this->fpdf->SetY(10);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(0,4,strtoupper('Target Client List for Family Planning - '.$fpmethod),0,0,'C');
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFillColor(208,208,255);
        $fill = true;
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(12,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(30,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LTRb',1,'C',$fill);
         
        $this->fpdf->SetFont('Helvetica','B',8);
        $this->fpdf->Cell(12,4,strtoupper('No.'),'LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(20,4,strtoupper('Date of'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(30,4,strtoupper('Family'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,strtoupper('Name'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,strtoupper('Address'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,strtoupper('Type'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,strtoupper('FP'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,strtoupper('Previous'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,strtoupper('Date'),'LtRb',1,'C',$fill);                
        
        $this->fpdf->Cell(12,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,strtoupper('Regis-'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(30,4,strtoupper('Serial No.'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,strtoupper('Age'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,strtoupper('of'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,strtoupper('Method**'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,strtoupper('Method**'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,strtoupper('Started'),'LtRb',1,'C',$fill);
        
        $this->fpdf->Cell(12,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,strtoupper('tration'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(30,4,strtoupper('No'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,strtoupper('Client*'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LtRb',1,'C',$fill);
        
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(12,4,'(1)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'(2)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(30,4,'(3)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(60,4,'(4)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(60,4,'(5)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'(6)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'(7)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'(8)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'(8)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'(9)','LtRB',1,'C',$fill);
            
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
                  {$address = $brgy;} 
            elseif(!$brgy)
                  {$address = $client->address;} 
            else 
                  {$address = $brgy.', '.$client->address;}
                  
            $client_type = $client->client_type ? $this->data->client_types[$client->client_type]: '';
            $drop_out_reason = $client->drop_out_reason ? $this->data->drop_out_reasons[$client->drop_out_reason]: '';
            $fp_method_cu = $client->method_id ? $this->data->method_codes[$client->method_id]: ''; 
            $fp_method_prev = $client->previous_method_id ? $this->data->method_codes[$client->previous_method_id]: ''; 
            
            $registration_date = $client->registration_date ? date('M j, Y', $client->registration_date): '';
            $drop_out_date = $client->drop_out_date ? date('M j, Y', $client->drop_out_date): '';
            $date_started = $client->date_started ? date('m-d-Y', $client->date_started): '';
            
            $this->fpdf->SetFillColor(230,230,230);             
            $modx = $x%2;
            if($modx==0) 
            {$fill=false;}
            else
            {$fill=true;}
            
            $this->fpdf->SetFont('Helvetica','B',7.5);
            $this->fpdf->Cell(12,8,$x.'.',1,0,'L',$fill);
            $this->fpdf->SetFont('Helvetica','',7.5);
            $this->fpdf->Cell(20,8,$registration_date,1,0,'C',$fill);    
            $this->fpdf->Cell(30,8,$client->serial_number,1,0,'L',$fill);
            $this->fpdf->Cell(60,8,trim($client->last_name).', '.trim($client->first_name).' '.trim($client->middle_name),1,0,'L',$fill);
            $this->fpdf->Cell(60,8,$address,1,0,'L',$fill);
            $this->fpdf->Cell(15,8,$age,1,0,'C',$fill);
            $this->fpdf->Cell(20,8,ucwords($client->client_type),1,0,'L',$fill);
            $this->fpdf->Cell(20,8,ucfirst($fp_method_cu),1,0,'C',$fill);
            $this->fpdf->Cell(20,8,ucfirst($fp_method_prev),1,0,'C',$fill);
            $this->fpdf->Cell(20,8,$drop_out_date,1,1,'C',$fill);
            if($i==17)
            {
                if($x<$totalitems)
                {                   
                    $this->fpdf->SetY(-15);
                    $this->fpdf->SetTextColor(128);
                    $this->fpdf->SetFont('Helvetica','I',7);
                    $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
                    $this->footer();
                    $this->legend_page1();
                    $this->tcl_header();
                    $i = 1;
                    $currentpage = $currentpage + 1;
                    $this->fpdf->SetTextColor(0,0,0);
                    
                    $this->fpdf->AliasNbPages();
                    $this->fpdf->AddPage('L','A4');
                    $this->fpdf->SetAutoPageBreak(true, 10);
                                        
                    $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
                    $this->fpdf->SetY(10);
                    $this->fpdf->SetFont('Helvetica','B',12);
                    $this->fpdf->SetTextColor(0,0,0);
                    $this->fpdf->Cell(0,4,strtoupper('Target Client List for Family Planning - '.$fpmethod),0,0,'C');
                    
                    $this->fpdf->SetY(15);
                    $this->fpdf->SetFillColor(208,208,255);
                    $fill = true;
                    $this->fpdf->SetFont('Helvetica','',8);
                    $this->fpdf->Cell(12,4,'','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(30,4,'','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(60,4,'','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(60,4,'','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'','LTRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'','LTRb',1,'C',$fill);
                    
                    $this->fpdf->SetFont('Helvetica','B',8);
                    $this->fpdf->Cell(12,4,strtoupper('No.'),'LtRb',0,'C',$fill); 
                    $this->fpdf->SetFont('Helvetica','',8);
                    $this->fpdf->Cell(20,4,strtoupper('Date of'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(30,4,strtoupper('Family'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(60,4,strtoupper('Name'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(60,4,strtoupper('Address'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,strtoupper('Type'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,strtoupper('FP'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,strtoupper('Previous'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,strtoupper('Date'),'LtRb',1,'C',$fill);                
                    
                    $this->fpdf->Cell(12,4,'','LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,strtoupper('Regis-'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(30,4,strtoupper('Serial No.'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(60,4,'','LtRb',0,'C',$fill);
                    $this->fpdf->Cell(60,4,'','LtRb',0,'C',$fill);
                    $this->fpdf->Cell(15,4,strtoupper('Age'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,strtoupper('of'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,strtoupper('Method**'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,strtoupper('Method**'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,strtoupper('Started'),'LtRb',1,'C',$fill);
                    
                    $this->fpdf->Cell(12,4,'','LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,strtoupper('tration'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(30,4,strtoupper('No'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(60,4,'','LtRb',0,'C',$fill);
                    $this->fpdf->Cell(60,4,'','LtRb',0,'C',$fill);
                    $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,strtoupper('Client*'),'LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'','LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'','LtRb',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'','LtRb',1,'C',$fill);
                    
                    $this->fpdf->SetFont('Helvetica','',7);
                    $this->fpdf->Cell(12,4,'(1)','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'(2)','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(30,4,'(3)','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(60,4,'(4)','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(60,4,'(5)','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(15,4,'(6)','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'(7)','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'(8)','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'(8)','LtRB',0,'C',$fill);
                    $this->fpdf->Cell(20,4,'(9)','LtRB',1,'C',$fill);
                    
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
        $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
        $this->footer();
        $this->legend_page1();
        $this->tcl_header();
        //================================= Second Page Family Planning ===============================//
      
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
                            
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
        $this->fpdf->SetY(10);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(0,4,strtoupper('Client List for Family Planning - '.$fpmethod),0,0,'C');
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFillColor(208,208,255);
        $fill = true;
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->Cell(10,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(240,4,'FOLLOW-UP VISITS','LTRb',0,'C',$fill);
        $this->fpdf->Cell(27,4,'','LTRb',1,'C',$fill);
                
        $this->fpdf->SetFont('Helvetica','B',8);
        $this->fpdf->Cell(10,4,'NO.','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(240,4,strtoupper('Upper Space: Next Service Date / Lower Space: Date Accomplished'),'LtRb',0,'C',$fill);
        $this->fpdf->Cell(27,4,'DROP-OUTS','LtRb',1,'C',$fill);
        
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(10,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(240,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(27,4,'','LtRb',1,'C',$fill);
        
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(10,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(240,4,'(11)','LtRb',0,'C',$fill);
        $this->fpdf->Cell(27,4,'(12)','LtRb',1,'C',$fill);
        
        $this->fpdf->Cell(10,4,'(10)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'1ST',1,0,'C',$fill);
        $this->fpdf->Cell(20,4,'2ND',1,0,'C',$fill);
        $this->fpdf->Cell(20,4,'3RD',1,0,'C',$fill);
        $this->fpdf->Cell(20,4,'4TH',1,0,'C',$fill);
        $this->fpdf->Cell(20,4,'5TH',1,0,'C',$fill);
        $this->fpdf->Cell(20,4,'6TH',1,0,'C',$fill);
        $this->fpdf->Cell(20,4,'7TH',1,0,'C',$fill);
        $this->fpdf->Cell(20,4,'8TH',1,0,'C',$fill);
        $this->fpdf->Cell(20,4,'9TH',1,0,'C',$fill);
        $this->fpdf->Cell(20,4,'10TH',1,0,'C',$fill);
        $this->fpdf->Cell(20,4,'11TH',1,0,'C',$fill);
        $this->fpdf->Cell(20,4,'12TH',1,0,'C',$fill);
        $this->fpdf->Cell(12,4,'REASON',1,0,'C',$fill);
        $this->fpdf->Cell(15,4,'DATE',1,1,'C',$fill);
    
      
        $i2 = 1;
        $x2 = 1;
        $currentpage = 1;
        
        foreach ($clients as $client)
        {                             
          $fp_visits = $this->family_m->get_fp_visits($client->fp_id);
          //echo print_r($clients);
          //echo $client->fp_id."<br />";
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
                  
          $client_type = $client->client_type ? $this->data->client_types[$client->client_type]: '-';
          $drop_out_reason = $client->drop_out_reason ? $this->data->drop_out_reasons[$client->drop_out_reason]: '-';
          $fp_method = $client->previous_method_id ? $this->data->method_codes[$client->previous_method_id]: '-'; 
          
          $registration_date = $client->registration_date ? date('M j, Y', $client->registration_date): '-';
          $drop_out_date = $client->drop_out_date ? date('M j, Y', $client->drop_out_date): '-';
          $date_started = $client->date_started ? date('Y-m-d', $client->date_started): '-';
          
            $fpindex = 1;
            foreach ($fp_visits as $fp_visit)
            { 
                if($fpindex==1)  
                {
                    $fp_sd1 = $fp_visit->service_date ? date('Y-m-d', $fp_visit->service_date): '';  
                    $fp_ad1 = $fp_visit->accomplished_date ? date('Y-m-d', $fp_visit->accomplished_date): '';         
                }   
                elseif($fpindex==2)
                {
                    $fp_sd2 = $fp_visit->service_date ? date('Y-m-d', $fp_visit->service_date): '';  
                    $fp_ad2 = $fp_visit->accomplished_date ? date('Y-m-d', $fp_visit->accomplished_date): '';
                } 
                elseif($fpindex==3)
                {
                    $fp_sd3 = $fp_visit->service_date ? date('Y-m-d', $fp_visit->service_date): '';  
                    $fp_ad3 = $fp_visit->accomplished_date ? date('Y-m-d', $fp_visit->accomplished_date): '';
                }
                elseif($fpindex==4)
                {
                    $fp_sd4 = $fp_visit->service_date ? date('Y-m-d', $fp_visit->service_date): '';  
                    $fp_ad4 = $fp_visit->accomplished_date ? date('Y-m-d', $fp_visit->accomplished_date): '';
                }
                elseif($fpindex==5)
                {
                    $fp_sd5 = $fp_visit->service_date ? date('Y-m-d', $fp_visit->service_date): '';  
                    $fp_ad5 = $fp_visit->accomplished_date ? date('Y-m-d', $fp_visit->accomplished_date): '';
                }
                elseif($fpindex==6)
                {
                    $fp_sd6 = $fp_visit->service_date ? date('Y-m-d', $fp_visit->service_date): '';  
                    $fp_ad6 = $fp_visit->accomplished_date ? date('Y-m-d', $fp_visit->accomplished_date): '';
                }
                elseif($fpindex==7)
                {
                    $fp_sd7 = $fp_visit->service_date ? date('Y-m-d', $fp_visit->service_date): '';  
                    $fp_ad7 = $fp_visit->accomplished_date ? date('Y-m-d', $fp_visit->accomplished_date): '';
                }
                elseif($fpindex==8)
                {
                    $fp_sd8 = $fp_visit->service_date ? date('Y-m-d', $fp_visit->service_date): '';  
                    $fp_ad8 = $fp_visit->accomplished_date ? date('Y-m-d', $fp_visit->accomplished_date): '';
                }
                elseif($fpindex==9)
                {
                    $fp_sd9 = $fp_visit->service_date ? date('Y-m-d', $fp_visit->service_date): '';  
                    $fp_ad9 = $fp_visit->accomplished_date ? date('Y-m-d', $fp_visit->accomplished_date): '';
                }
                elseif($fpindex==10)
                {
                    $fp_sd10 = $fp_visit->service_date ? date('Y-m-d', $fp_visit->service_date): '';  
                    $fp_ad10 = $fp_visit->accomplished_date ? date('Y-m-d', $fp_visit->accomplished_date): '';
                }
                elseif($fpindex==11)
                {
                    $fp_sd11 = $fp_visit->service_date ? date('Y-m-d', $fp_visit->service_date): '';  
                    $fp_ad11 = $fp_visit->accomplished_date ? date('Y-m-d', $fp_visit->accomplished_date): '';
                }
                elseif($fpindex==12)
                {
                    $fp_sd12 = $fp_visit->service_date ? date('Y-m-d', $fp_visit->service_date): '';  
                    $fp_ad12 = $fp_visit->accomplished_date ? date('Y-m-d', $fp_visit->accomplished_date): '';
                }
                $fpindex = $fpindex+1;
            } 
            
            $this->fpdf->SetFillColor(230,230,230);             
            $modx = $x2%2;
            if($modx==0) 
            {$fill=false;}
            else
            {$fill=true;}
                        
            $this->fpdf->SetFont('Helvetica','B',7.5);
            $this->fpdf->Cell(10,8,$x2.'.',1,0,'L',$fill);
            $this->fpdf->SetFont('Helvetica','',7.5);
            
            $this->fpdf->Cell(20,4,$fp_sd1,1,0,'C',$fill); 
            $posx = $this->fpdf->GetX();
            $posy = $this->fpdf->GetY();
            $posx = number_format($posx)-20;
            $posy = $posy+4;
            $posx2 = number_format($posx)+20;
            $posy2 = $posy-4;
            $this->fpdf->SetY($posy);
            $this->fpdf->SetX($posx); 
            $this->fpdf->Cell(20,4,$fp_ad1,1,0,'C',$fill); 
            $this->fpdf->SetY($posy2);
            $this->fpdf->SetX($posx2); 
            
            $this->fpdf->Cell(20,4,$fp_sd2,1,0,'C',$fill); 
            $posx = $this->fpdf->GetX();
            $posy = $this->fpdf->GetY();
            $posx = number_format($posx)-20;
            $posy = $posy+4;
            $posx2 = number_format($posx)+20;
            $posy2 = $posy-4;
            $this->fpdf->SetY($posy);
            $this->fpdf->SetX($posx); 
            $this->fpdf->Cell(20,4,$fp_ad2,1,0,'C',$fill);
            $this->fpdf->SetY($posy2);
            $this->fpdf->SetX($posx2); 
            
            $this->fpdf->Cell(20,4,$fp_sd3,1,0,'C',$fill);  
            $posx = $this->fpdf->GetX();
            $posy = $this->fpdf->GetY();
            $posx = number_format($posx)-20;
            $posy = $posy+4;
            $posx2 = number_format($posx)+20;
            $posy2 = $posy-4;
            $this->fpdf->SetY($posy);
            $this->fpdf->SetX($posx); 
            $this->fpdf->Cell(20,4,$fp_ad3,1,0,'C',$fill);
            $this->fpdf->SetY($posy2);
            $this->fpdf->SetX($posx2);
            
            $this->fpdf->Cell(20,4,$fp_sd4,1,0,'C',$fill); 
            $posx = $this->fpdf->GetX();
            $posy = $this->fpdf->GetY();
            $posx = number_format($posx)-20;
            $posy = $posy+4;
            $posx2 = number_format($posx)+20;
            $posy2 = $posy-4;
            $this->fpdf->SetY($posy);
            $this->fpdf->SetX($posx);  
            $this->fpdf->Cell(20,4,$fp_ad4,1,0,'C',$fill);
            $this->fpdf->SetY($posy2);
            $this->fpdf->SetX($posx2); 
            
            $this->fpdf->Cell(20,4,$fp_sd5,1,0,'C',$fill); 
            $posx = $this->fpdf->GetX();
            $posy = $this->fpdf->GetY();
            $posx = number_format($posx)-20;
            $posy = $posy+4;
            $posx2 = number_format($posx)+20;
            $posy2 = $posy-4;
            $this->fpdf->SetY($posy);
            $this->fpdf->SetX($posx);  
            $this->fpdf->Cell(20,4,$fp_ad5,1,0,'C',$fill);
            $this->fpdf->SetY($posy2);
            $this->fpdf->SetX($posx2);
            
            $this->fpdf->Cell(20,4,$fp_sd6,1,0,'C',$fill); 
            $posx = $this->fpdf->GetX();
            $posy = $this->fpdf->GetY();
            $posx = number_format($posx)-20;
            $posy = $posy+4;
            $posx2 = number_format($posx)+20;
            $posy2 = $posy-4;
            $this->fpdf->SetY($posy);
            $this->fpdf->SetX($posx);  
            $this->fpdf->Cell(20,4,$fp_ad6,1,0,'C',$fill);
            $this->fpdf->SetY($posy2);
            $this->fpdf->SetX($posx2);
            
            $this->fpdf->Cell(20,4,$fp_sd7,1,0,'C',$fill); 
            $posx = $this->fpdf->GetX();
            $posy = $this->fpdf->GetY();
            $posx = number_format($posx)-20;
            $posy = $posy+4;
            $posx2 = number_format($posx)+20;
            $posy2 = $posy-4;
            $this->fpdf->SetY($posy);
            $this->fpdf->SetX($posx);  
            $this->fpdf->Cell(20,4,$fp_ad7,1,0,'C',$fill);
            $this->fpdf->SetY($posy2);
            $this->fpdf->SetX($posx2);
            
            $this->fpdf->Cell(20,4,$fp_sd8,1,0,'C',$fill); 
            $posx = $this->fpdf->GetX();
            $posy = $this->fpdf->GetY();
            $posx = number_format($posx)-20;
            $posy = $posy+4;
            $posx2 = number_format($posx)+20;
            $posy2 = $posy-4;
            $this->fpdf->SetY($posy);
            $this->fpdf->SetX($posx);  
            $this->fpdf->Cell(20,4,$fp_ad8,1,0,'C',$fill);
            $this->fpdf->SetY($posy2);
            $this->fpdf->SetX($posx2);
            
            $this->fpdf->Cell(20,4,$fp_sd9,1,0,'C',$fill); 
            $posx = $this->fpdf->GetX();
            $posy = $this->fpdf->GetY();
            $posx = number_format($posx)-20;
            $posy = $posy+4;
            $posx2 = number_format($posx)+20;
            $posy2 = $posy-4;
            $this->fpdf->SetY($posy);
            $this->fpdf->SetX($posx);  
            $this->fpdf->Cell(20,4,$fp_ad9,1,0,'C',$fill);
            $this->fpdf->SetY($posy2);
            $this->fpdf->SetX($posx2);   
            
            $this->fpdf->Cell(20,4,$fp_sd10,1,0,'C',$fill); 
            $posx = $this->fpdf->GetX();
            $posy = $this->fpdf->GetY();
            $posx = number_format($posx)-20;
            $posy = $posy+4;
            $posx2 = number_format($posx)+20;
            $posy2 = $posy-4;
            $this->fpdf->SetY($posy);
            $this->fpdf->SetX($posx);  
            $this->fpdf->Cell(20,4,$fp_ad10,1,0,'C',$fill);
            $this->fpdf->SetY($posy2);
            $this->fpdf->SetX($posx2);
            
            $this->fpdf->Cell(20,4,$fp_sd11,1,0,'C',$fill); 
            $posx = $this->fpdf->GetX();
            $posy = $this->fpdf->GetY();
            $posx = number_format($posx)-20;
            $posy = $posy+4;
            $posx2 = number_format($posx)+20;
            $posy2 = $posy-4;
            $this->fpdf->SetY($posy);
            $this->fpdf->SetX($posx);  
            $this->fpdf->Cell(20,4,$fp_ad11,1,0,'C',$fill);
            $this->fpdf->SetY($posy2);
            $this->fpdf->SetX($posx2);
            
            $this->fpdf->Cell(20,4,$fp_sd12,1,0,'C',$fill); 
            $posx = $this->fpdf->GetX();
            $posy = $this->fpdf->GetY();
            $posx = number_format($posx)-20;
            $posy = $posy+4;
            $posx2 = number_format($posx)+20;
            $posy2 = $posy-4;
            $this->fpdf->SetY($posy);
            $this->fpdf->SetX($posx);  
            $this->fpdf->Cell(20,4,$fp_ad12,1,0,'C',$fill);
            $this->fpdf->SetY($posy2);
            $this->fpdf->SetX($posx2);
                         
            $this->fpdf->Cell(12,8,$client->drop_out_reason,1,0,'C',$fill);
            $this->fpdf->Cell(15,8,$date_started,1,1,'C',$fill);
            
            $fp_sd1 = "";
            $fp_ad1 = "";
        
            $fp_sd2 = "";
            $fp_ad2 = "";
        
            $fp_sd3 = "";
            $fp_ad3 = "";
        
            $fp_sd4 = "";
            $fp_ad4 = "";
        
            $fp_sd5 = "";
            $fp_ad5 = "";
        
            $fp_sd6 = "";
            $fp_ad6 = "";
        
            $fp_sd7 = "";
            $fp_ad7 = "";
        
            $fp_sd8 = "";
            $fp_ad8 = "";
        
            $fp_sd9 = "";
            $fp_ad9 = "";
        
            $fp_sd10 = "";
            $fp_ad10 = "";
       
            $fp_sd11 = "";
            $fp_ad11 = "";
        
            $fp_sd12 = "";
            $fp_ad12 = "";
            
          if($i2==17)
          {
              if($x2<$totalitems)
              {              
                $this->fpdf->SetY(-15);
                $this->fpdf->SetTextColor(128);
                $this->fpdf->SetFont('Helvetica','I',7);
                $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
                $this->footer();
                $this->legend_page2();
                
                $i2 = 1;
                $currentpage = $currentpage + 1;
                
                $this->fpdf->SetTextColor(0,0,0);
                
                $this->fpdf->AliasNbPages();
                $this->fpdf->AddPage('L','A4');
                $this->fpdf->SetAutoPageBreak(true, 10);
                                    
                $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
                $this->fpdf->SetY(10);
                $this->fpdf->SetFont('Helvetica','B',12);
                $this->fpdf->SetTextColor(0,0,0);
                $this->fpdf->Cell(0,4,strtoupper('Client List for Family Planning - '.$fpmethod),0,0,'C');
                
                $this->fpdf->SetY(15);
                $this->fpdf->SetFillColor(208,208,255);
                $fill = true;
                $this->fpdf->SetFont('Helvetica','',8);
                
                $this->fpdf->Cell(10,4,'','LTRb',0,'C',$fill);
                $this->fpdf->Cell(240,4,'FOLLOW-UP VISITS','LTRb',0,'C',$fill);
                $this->fpdf->Cell(27,4,'','LTRb',1,'C',$fill);
                        
                $this->fpdf->SetFont('Helvetica','B',8);
                $this->fpdf->Cell(10,4,'NO.','LtRb',0,'C',$fill);
                $this->fpdf->SetFont('Helvetica','',8);
                $this->fpdf->Cell(240,4,strtoupper('Upper Space: Next Service Date / Lower Space: Date Accomplished'),'LtRb',0,'C',$fill);
                $this->fpdf->Cell(27,4,'DROP-OUTS','LtRb',1,'C',$fill);
                
                $this->fpdf->SetFont('Helvetica','',7.5);
                $this->fpdf->Cell(10,4,'','LtRb',0,'C',$fill);
                $this->fpdf->Cell(240,4,'','LtRb',0,'C',$fill);
                $this->fpdf->Cell(27,4,'','LtRb',1,'C',$fill);
                
                $this->fpdf->SetFont('Helvetica','',7.5);
                $this->fpdf->Cell(10,4,'','LtRb',0,'C',$fill);
                $this->fpdf->Cell(240,4,'(11)','LtRb',0,'C',$fill);
                $this->fpdf->Cell(27,4,'(12)','LtRb',1,'C',$fill);
                
                $this->fpdf->Cell(10,4,'(10)','LtRB',0,'C',$fill);
                $this->fpdf->Cell(20,4,'1ST',1,0,'C',$fill);
                $this->fpdf->Cell(20,4,'2ND',1,0,'C',$fill);
                $this->fpdf->Cell(20,4,'3RD',1,0,'C',$fill);
                $this->fpdf->Cell(20,4,'4TH',1,0,'C',$fill);
                $this->fpdf->Cell(20,4,'5TH',1,0,'C',$fill);
                $this->fpdf->Cell(20,4,'6TH',1,0,'C',$fill);
                $this->fpdf->Cell(20,4,'7TH',1,0,'C',$fill);
                $this->fpdf->Cell(20,4,'8TH',1,0,'C',$fill);
                $this->fpdf->Cell(20,4,'9TH',1,0,'C',$fill);
                $this->fpdf->Cell(20,4,'10TH',1,0,'C',$fill);
                $this->fpdf->Cell(20,4,'11TH',1,0,'C',$fill);
                $this->fpdf->Cell(20,4,'12TH',1,0,'C',$fill);
                $this->fpdf->Cell(12,4,'REASON',1,0,'C',$fill);
                $this->fpdf->Cell(15,4,'DATE',1,1,'C',$fill);
                
              }
          }
          else
          {
              $i2=$i2+1;
          } 
          $x2=$x2+1;          
        }
        
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',7);
        $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
        $this->footer();
        $this->legend_page2();
        
        $this->fpdf->Output('TCL_Family_Planning.pdf','I');        
	} 
    
    function footer()
    {
        //Draw a line
        $this->fpdf->SetY(-15);
        
        //Page footer
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',7);
        //$this->fpdf->Cell(10,4,'Page '.$this->fpdf->PageNo().'/{nb}',0,0,'C');
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(0,4,'Copyright © 2013 IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
    } 
    
    function tcl_header()
    {
        //Draw a line
        $this->fpdf->SetY(5);
        $this->fpdf->SetX(255);
        
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','B',20);
        $this->fpdf->Cell(30,10,'TCL - FP',0,1,'C');
    } 
    
    function legend_page1()
    {
        //Draw a line
        $this->fpdf->SetY(173);
        $this->fpdf->SetX(10);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','BI',8);
        $this->fpdf->Cell(30,4,'* Type of Clients:',0,0,'L');
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->Cell(40,4,'CU = Current Users',0,0,'L');
        $this->fpdf->SetFont('Helvetica','BI',7.5);
        $this->fpdf->Cell(70,4,'** Family Planning Methods:',0,1,'L');
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->SetX(40);
        $this->fpdf->Cell(40,4,'NA = New Acceptors',0,0,'L');
        $this->fpdf->Cell(70,4,'CON = Condom',0,0,'L');
        $this->fpdf->Cell(60,4,'NFP-BBT = Basal Body Temperature',0,0,'L');
        $this->fpdf->Cell(70,4,'NFP-SDM = Standard Days Method',0,1,'L');
        $this->fpdf->SetX(40);
        $this->fpdf->Cell(40,4,'CM = Changing Method',0,0,'L');
        $this->fpdf->Cell(70,4,'INJ = Depo-medroxy Progesterone Acetate (DMPA)',0,0,'L');
        $this->fpdf->Cell(60,4,'NFB-CM = Cervical Mucus Method ',0,0,'L');
        $this->fpdf->Cell(70,4,'MSTR/Vasec = Male Ster/Vasectomy',0,1,'L');
        $this->fpdf->SetX(40);
        $this->fpdf->Cell(40,4,'CC = Changing Clinic',0,0,'L');
        $this->fpdf->Cell(70,4,'IUD = Intra-uterine Device',0,0,'L');
        $this->fpdf->Cell(60,4,'NFP-STM = Sympothermal Method ',0,0,'L');
        $this->fpdf->Cell(70,4,'FSTR/BTL = Female Ster/Bilateral Tubal Ligation',0,1,'L');
        $this->fpdf->SetX(40);
        $this->fpdf->Cell(40,4,'RS = Restart',0,0,'L');   
        $this->fpdf->Cell(70,4,'PILLS = Pills',0,0,'L');
        $this->fpdf->Cell(60,4,'NFP-LAM = Lacational Amenorrhea Method',0,1,'L');    
    } 
    
    function legend_page2()
    {
        //Draw a line
        $this->fpdf->SetY(173);
        $this->fpdf->SetX(30);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','BI',7.5);
        $this->fpdf->Cell(30,4,'*** Reasons:',0,0,'L');
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->Cell(75,4,'A = Pregnant',0,0,'L');
        $this->fpdf->Cell(75,4,'F = Husband disapproves',0,0,'L');
        $this->fpdf->Cell(75,4,'K = Lack of Supply',0,1,'L');
        $this->fpdf->SetX(60);
        $this->fpdf->Cell(75,4,'B = Desire to become pregnant',0,0,'L');
        $this->fpdf->Cell(75,4,'G = Menopause',0,0,'L');
        $this->fpdf->Cell(75,4,'L = Unknown',0,1,'L');
        $this->fpdf->SetX(60);
        $this->fpdf->Cell(75,4,'C = Medical complicationns',0,0,'L');
        $this->fpdf->Cell(75,4,'H = Lost or moved out of the area of residence',0,1,'L');
        $this->fpdf->SetX(60);
        $this->fpdf->Cell(75,4,'D = Fear of side effects',0,0,'L');
        $this->fpdf->Cell(75,4,'I = Failed to get supply',0,1,'L');
        $this->fpdf->SetX(60);
        $this->fpdf->Cell(75,4,'E = Changed clinic',0,0,'L');
        $this->fpdf->Cell(75,4,'J = IUD expelled',0,1,'L');  
    }
    
    function legend_page3()
    {
        $this->fpdf->SetY(164);
        $this->fpdf->SetX(15);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','BI',7.5);
        $this->fpdf->Cell(35,3.5,'*Diagnosis/Findings:',1,0,'L');
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->Cell(35,3.5,'',1,0,'L');
        $this->fpdf->SetFont('Helvetica','BI',7.5);
        $this->fpdf->Cell(202,3.5,'**Recommended Vitamin A Supplementation Given to High Risk/Sick Children',1,1,'L');
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
    	
}
