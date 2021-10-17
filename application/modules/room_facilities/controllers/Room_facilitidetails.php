<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_facilitidetails extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->load->model(array(
			'roomfacilitidetails_model'
		));	
    }
    public function testdatatable(){
		$this->permission->method('Room_serviceModel','read')->redirect();
        $data['title']    = display('room_facilities_list'); 
		$data['module'] = "room_facilities";
        $data['page']   = "roomfacilitydetailslist";   
        echo Modules::run('template/layout', $data); 
		}
	
	public function responses(){
		$params = $columns = $totalRecords = $data = array();
		$params = $_REQUEST;
	    $columns = array( 
		0 => 'roomfacilitydetails.facilityid', 
		1 => 'facilitytypetitle', 
		2 => 'facilitytitle'
	);

	$where = $sqlTot = $sqlRec = "";
	// check search value exist
	if(!empty($params['search']['value']) ) {   
		$where .=" WHERE ";
		$where .=" ( roomfacilitytype.facilitytypetitle LIKE '".$params['search']['value']."%' ";    
		$where .=" OR roomfacilitydetails.facilitytitle LIKE '".$params['search']['value']."%' )";
	}
	// getting total number records without any search
	$sql = "SELECT roomfacilitydetails.*,roomfacilitytype.facilitytypetitle FROM roomfacilitydetails Left Join roomfacilitytype ON roomfacilitytype.facilitytypeid=roomfacilitydetails.facilitytypeid";
	
	
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where) && ($where != '')) {
		$sqlTot .= $where;
		$sqlRec .= $where;
	}
	
 	$sqlRec .=  " ORDER BY ".$columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']." LIMIT ".$params['start']." ,".$params['length']." ";
	$SQLtotal=$this->db->query($sqlTot);
	$SQLoffer=$this->db->query($sqlRec);
	$totalRecords = $SQLtotal->num_rows();	
	$queryRecords=$SQLoffer->result();
	$i=0;
	foreach($queryRecords as  $value){
		$i++;
		$row = array();
		$update='';
		$delete='';
		if($this->permission->method('Room_serviceModel','update')->access()):
		$update='<input name="url" type="hidden" id="url_'.$value->facilityid.'" value="'.base_url().'room_facilities/room_facilitidetails/updateintfrm" /><a onclick="editinforoom('.$value->facilityid.')" class="btn btn-info btn-sm margin_right_5px" data-toggle="tooltip" data-placement="left" title="Update"><i class="ti-pencil-alt text-white" aria-hidden="true"></i></a>';
		endif;
		 if($this->permission->method('Room_serviceModel','delete')->access()):
		 $delete='<input name="delurl" type="hidden" id="delurl_'.$value->facilityid.'" value="'.base_url().'room_facilities/room-facilities-details-delete/'.$value->facilityid.'" /><a onclick="deleteitem('.$value->facilityid.');" class="btn btn-danger btn-sm color_fff" data-toggle="tooltip" data-placement="right" title="Delete "><i class="ti-trash text-white"></i></a>';
		 endif;
		$row[] =$i;
		$row[] =$value->facilitytypetitle;
		$row[] =$value->facilitytitle;
		$row[] =$update.$delete;
        $data[] = $row;
		
		}
	
	$json_data = array(
			"draw"            => intval( $params['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval($totalRecords),
			"data"            => $data   // total data array
			);

	echo json_encode($json_data);
	
	}
    public function index($id = null)
    {
		$this->permission->method('Room_serviceModel','read')->redirect();
        $data['title']    = 'Facility Details'; 
		$data['facilitytype']   = $this->roomfacilitidetails_model->allfacilitytype();
		$data['module'] = "room_facilities";
        $data['page']   = "roomfacilitydetailslist";   
        echo Modules::run('template/layout', $data); 		
    }
	
	
    public function create($id = null)
    {
	  $data['title'] = 'Room Size';
	  $this->form_validation->set_rules('facilititypeyname',display('add_facility_type'),'required|xss_clean');
	  $this->form_validation->set_rules('facilityname',display('facilityname'),'required|max_length[50]|xss_clean');
	  $saveid=$this->session->userdata('id');
	  $data['intinfo']="";
	  if ($this->form_validation->run()) {
	   if(empty($this->input->post('facilityid', TRUE))) {
		$facility = $this->input->post('facilititypeyname',TRUE);
		$facility_details = $this->input->post('facilityname',TRUE);
		$this->db->where('facilitytypeid',$facility);
		$this->db->where('LOWER(facilitytitle)',strtolower($facility_details));
		$this->db->FROM('roomfacilitydetails');
		$query = $this->db->get();
		$result = $query->row();
		if($result<=0){
		 $data['Room_serviceModel']   = (Object) $postData = array(
		   'facilityid'     	 => $this->input->post('facilityid', TRUE),
		   'facilitytypeid'      => $this->input->post('facilititypeyname', TRUE),
		   'facilitytitle' 	     => $this->input->post('facilityname',TRUE),
		  );
		} else {
			$this->session->set_flashdata('exception',  display('facility_details'));
			redirect("room_facilities/room-facilities-details-list"); 
		   }
		$this->permission->method('Room_serviceModel','create')->redirect();
		if ($this->roomfacilitidetails_model->create($postData)) { 
		 $this->session->set_flashdata('message', display('save_successfully'));
		 redirect('room_facilities/room-facilities-details-list');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("room_facilities/room-facilities-details-list"); 
	
	   } else {
		$this->permission->method('Room_serviceModel','update')->redirect();
		$data['Room_serviceModel']   = (Object) $postData = array(
		    'facilityid'     	 => $this->input->post('facilityid', TRUE),
		   'facilitytypeid'      => $this->input->post('facilititypeyname', TRUE),
		   'facilitytitle' 	     => $this->input->post('facilityname',TRUE),
		  );
	 
		if ($this->roomfacilitidetails_model->update($postData)) { 
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("room_facilities/room-facilities-details-list");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('room_facilities_edit');
		$data['intinfo']   = $this->roomfacilitidetails_model->findById($id);
	   }
	   $data['facilitytype']   = $this->roomfacilitidetails_model->allfacilitytype();
	   $data['module'] = "room_facilities";
	   $data['page']   = "roomfacilitydetailslist";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
	}
   public function updateintfrm($id){
	  
		$this->permission->method('Room_serviceModel','update')->redirect();
		$data['title'] = display('room_facilities_edit');
		$data['intinfo']   = $this->roomfacilitidetails_model->findById($id);
		$data['facilitytype']   = $this->roomfacilitidetails_model->allfacilitytype();
        $data['module'] = "room_facilities";  
        $data['page']   = "roomfacilitydetailsedit";
		$this->load->view('room_facilities/roomfacilitydetailsedit', $data);   
	   }
 
    public function delete($id = null)
    {
        $this->permission->module('Room_serviceModel','delete')->redirect();
		
		if ($this->roomfacilitidetails_model->delete($id)) {
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('room_facilities/room-facilities-details-list');
    }
 
}
