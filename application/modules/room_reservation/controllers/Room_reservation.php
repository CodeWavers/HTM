<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_reservation extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->load->model(array(
			'roomreservation_model'
		));	
    }
    public function bookingdatatable(){
		$params = $columns = $totalRecords = $data = array();
		$params = $_REQUEST;
	    $columns = array( 
		0 => 'booked_info.bookedid', 
		1 => 'booking_number', 
		2 => 'roomtype',
		3 => 'checkindate',
		4 => 'checkoutdate',
		5 => 'date_time',
		6 => 'bookingstatus',
		7 => 'paid_amount',
	);

	$where = $sqlTot = $sqlRec = "";
	// check search value exist
	if(!empty($params['search']['value']) ) {   
		$where .=" WHERE ";
		$where .=" ( booked_info.booking_number LIKE '".$params['search']['value']."%' ";    
		$where .=" OR roomdetails.roomtype LIKE '".$params['search']['value']."%' ";
		$where .=" OR booked_info.checkindate LIKE '".$params['search']['value']."%' ";
		$where .=" OR booked_info.checkoutdate LIKE '".$params['search']['value']."%' ";
		$where .=" OR booked_info.date_time LIKE '".$params['search']['value']."%' ";
		$where .=" OR booked_info.bookingstatus LIKE '".$params['search']['value']."%' )";
	}
	// getting total number records without any search
	$sql = "SELECT booked_info.*,roomdetails.roomtype FROM booked_info Left Join roomdetails ON roomdetails.roomid=booked_info.roomid";
	
	
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
		if($this->permission->method('room_reservation','update')->access()):
		$update='<input name="url" type="hidden" id="url_'.$value->bookedid.'" value="'.base_url().'room_reservation/room_reservation/updateintfrm" /><a onclick="editinforoom('.$value->bookedid.')" class="btn btn-info btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Update" title="Update"><i class="ti-pencil-alt text-white" aria-hidden="true"></i></a>';
		endif;
		if($this->permission->method('room_reservation','read')->access()):
		$view='<a href="'.base_url().'room_reservation/booking-information/'.$value->bookedid.'" class="btn btn-primary btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="View" title="View"><i class="ti-eye"></i></a>';
		endif;
		 if($this->permission->method('room_reservation','create')->access()):
		 $Payment='<a href="'.base_url().'room_reservation/payment-information/'.$value->bookedid.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Payment" title="Payment"><i class="ti-wallet"></i></a>';
		 endif;
		 if($value->bookingstatus==0){
			 $status="Pending";
			 }
		else if($value->bookingstatus==1){
			$status="Cancel";
			}
		else if($value->bookingstatus==2){
			$status="Success";
			}
		if($value->paid_amount<$value->total_price){
			$paymentStatus="Pending";
			}
		else{
			$paymentStatus="Success";
		}
		$row[] =$i;
		$row[] =$value->booking_number;
		$row[] =$value->roomtype;
		$row[] =$value->checkindate;
		$row[] =$value->checkoutdate;
		$row[] =$value->date_time;
		$row[] =$status;
		$row[] =$paymentStatus;
		$row[] =$update.$view.$Payment;
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
		$this->permission->method('room_reservation','read')->redirect();
		$sc =array('isSeen'         =>  1);  
			    $this->db->update('booked_info',$sc);
				
        $data['title']    = display('room_reservation'); 
	    $data["roomlist"] = $this->roomreservation_model->allrooms();
		$data["customerlist"] = $this->roomreservation_model->customerlist();
        #
        #pagination ends
        #   
        $data['module'] = "room_reservation";
        $data['page']   = "reservationlist";   
        echo Modules::run('template/layout', $data); 
    }
	
	
    public function create($id = null)
    {
	  $data['title'] = display('room_reservation');
	  $this->form_validation->set_rules('guest',display('guest'),'required|xss_clean');
	  $this->form_validation->set_rules('room_name',display('room_name'),'required|xss_clean');
	  $this->form_validation->set_rules('no_of_people',display('no_of_people'),'required|xss_clean');
	  $this->form_validation->set_rules('check_in',display('check_in'),'required|xss_clean');
	  $this->form_validation->set_rules('check_out',display('check_out'),'required|xss_clean');
	  $saveid=$this->session->userdata('id');
	  $this->input->post('discount',true);
	  $data['intinfo']="";
	  if ($this->form_validation->run()) { 
	   if(empty($this->input->post('bookedid', TRUE))) {
		$bookinginfo=$this->db->select("*")->from('booked_info')->order_by('bookedid','desc')->get()->row();
		if(!empty($bookinginfo)){
		$bookno=$bookinginfo->bookedid;
		}
		else{
			$bookno = "00000000"; 
			}
		
		$nextno=$bookno+1;
		$bk_length = strlen((int)$nextno); 
		
		$bkstr = '00000000';
		$bknumber = substr($bkstr, $bk_length); 
		$bookingnumber = $bknumber.$nextno;  
		$length=count($this->input->post('slroomno',TRUE)); 
		$room=$this->input->post('slroomno',TRUE);
		$roomnosel='';
		$custID=$this->input->post('guest', TRUE);
		for($i=0;$i<$length;$i++){
			$roomnosel.=$room[$i].',';
			} 
		 $roomnosel=rtrim($roomnosel,',');  
		 $postData = array(
		   'bookedid'     	     	 => $this->input->post('bookedid', TRUE),
		   'booking_number' 	     => $bookingnumber,
		   'date_time' 	             => date('Y-m-d H:i:s'),
		   'roomid' 	             => $this->input->post('room_name',TRUE),
		   'nuofpeople'              => $this->input->post('no_of_people',TRUE),
		   'total_room'              => $this->input->post('numofroom',TRUE),
		   'room_no'              	 => $roomnosel,
		   'roomrate'                => $this->input->post('roomrate',TRUE),
		   'total_price'             => $this->input->post('gramount',TRUE),
		   'offer_discount'          => $this->input->post('discount',TRUE),
		   'coments'                 => '',
		   'checkindate'             => $this->input->post('check_in',TRUE),
		   'checkoutdate'            => $this->input->post('check_out',TRUE),
		   'cutomerid' 	             => $this->input->post('guest', TRUE),
		   'bookingstatus' 	         => 0
		  
		  );
		$this->permission->method('room_reservation','create')->redirect();
		if($this->roomreservation_model->create($postData)) { 
		 $type = "processing";
		 $response = $this->lsoft_setting->send_sms($bookingnumber, $custID, $type);
		 $data = json_decode($response);
		 $msg = $data->message;
		 $this->session->set_flashdata('message', display('save_successfully'));
		 if($msg)
		 $this->session->set_userdata('msg', $msg);
		 redirect('room_reservation/room-booking');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		 redirect('room_reservation/room-booking');
	   } else {
		$this->permission->method('room_reservation','update')->redirect();
		$roomnosel=$this->input->post('room_no', TRUE);
		$status = $this->input->post('status', TRUE);
		$bookingnumber = $this->input->post('bookingnumber', TRUE);
		$custID = $this->input->post('guest', TRUE);
		if(empty($roomnosel)){
			$length=count($this->input->post('slroomno',TRUE)); 
			$room=$this->input->post('slroomno',TRUE);
			$roomnosel='';
			for($i=0;$i<$length;$i++){
				$roomnosel.=$room[$i].',';
				} 
			 $roomnosel=rtrim($roomnosel,',');  
		}
		$data['room_reservation']   = (Object) $updateData = array(
		   'room_no'              	 => $roomnosel,
		   'bookedid'     	     	 => $this->input->post('bookedid', TRUE),
		   'bookingstatus' 	         => $this->input->post('status', TRUE)
		  );
		if ($this->roomreservation_model->update($updateData)) { 
		if($status==2){
			$type = "completeorder";
			$response = $this->lsoft_setting->send_sms($bookingnumber, $custID, $type);
			$data = json_decode($response);
			$msg = $data->message;
		}
		if($status==1){
			$type = "cancel";
			$response = $this->lsoft_setting->send_sms($bookingnumber, $custID, $type);
			$data = json_decode($response);
			$msg = $data->message;
		}
		 $this->session->set_flashdata('message', display('update_successfully'));
		 if($msg)
		 $this->session->set_userdata('msg', $msg);
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("room_reservation/booking-list");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('reservation_edit');
		$data['intinfo']   = $this->roomreservation_model->findById($id);
	   }
	   $data["roomlist"] = $this->roomreservation_model->allrooms();
	   $data["customerlist"] = $this->roomreservation_model->customerlist();
	   $data['module'] = "room_reservation";
	   $data['page']   = "addbooking";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
   public function updateintfrm($id){
	  
		$this->permission->method('room_reservation','update')->redirect();
		$data['title'] = display('bed_edit');
		$data["roomlist"] = $this->roomreservation_model->allrooms();
		$data["customerlist"] = $this->roomreservation_model->customerlist();
		$data['intinfo']   = $this->roomreservation_model->findById($id);

		$roomname=$data['intinfo']->roomid;
		$checkin=$data['intinfo']->checkindate;
		$checkout=$data['intinfo']->checkoutdate;
		$status=1;
		$exits = $this->db->select("*")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->result();
		$exit = $this->db->select("*")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where('bookingstatus!=',$status)->get()->result();
		$check = $this->db->select("*")->from('booked_info')->where('checkindate>',$checkin)->where('checkoutdate<=',$checkout)->where('bookingstatus!=',$status)->get()->result();
		$totalroom1 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->row();
		$totalroom2 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->row();
		$totalroom3 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate>=',$checkin)->where('checkoutdate<=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomname)->group_by('checkindate')->get()->result();
		$allbokedroom3 = (!empty($allbokedroom3)?max(array_column($totalroom3, 'allroom')):0);
		$totalroomfound=$this->db->select("count(roomid) as totalroom")->from('tbl_roomnofloorassign')->where('roomid',$roomname)->get()->row();
		$roomdetails=$this->db->select("*")->from('roomdetails')->where('roomid',$roomname)->get()->row();
		$numberlist=$this->db->select("*")->from('tbl_roomnofloorassign')->where('roomid',$roomname)->get()->result();
		$roomlist='';
		foreach($numberlist as $singleno){
			$roomlist.=$singleno->roomno.',';
			}
		$gtroomno=rtrim($roomlist,',');
		if(empty($exits)&&empty($exit) && empty($check)){
			$data['freeroom']=$gtroomno;
			$data['isfound']=0;
		}
		else{
			$bookedroom="";
			if(!empty($exits)){
			foreach($exits as $booked){
			$bookedroom.=$booked->room_no.',';
				}
			}
			if(!empty($exit)){
				foreach($exit as $ex){
					$bookedroom.=$ex->room_no.',';
				}
			}
			if(!empty($check)){
				foreach($check as $ch){
					$bookedroom.=$ch->room_no.',';
				}
			}
		$getbookedall=rtrim($bookedroom,',');
		$allbokedroom1=$totalroom1->allroom;
		$allbokedroom2=$totalroom2->allroom;
		$allbokedroom=max((int)$allbokedroom1,(int)$allbokedroom2,(int)$allbokedroom3);
		$allfreeroom=$totalroomfound->totalroom;
				if($allfreeroom>$allbokedroom){
					$output=$this->Differences($getbookedall, $gtroomno);
					if(!empty($output)){
						$data['freeroom']=$output;
						$data['isfound']='1';
							}
					else{
						$data['freeroom']='';
						$data['isfound']='2';
						}
				}
				else{
					$data['freeroom']='';
					$data['isfound']='2';
					}
			}

        $data['module'] = "room_reservation";  
        $data['page']   = "reservationedit";
		$this->load->view('room_reservation/reservationedit', $data);   
	   }
 
    public function delete($id = null)
    {
        $this->permission->module('room_reservation','delete')->redirect();
		
		if ($this->roomreservation_model->delete($id)) {
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('room_reservation/booking-list');
    }
 	public function checkroom(){
	    $guest =$this->input->post('guest',true);
		$roomname=$this->input->post('room_name',true);
		$checkin=$this->input->post('check_in',true);
		$checkout=$this->input->post('check_out',true);
		$status=1;
		$exits = $this->db->select("*")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->result();
		$exit = $this->db->select("*")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->result();
		$check = $this->db->select("*")->from('booked_info')->where('checkindate>',$checkin)->where('checkoutdate<=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->result();
		$totalroom1 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->row();
		$totalroom2 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->row();
		$totalroom3 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate>=',$checkin)->where('checkoutdate<=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomname)->group_by('checkindate')->get()->result();
		$allbokedroom3 = (!empty($allbokedroom3)?max(array_column($totalroom3, 'allroom')):0);
		$totalroomfound=$this->db->select("count(roomid) as totalroom")->from('tbl_roomnofloorassign')->where('roomid',$roomname)->get()->row();
		$roomdetails=$this->db->select("*")->from('roomdetails')->where('roomid',$roomname)->get()->row();
		$numberlist=$this->db->select("*")->from('tbl_roomnofloorassign')->where('roomid',$roomname)->get()->result();
		$roomlist='';
		foreach($numberlist as $singleno){
			$roomlist.=$singleno->roomno.',';
			}
		$gtroomno=rtrim($roomlist,',');
		if(empty($exits)&&empty($exit)&&empty($check)){
				$data['freeroom']=$gtroomno;
				$data['isfound']=0;
			}
		else{
			$bookedroom="";
			if(!empty($exit)){
			foreach($exits as $booked){
				$bookedroom.=$booked->room_no.',';
				}
			}
			if(!empty($exit)){
				foreach($exit as $ex){
					$bookedroom.=$ex->room_no.',';
				}
			}
			if(!empty($check)){
				foreach($check as $ch){
					$bookedroom.=$ch->room_no.',';
				}
			}
			
		$getbookedall=rtrim($bookedroom,',');
		$allbokedroom1=$totalroom1->allroom;
		$allbokedroom2=$totalroom2->allroom;
		$allbokedroom=max((int)$allbokedroom1,(int)$allbokedroom2,(int)$allbokedroom3);
		$allfreeroom=$totalroomfound->totalroom;
				if($allfreeroom>$allbokedroom){
					$output=$this->Differences($getbookedall, $gtroomno);
					if(!empty($output)){
					$data['freeroom']=$output;
					$data['isfound']='1';
						}
					else{
						$data['freeroom']='';
						$data['isfound']='2';
						}
				}
				else{
					$data['freeroom']='';
					$data['isfound']='2';
					}
			}
		$data['checkin']=$checkin;
		$data['checkout']=$checkout;
		$data['guest']=$guest;
		$data['roomno']=$roomname;
		$data['roominfo']=$roomdetails;
		$data['chargeinfo']=$this->roomreservation_model->chargeinfo();
		$data['module'] = "room_reservation";
	    $data['page']   = "bookinginfo";   
	    $this->load->view('room_reservation/bookinginfo', $data);  
	 }
	 
	 public function Differences ($Arg1, $Arg2){
		$Arg1 = explode (',', $Arg1);
		$Arg2 = explode (',', $Arg2);
	
		$Difference_1 = array_diff($Arg1, $Arg2);
		$Difference_2 = array_diff($Arg2, $Arg1);
		$Diff = array_merge($Difference_1, $Difference_2);
		$Difference = implode(',', $Diff);
		return $Difference;
	}
	public function detailView($id){
	   $data["bookinginfo"] = $this->roomreservation_model->findById($id);
	   $data["paymentmethod"] = $this->roomreservation_model->paymentlist();
	   $data["paymentlist"] = $this->roomreservation_model->findBypayId($id);
	    $data['module'] = "room_reservation";
	    $data['page']   = "reservationdetail";   
	    echo Modules::run('template/layout', $data); 
	}
  public function paymentsdatatable($id){
		$params = $columns = $totalRecords = $data = array();
		$params = $_REQUEST;
	    $columns = array( 
		0 => 'tbl_guestpayments.invoice', 
		1 => 'bookingnumber', 
		2 => 'paydate',
		3 => 'paymenttype',
		4 => 'paymentamount',
	);

	$where = $sqlTot = $sqlRec = "";
	// check search value exist
	if(!empty($params['search']['value']) ) {   
		$where .=" WHERE ";
		$where .=" ( tbl_guestpayments.invoice LIKE '".$params['search']['value']."%' ";
		$where .=" OR tbl_guestpayments.bookingnumber LIKE '".$params['search']['value']."%' ";
		$where .=" OR tbl_guestpayments.paydate LIKE '".$params['search']['value']."%' ";
		$where .=" OR tbl_guestpayments.paymentamount LIKE '".$params['search']['value']."%' ";
		$where .=" OR payment_method.payment_method LIKE '".$params['search']['value']."%' )";

	}
	// getting total number records without any search
	$sql = "SELECT tbl_guestpayments.*,booked_info.bookedid,payment_method.payment_method FROM tbl_guestpayments Left Join booked_info ON booked_info.booking_number=tbl_guestpayments.bookingnumber Left Join payment_method ON payment_method.payment_method_id=tbl_guestpayments.paymenttype where booked_info.bookedid='$id'";
	
	
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
		if($this->permission->method('room_reservation','update')->access()):
		$update='<a onclick="editpayment(\''.$value->payid.'\',\''.$value->bookedid.'\',\''.$value->bookingnumber.'\',\''.$value->invoice.'\',\''.$value->paydate.'\',\''.$value->paymenttype.'\',\''.$value->paymentamount.'\')" class="btn btn-info btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Update" title="Update"><i class="ti-pencil-alt text-white" aria-hidden="true"></i></a>';
		endif;
		 if($this->permission->method('room_reservation','create')->access()):
		 $Payment='<a href="'.base_url().'room_reservation/payment-information/'.$value->bookedid.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Payment" title="Payment"><i class="ti-wallet"></i></a>';
		 endif;
		
		$row[] =$value->invoice;
		$row[] =$value->bookingnumber;
		$row[] =$value->paydate;
		$row[] =$value->payment_method;
		$row[] =$value->paymentamount;
		$row[] =$update;
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
  public function payments($id){
	   $data["bookinginfo"] = $this->roomreservation_model->findById($id);
	   $data["paymentmethod"] = $this->roomreservation_model->paymentlist();
	   $data["paymentlist"] = $this->roomreservation_model->findBypayId($id);
	   $payinfo=$this->db->select("*")->from('tbl_guestpayments')->order_by('payid','desc')->get()->row();
		if(!empty($payinfo)){
		$invoicenum=$payinfo->invoice;
		}
		else{
			$invoicenum = "000000"; 
			}
		$nextno=$invoicenum+1;
		$bk_length = strlen((int)$nextno); 
		$bkstr = '000000';
		$bknumber = substr($bkstr, $bk_length); 
		$data['invoice'] = $bknumber.$nextno;  
	    $data['title'] = "payments";
	    $data['module'] = "room_reservation";
	    $data['page']   = "payments";   
	    echo Modules::run('template/layout', $data); 
	  }
 public function addpayment($bid){
	 	$data['title'] = "Add Payment";
	  $this->form_validation->set_rules('booking_number',display('booking_number'),'required|xss_clean');
	  $this->form_validation->set_rules('invoice_no',display('invoice_no'),'required|xss_clean');
	  $this->form_validation->set_rules('pay_date',display('pay_date'),'required|xss_clean');
	  $this->form_validation->set_rules('payment_method',display('payment_method'),'required|xss_clean');
	  $this->form_validation->set_rules('amount',display('amount'),'required|xss_clean');
	  $saveid=$this->session->userdata('id');
	  $id= $this->input->post('payid', TRUE);
	  $data['intinfo']="";
	  // Find the acc COAID for the Transaction
	   $thisbookinfo = $this->db->select('cutomerid')->from('booked_info')->where('bookedid',$bid)->get()->row();
	   $customerid=$thisbookinfo->cutomerid;
	   $cusifo = $this->db->select('*')->from('customerinfo')->where('customerid',$customerid)->get()->row();
	   $headn = $cusifo->customernumber.'-'.$cusifo->firstname.' '.$cusifo->lastname;
	   $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName',$headn)->get()->row();
	   $customer_headcode = (!empty($coainfo->HeadCode)?$coainfo->HeadCode:null);
	   $invoice_no=$this->input->post('invoice_no',TRUE);
	   $newdate= date('Y-m-d');
	
	  if ($this->form_validation->run()) { 
	   if(empty($this->input->post('payid', TRUE))) {
		   $total_amount = $this->input->post('total_amount',TRUE);
			$paid_amount = $this->input->post('amount',TRUE);
			if($total_amount-$paid_amount>=0){
			$this->db->set('paid_amount', 'paid_amount+'.$paid_amount, FALSE);
			$this->db->where('bookedid', $bid);
			$this->db->update('booked_info');

			$data['room_reservation']   = (Object) $postData = array(
		   'payid'     	     		 => $this->input->post('payid', TRUE),
		   'bookingnumber' 	         => $this->input->post('booking_number',TRUE),
		   'invoice' 	             => $this->input->post('invoice_no',TRUE),
		   'paydate' 	             => $this->input->post('pay_date',TRUE),
		   'paymenttype' 	         => $this->input->post('payment_method',TRUE),
		   'paymentamount' 	         => $this->input->post('amount',TRUE),
		  );
		}else{
			$this->session->set_flashdata('exception',  display('pay_exact_amount'));
			redirect("room_reservation/payment-information/".$bid);
		}
		$this->permission->method('room_reservation','create')->redirect();
		if($this->roomreservation_model->createpayment($postData)) { 

				//Customer debit for Rent Value
				$invoice_no=$this->input->post('invoice_no',TRUE);
				$newdate= date('Y-m-d');
				 $cosdr = array(
				  'VNo'            =>  $invoice_no,
				  'Vtype'          =>  'CIV',
				  'VDate'          =>  $newdate,
				  'COAID'          =>  $customer_headcode,
				  'Narration'      =>  'Customer debit for Rent Invoice#'.$invoice_no,
				  'Debit'          =>  $this->input->post('amount',TRUE),
				  'Credit'         =>  0,
				  'StoreID'        =>  0,
				  'IsPosted'       => 1,
				  'CreateBy'       => $saveid,
				  'CreateDate'     => $newdate,
				  'IsAppove'       => 1
				); 
				 $this->db->insert('acc_transaction',$cosdr);
				 //Hotel Owner credit for Rent Value
				  $sc =array(
				  'VNo'            =>  $invoice_no,
				  'Vtype'          =>  'CIV',
				  'VDate'          =>  $newdate,
				  'COAID'          =>  10107,
				  'Narration'      =>  'Hotel Credit for Rent Invoice#'.$invoice_no,
				  'Debit'          =>  0,
				  'Credit'         =>  $this->input->post('amount',TRUE),
				  'StoreID'        =>  0,
				  'IsPosted'       => 1,
				  'CreateBy'       => $saveid,
				  'CreateDate'     => $newdate,
				  'IsAppove'       => 1
				);  
				 $this->db->insert('acc_transaction',$sc);
				 
				 // Customer Credit for paid amount.
				  $cc =array(
				  'VNo'            =>  $invoice_no,
				  'Vtype'          =>  'CIV',
				  'VDate'          =>  $newdate,
				  'COAID'          =>  $customer_headcode,
				  'Narration'      =>  'Customer Credit for Rent Invoice#'.$invoice_no,
				  'Debit'          =>  0,
				  'Credit'         =>  $this->input->post('amount',TRUE),
				  'StoreID'        =>  0,
				  'IsPosted'       => 1,
				  'CreateBy'       => $saveid,
				  'CreateDate'     => $newdate,
				  'IsAppove'       => 1
				);  
				 $this->db->insert('acc_transaction',$cc);
				
				 //Cash In hand Debit for paid value
				 $cdv = array(
				  'VNo'            =>  $invoice_no,
				  'Vtype'          =>  'CIV',
				  'VDate'          =>  $newdate,
				  'COAID'          =>  1020101,
				  'Narration'      =>  'Cash in hand Debit For Invoice#'.$invoice_no,
				  'Debit'          =>  $this->input->post('amount',TRUE),
				  'Credit'         =>  0,
				  'StoreID'        =>  0,
				  'IsPosted'       =>  1,
				  'CreateBy'       => $saveid,
				  'CreateDate'     => $newdate,
				  'IsAppove'       => 1
				); 
				 $this->db->insert('acc_transaction',$cdv);
		
		
		 $this->session->set_flashdata('message', display('save_successfully'));
		 redirect('room_reservation/payment-information/'.$bid);
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("room_reservation/payment-information/".$bid); 
	
	   } else {
		$this->permission->method('room_reservation','update')->redirect();
		$data['room_reservation']   = (Object) $postData = array(
		   'payid'     	     		 => $this->input->post('payid', TRUE),
		   'bookingnumber' 	         => $this->input->post('booking_number',TRUE),
		   'invoice' 	             => $this->input->post('invoice_no',TRUE),
		   'paydate' 	             => $this->input->post('pay_date',TRUE),
		   'paymenttype' 	         => $this->input->post('payment_method',TRUE),
		   'paymentamount' 	         => $this->input->post('amount',TRUE),
		  );
	 
		if ($this->roomreservation_model->updatepayment($postData)) { 
		$crtransac = $this->db->select('*')->from('acc_transaction')->where('COAID',$customer_headcode)->where('VNo',$invoice_no)->where('Credit>',0)->get()->row();
		$detransac = $this->db->select('*')->from('acc_transaction')->where('COAID',$customer_headcode)->where('VNo',$invoice_no)->where('Debit>',0)->get()->row();
		$storetransac = $this->db->select('*')->from('acc_transaction')->where('COAID','10107')->where('VNo',$invoice_no)->get()->row();
		$cashtransac = $this->db->select('*')->from('acc_transaction')->where('COAID','1020101')->where('VNo',$invoice_no)->get()->row();
		
		//Customer debit for Product Value
		$saveddate=date("Y-m-d");
				 $cosdr = array(
				  'Debit'          =>  $this->input->post('amount',TRUE),
				  'CreateBy'       => $saveid,
				  'UpdateDate'     => $saveddate,
				); 
				$this->db->where('ID',$detransac->ID);
			    $this->db->update('acc_transaction',$cosdr);
				 //Store credit for Product Value
				  $sc =array(
				  'Credit'         =>  $this->input->post('amount',TRUE),
				  'CreateBy'       => $saveid,
				  'UpdateDate'     => $saveddate,
				);  
				$this->db->where('ID',$storetransac->ID);
			    $this->db->update('acc_transaction',$sc);
				 
				 // Customer Credit for paid amount.
				  $cc =array(
				  'Credit'         =>  $this->input->post('amount',TRUE),
				  'CreateBy'       => $saveid,
				  'UpdateDate'     => $saveddate
				);  
				$this->db->where('ID',$crtransac->ID);
			    $this->db->update('acc_transaction',$cc);
				
				 //Cash In hand Debit for paid value
				 $cdv = array(
				  'Debit'          =>  $this->input->post('amount',TRUE),
				  'CreateBy'       => $saveid,
				  'UpdateDate'     => $saveddate
				); 
				 $this->db->where('ID',$cashtransac->ID);
			    $this->db->update('acc_transaction',$cdv);
		
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("room_reservation/payment-information/".$bid);  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('bed_edit');
		$data['intinfo']   = $this->roomreservation_model->findById($id);
	   }
	   
	   $data["bookinginfo"] = $this->roomreservation_model->findById($bid);
	   $data["paymentmethod"] = $this->roomreservation_model->paymentlist();
	   $payinfo=$this->db->select("*")->from('tbl_guestpayments')->order_by('payid','desc')->get()->row();
		if(!empty($payinfo)){
		$invoicenum=$payinfo->invoice;
		}
		else{
			$invoicenum = "000000"; 
			}
		$nextno=$invoicenum+1;
		$bk_length = strlen((int)$nextno); 
		$bkstr = '000000';
		$bknumber = substr($bkstr, $bk_length); 
		$data['invoice'] = $bknumber.$nextno;  
	    $data['module'] = "room_reservation";
	    $data['page']   = "payments";   
	    echo Modules::run('template/layout', $data); 
	   }   
	 }

 
}
