<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_info extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->load->model(array(
			'customer_model'
		));	
    }
 
    public function index($id = null)
    {
		$this->permission->method('customer','read')->redirect();
        $data['title']    = display('customer_list'); 
        #
        #pagination starts
        #
        $config["base_url"] = base_url('customer/customer_info/index');
        $config["total_rows"]  = $this->customer_model->countlist();
        $config["per_page"]    = 15;
        $config["uri_segment"] = 4;
        /* This Application Must Be Used With BootStrap 4 * */
        $config['full_tag_open']='<ul class="pagination pagination-md">';
        $config['full_tag_close']='</ul>';
		$config['first_link'] = false;
		$config['first_tag_open'] = '<li class="page-item disabled">';
        $config['first_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item"><a class="page-link active">';
        $config['cur_tag_close'] = '</a></li>';
		$config['next_link'] = '<i class="ti-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = '</a></li>';
		$config['prev_link'] = '<i class="ti-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = '</li>';
		$config['last_link'] =false;
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["customer_infolist"] = $this->customer_model->read();
        $data["links"] = $this->pagination->create_links();

		if(!empty($id)) {
		$data['title'] = display('customer');
		$data['intinfo']   = $this->customer_model->findById($id);
	   }
        #pagination ends
        #   
        $data['module'] = "customer";
        $data['page']   = "customerlist";   
        echo Modules::run('template/layout', $data); 
    }
	function _alpha_dash_space($str_in = '',$fields=''){
		if (! preg_match("/^([-a-z0-9_. ])+$/i", $str_in))
		{
			$this->form_validation->set_message('_alpha_dash_space', 'The '.$fields.' field may only contain alpha-numeric characters,Space,underscores, and dashes.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
    public function create($id = null)
    {
	  $this->load->library(array('my_form_validation'));
	  $data['title'] = display('customer');
	  $this->form_validation->set_rules('firstname',display('firstname'),'required|xss_clean');
	  $this->form_validation->set_rules('lastname',display('lastname'),'required|xss_clean');
	  $this->form_validation->set_rules('phone',display('phone'),'required|xss_clean|is_natural');
	  $this->form_validation->set_rules('email',display('email'),'required|xss_clean|valid_email');
	  if($this->input->post('nationaliti',TRUE)=='foreigner'){
	  $this->form_validation->set_rules('national_id',display('national_id'),'required|xss_clean|is_natural');
	  $this->form_validation->set_rules('nationalitycon',display('nationality'),'required|xss_clean');
	  $this->form_validation->set_rules('passport_no',display('passport_no'),'required|xss_clean');
	  $this->form_validation->set_rules('visa_reg_no',display('visa_reg_no'),'required|xss_clean');
	  $this->form_validation->set_rules('purpose',display('purpose'),'required|xss_clean');
	  }
	  $this->form_validation->set_rules('address',display('address'),'xss_clean');
	  $saveid=$this->session->userdata('id');
	  $this->input->post('discount',true);
	  
	  $data['intinfo']="";
	  if ($this->form_validation->run($this)) { 
	   if(empty($this->input->post('customerid',TRUE))) {
		$this->permission->method('customer','create')->redirect();
		  $lastid=$this->db->select("*")->from('customerinfo')->order_by('customerid','desc')->get()->row();
		if(!empty($lastid)){
			$sl=$lastid->customerid;
			}
		else{
			$sl = "0001"; 
			}
		$nextno=$sl+1;
		$si_length = strlen((int)$nextno); 
		
		$str = '0000';
		$cutstr = substr($str, $si_length); 
		$sino = $cutstr.$nextno; 
		
		$postData = array(
		   'customerid'     	        => $this->input->post('customerid',TRUE),
		   'firstname'     	    		=> $this->input->post('firstname',TRUE),
		   'customernumber' 	        => $sino,
		   'lastname' 	        		=> $this->input->post('lastname',TRUE),
		   'cust_phone' 	         	=> $this->input->post('phone',TRUE),
		   'email' 	             		=> $this->input->post('email',TRUE),
		   'dob' 	             		=> $this->input->post('dob',TRUE),
		   'profession' 	         	=> $this->input->post('profession',TRUE),
		   'isnationality' 	         	=> $this->input->post('nationaliti',TRUE),
		   'nid' 	         		    => $this->input->post('national_id',TRUE),
		   'nationality' 	         	=> $this->input->post('nationalitycon',TRUE),
		   'passport' 	         		=> $this->input->post('passport_no',TRUE),
		   'visano' 	         		=> $this->input->post('visa_reg_no',TRUE),
		   'purpose' 	         		=> $this->input->post('purpose',TRUE),
		   'address' 	         		=> $this->input->post('address',TRUE),
		   'signupdate'					=> date('Y-m-d')
		  );
		$this->db->insert('customerinfo',$postData);
		$customerid = $this->db->insert_id();
		
		$coa = $this->customer_model->headcode();
        if($coa->HeadCode!=NULL){
            $headcode=$coa->HeadCode+1;
        }
        else{
            $headcode="102030101";
        }
		//insert Coa for Customer Receivable
		$c_name = $this->input->post('firstname',TRUE)." ".$this->input->post('lastname',TRUE);
        $c_acc=$sino.'-'.$c_name;
		$createdate=date('Y-m-d H:i:s');
		$postData1['HeadCode']   	=$headcode;
		$postData1['HeadName']   	=$c_acc;
		$postData1['PHeadName']   	='Customer Receivable';
		$postData1['HeadLevel']   	='4';
		$postData1['IsActive']  	='1';
		$postData1['IsTransaction'] ='1';
		$postData1['IsGL']   		='0';
		$postData1['HeadType']  	='A';
		$postData1['IsBudget'] 		='0';
		$postData1['IsDepreciation']='0';
		$postData1['DepreciationRate']='0';
		$postData1['CreateBy'] 		=$customerid;
		$postData1['CreateDate'] 	=$createdate;
		$this->db->insert('acc_coa',$postData1);
		
		 $this->session->set_flashdata('message', display('save_successfully'));
           if (isset($_POST['instant'])){
               redirect("room_reservation/room-booking");

           }elseif (isset($_POST['customer'])){
               redirect("customer/customer-list");
           }
	
	   } else {
		$this->permission->method('customer','update')->redirect();
		$coahead=$this->input->post('coahead',TRUE);
		$newheadname=$this->input->post('customernumber',TRUE).'-'.$this->input->post('firstname',TRUE).' '.$this->input->post('lastname',TRUE);
		$data['customer']   = (Object) $postData3 = array(
		   'customerid'     	        => $this->input->post('customerid',TRUE),
		   'firstname'     	    		=> $this->input->post('firstname',TRUE),
		   'lastname' 	        		=> $this->input->post('lastname',TRUE),
		   'cust_phone' 	         	=> $this->input->post('phone',TRUE),
		   'email' 	             		=> $this->input->post('email',TRUE),
		   'dob' 	             		=> $this->input->post('dob',TRUE),
		   'profession' 	         	=> $this->input->post('profession',TRUE),
		   'isnationality' 	         	=> $this->input->post('nationaliti',TRUE),
		   'nid' 	         		    => $this->input->post('national_id',TRUE),
		   'nationality' 	         	=> $this->input->post('nationalitycon',TRUE),
		   'passport' 	         		=> $this->input->post('passport_no',TRUE),
		   'visano' 	         		=> $this->input->post('visa_reg_no',TRUE),
		   'purpose' 	         		=> $this->input->post('purpose',TRUE),
		   'address' 	         		=> $this->input->post('address',TRUE),
		   'signupdate'					=>	date('Y-m-d')
		  );
	 
		if ($this->customer_model->update($postData3)) { 
		
		$dataup = array('HeadName'     => $newheadname);
		$this->db->where('HeadCode',$coahead);
		$this->db->update('acc_coa',$dataup);
		 
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}


           if (isset($_POST['instant'])){
               redirect("room_reservation/room-booking");

           }elseif (isset($_POST['customer'])){
               redirect("customer/customer-list");
           }


	   }
	  } else { 
		    if(!empty($id)) {
				$data['title'] = display('update_customer');
				$data['intinfo']   = $this->customer_model->findById($id);
				$data['module'] = "customer";
				$data['page']   = "customeredit";   
				echo Modules::run('template/layout', $data); 
		   }else{
	   
		   $data['module'] = "customer";
		   $data['page']   = "addcustomerlist";   
		   echo Modules::run('template/layout', $data); 
		}
	   }   
 
    }
   public function updateintfrm($id){
		$this->permission->method('customer','update')->redirect();
		$customerinfo=$this->db->select("*")->from('customerinfo')->where('customerid',$id)->get()->row();
		$customerhead= $customerinfo->customernumber.'-'.$customerinfo->firstname.' '.$customerinfo->lastname;
		$coahead=$this->db->select("HeadCode")->from('acc_coa')->where('HeadName',$customerhead)->get()->row();
		$data['title'] = display('customer_edit');
		$data['intinfo']   = $this->customer_model->findById($id);
		$data['customerhead']=$coahead->HeadCode;
        $data['module'] = "customer";  
        $data['page']   = "customeredit";
        echo Modules::run('template/layout', $data); 
	   }
 
    public function delete($id = null)
    {
        $this->permission->module('customer','delete')->redirect();
		if ($this->customer_model->delete($id)) {
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('customer/customer-list');
    }
 
}
