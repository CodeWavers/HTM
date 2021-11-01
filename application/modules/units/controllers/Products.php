<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->load->model(array(
			'product_model',
			'unit_model'
		));	
    }
 
    public function index($id = null)
    {
        
		$this->permission->method('units','read')->redirect();
        $data['title']    = display('ingradient_list'); 
        #
        #pagination starts
        #
        $config["base_url"] = base_url('units/products/index');
        $config["total_rows"]  = $this->product_model->count_ingredient();
        $config["per_page"]    = 15;
        $config["uri_segment"] = 4;
        $config["last_link"] = "Last"; 
        $config["first_link"] = "First"; 
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';  
        $config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["ingredientlist"] = $this->product_model->read_ingredient();
        $data["links"] = $this->pagination->create_links();
		$data['pagenum']=$page;
		if(!empty($id)) {
		$data['title'] = display('unit_update');
		$data['intinfo']   = $this->unit_model->findById($id);
	   }
	    $data['unitdropdown']   =  $this->unit_model->ingredient_dropdown();
        #
        #pagination ends
        #   
        $data['module'] = "units";
        $data['page']   = "ingredientlist";   
        echo Modules::run('template/layout', $data); 
    }

    public function create($id = null)
    {
	  $data['title'] = display('add_ingredient');
	  if (empty($this->input->post('id', TRUE))) {
		$this->form_validation->set_rules('ingredientname',display('ingredient_name'),'required|is_unique[products.product_name]|max_length[50]|xss_clean');
	  }
		$this->form_validation->set_rules('unitid',display('unit_name')  ,'required|is_natural|xss_clean');
		$this->form_validation->set_rules('status', display('status')  ,'required|is_natural|xss_clean');
	   
	  $data['intinfo']="";
	  $data['units']   = (Object) $postData = array(
	   'id'     => $this->input->post('id', TRUE),
	   'product_name' 	 => $this->input->post('ingredientname',TRUE),
	   'uom_id' 	 		 => $this->input->post('unitid',TRUE),
	   'is_active' 	 	     => $this->input->post('status',TRUE),
	  );
	  if ($this->form_validation->run()) { 
	   if (empty($this->input->post('id', TRUE))) {
		$this->permission->method('units','create')->redirect();
		if ($this->product_model->unit_ingredient($postData)) { 
		    $this->db->select('*');
			$this->db->from('products');
			$this->db->where('is_active',1);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$json_product[] = array('label'=>$row->product_name,'value'=>$row->id);
			}
			$cache_file = './assets/js/products.json';
			$productList = json_encode($json_product);
			file_put_contents($cache_file,$productList);
		 $this->session->set_flashdata('message', display('save_successfully'));
		 redirect('units/product-list');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("units/product-list"); 
	
	   } else {
		$this->permission->method('units','update')->redirect();
	  
		if ($this->product_model->update_ingredient($postData)) { 
		 $this->db->select('*');
			$this->db->from('products');
			$this->db->where('is_active',1);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$json_product[] = array('label'=>$row->product_name,'value'=>$row->id);
			}
			$cache_file = './assets/js/products.json';
			$productList = json_encode($json_product);
			file_put_contents($cache_file,$productList);
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("units/product-list");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('update_ingredient');
		$data['intinfo']   = $this->product_model->findById($id);
	   }
		#
        #pagination starts
        #
	   $config["base_url"] = base_url('units/products/index');
	   $config["total_rows"]  = $this->product_model->count_ingredient();
	   $config["per_page"]    = 15;
	   $config["uri_segment"] = 4;
	   $config["last_link"] = "Last"; 
	   $config["first_link"] = "First"; 
	   $config['next_link'] = 'Next';
	   $config['prev_link'] = 'Prev';  
	   $config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
	   $config['full_tag_close'] = "</ul>";
	   $config['num_tag_open'] = '<li>';
	   $config['num_tag_close'] = '</li>';
	   $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
	   $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
	   $config['next_tag_open'] = "<li>";
	   $config['next_tag_close'] = "</li>";
	   $config['prev_tag_open'] = "<li>";
	   $config['prev_tagl_close'] = "</li>";
	   $config['first_tag_open'] = "<li>";
	   $config['first_tagl_close'] = "</li>";
	   $config['last_tag_open'] = "<li>";
	   $config['last_tagl_close'] = "</li>";
	   /* ends of bootstrap */
	   $this->pagination->initialize($config);
	   $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
	   $data["ingredientlist"] = $this->product_model->read_ingredient($config["per_page"], $page);
	   $data["links"] = $this->pagination->create_links();
	   $data['pagenum']=$page;
	   $data['unitdropdown']   =  $this->unit_model->ingredient_dropdown();
	   $data['module'] = "units";
	   $data['page']   = "ingredientlist";
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
    public function updateintfrm($id){
	  
		$this->permission->method('units','update')->redirect();
		$data['title'] = display('update_ingredient');
		$data['intinfo']   = $this->product_model->findById($id);
		$data['unitdropdown']   =  $this->unit_model->ingredient_dropdown();
        $data['module'] = "units";  
        $data['page']   = "ingredientedit";
		$this->load->view('units/ingredientedit', $data);   
	   }

    public function out_declare(){

        $data['title'] = 'Products Out Declaration';
        $data['module'] = "units";
        $data['page']   = "out_quantity";
      //  $this->load->view('units/unitlist', $data);
        echo Modules::run('template/layout', $data);
    }
 
    public function delete($category = null)
    {
        $this->permission->module('units','delete')->redirect();
		
		if ($this->product_model->ingredient_delete($category)) {
			 $this->db->select('*');
			$this->db->from('products');
			$this->db->where('is_active',1);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$json_product[] = array('label'=>$row->product_name,'value'=>$row->id);
			}
			$cache_file = './assets/js/products.json';
			$productList = json_encode($json_product);
			file_put_contents($cache_file,$productList);
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('units/product-list');
    }


    public function product_out(){



        $result = $this->product_model->product_out();



        if ($result == TRUE) {
            $this->session->set_flashdata('message', display('save_successfully'));
            redirect('units/out-dec');

        } else {
            $this->session->set_flashdata('exception',  display('please_try_again'));
            redirect('units/out-dec');
        }


    }
}
