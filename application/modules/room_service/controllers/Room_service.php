<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Room_service extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->load->model(array(
            'room_service_model'
		));	
    }
 
    public function index($id = null)
    {
        
		$this->permission->method('room_service','read')->redirect();
        $data['title']    = 'Room Service';
        #
        #pagination starts
        #
        $config["base_url"] = base_url('room_service/index');
        $config["total_rows"]  = $this->room_service_model->countlist();
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
        $result=$this->room_service_model->read();

        $data["facilitilist"] = $result;
     //   $data["var_list"] = $variation;
        $data["links"] = $this->pagination->create_links();
	//	echo '<pre>';print_r($data["facilitilist"]);exit();
		if(!empty($id)) {
		$data['title'] = 'Service edit';
		$data['intinfo']   = $this->room_service_model->findById($id);

	   }


        #
        #pagination ends
        #
        $data['module'] = "room_service";
        $data['page']   = "room_service";
        echo Modules::run('template/layout', $data);
    }

//    public function add_service(){
//
//        echo 'Hello World';
//    }
	
	
    public function create($id = null)
    {
	  $data['title'] = 'Add Service';
	  $this->form_validation->set_rules('service_name','service_name','required|max_length[50]|xss_clean');
	  $saveid=$this->session->userdata('id');
	  $data['intinfo']="";
	  if ($this->form_validation->run()) { 
	   if(empty($this->input->post('service_id', TRUE))) {
		 $data['room_service']   = (Object) $postData = array(
		   'service_id'     	 => $this->input->post('service_id', TRUE),
		   'service_name' 	     => $this->input->post('service_name',TRUE),
		  );
		$this->permission->method('room_service','create')->redirect();
		if ($this->room_service_model->create($postData)) {
		 $this->session->set_flashdata('message', display('save_successfully'));
		 redirect('room_service/');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("room_service/");
	
	   } else {
		$this->permission->method('room_service','update')->redirect();


           $data['room_service']   = (Object) $postData = array(
               'service_id'     	 => $this->input->post('service_id', TRUE),
               'service_name' 	     => $this->input->post('service_name',TRUE),
           );
	 
		if ($this->room_service_model->update($postData)) {
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("room_service/");
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = "Edit Service";
		$data['intinfo']   = $this->room_service_model->findById($id);

	   }
	    #
        #pagination starts
        #
        $config["base_url"] = base_url('room_service/room_service/index');
        $config["total_rows"]  = $this->room_service_model->countlist();
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
        $data["facilitilist"] = $this->room_service_model->read();
        $data["links"] = $this->pagination->create_links();
	   $data['module'] = "room_service";
	   $data['page']   = "room_service";
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
   public function updateintfrm($id){
		$this->permission->method('room_service','update')->redirect();
		$data['title'] = 'Edit Service';
		$data['intinfo']   = $this->room_service_model->findById($id);
       $data['v_list']   = $this->room_service_model->variation_list_by_id($id);
        $data['module'] = "room_service";
        $data['page']   = "room_service_edit";
		$this->load->view('room_service/room_service_edit', $data);
	   }
 
    public function delete($id = null)
    {
        $this->permission->module('room_service','delete')->redirect();
		if ($this->room_service_model->delete($id)) {
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('room_service/');
    }
 
}
