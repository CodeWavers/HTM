<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hotel extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	  public $allmenu='';
	  public $webinfo='';
	  public $widgetinfo='';
	  public $settinginfo='';
	  public $storecurrency='';
	  public function __construct() {
        parent::__construct();
		$this->load->model(array(
			'hotel_model'
		));
        date_default_timezone_set('Asia/Dhaka');
		$this->allmenu= $this->hotel_model->allmenu_dropdown();
		$this->webinfo= $this->db->select('*')->from('common_setting')->get()->row();
		$this->settinginfo= $this->db->select('*')->from('setting')->get()->row(); 
		$this->storecurrency= $this->db->select('*')->from('currency')->where('currencyid',$this->settinginfo->currency)->get()->row();  
    }


	public function index()
	{
	   
	    $data['title']="Welcome to our Hotel";
		$curdate=date('Y-m-d');
		$month = date("Y-m-d", strtotime(" +12 months"));
		$where="offer_date Between '".$curdate."' AND '".$month."'";
		$data['slider_info']=  $this->hotel_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','1');
		$data['banner_homemiddle']=  $this->hotel_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','2');
		$data['banner_topweek']=  $this->hotel_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','3');
		$data['banner_destination']=  $this->hotel_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','4');
		$data['randoffer']=$this->db->select("*")->from('tbl_room_offer')->where($where)->order_by('offer_date','ASC')->get()->result();
		$data['content']=$this->load->view('home',$data,TRUE);
		$this->load->view('index',$data);
	}
	public function roomlist()
	{
	    $data['title']="All rooms";
		$checkinpost=$this->input->post('checkin',TRUE);
		$checkoutpost=$this->input->post('checkout',TRUE);
		$childrenpost=$this->input->post('children',TRUE); 
		$adultspost=$this->input->post('adults',TRUE);
		$sessiondata = array('checkin' =>$checkinpost,'checkout' =>$checkoutpost,'children'=>$childrenpost,'adults'=>$adultspost);
		$this->session->set_userdata($sessiondata);
		$checkin=$this->session->userdata('checkin');
		$checkout=$this->session->userdata('checkout');
		$children=$this->session->userdata('children');
		$adults=$this->session->userdata('adults');
		if($this->session->userdata('checkin')== FALSE){
			redirect('');
		}else{
		
		$status=1;
		$exits = $this->db->select("*")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where('bookingstatus!=',$status)->get()->result();
		$exit = $this->db->select("*")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where('bookingstatus!=',$status)->get()->result();
		$check = $this->db->select("*")->from('booked_info')->where('checkindate>',$checkin)->where('checkoutdate<=',$checkout)->where('bookingstatus!=',$status)->get()->result();
		$roomdetails=$this->db->select("roomdetails.*,room_image.room_imagename")->from('roomdetails')->join('room_image','room_image.room_id=roomdetails.roomid','left')->group_by("room_image.room_id")->get()->result();
		$numberlist=$this->db->select("*")->from('tbl_roomnofloorassign')->get()->result();
		$roomlist='';
		foreach($numberlist as $singleno){
			$roomlist.=$singleno->roomno.',';
			}
		
		$gtroomno=rtrim($roomlist,',');
		if(empty($exits)&&empty($exit)&&empty($check)){
			$data['roominfo']=$roomdetails;
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
			$bookedroom=rtrim($bookedroom,',');
			$condition="roomno NOT IN($bookedroom)";
			$allroom=$this->db->select("DISTINCT(roomid)")->from('tbl_roomnofloorassign')->where($condition)->get()->result();
			$sr='';
			foreach($allroom as $singleroom){
				$sr.="'".$singleroom->roomid."',";
				}
			$sr=rtrim($sr,',');
			$roomcondition="roomid IN($sr)";
			$roominfo=$this->db->select("roomdetails.*,room_image.room_imagename")->from('roomdetails')->join('room_image','room_image.room_id=roomdetails.roomid','left')->where($roomcondition)->group_by("room_image.room_id")->get()->result();
			$data['roominfo']=$roominfo;
		}
		$data['content']=$this->load->view('roomlist',$data,TRUE);
		$this->load->view('index',$data);
		}
	}
	
	public function roomdetails()
	{
		$data['title']="All rooms";

		$roomid2=$this->input->post('roomid',TRUE);
		$sessiondata = array('roomid' =>$roomid2);
		$this->session->set_userdata($sessiondata);
		$roomid=$this->session->userdata('roomid');
		$checkin=$this->session->userdata('checkin');
		$checkout=$this->session->userdata('checkout');
		$adults=$this->session->userdata('adults');
		$children=$this->session->userdata('children');
		if(empty($roomid2)){
			$roomid=$this->input->get('roomid',TRUE);
			$checkin=$this->input->get('checkin',TRUE);
			$checkout=$this->input->get('checkout',TRUE);
			$adults=$this->input->get('adults',TRUE);
			$children=$this->input->get('children',TRUE);
			$sessiondata = array('roomid' =>$roomid,'checkin' =>$checkin,'checkout' =>$checkout,'adults' =>$adults,'children' =>$children);
			$this->session->set_userdata($sessiondata);
		}
		if($this->session->userdata('checkin')== FALSE){
			redirect('');
			}
		else{
		$status=1;
		$exits = $this->db->select("*")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where('bookingstatus!=',$status)->where('roomid',$roomid)->get()->result();
		$exit = $this->db->select("*")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomid)->get()->result();
		$check = $this->db->select("*")->from('booked_info')->where('checkindate>',$checkin)->where('checkoutdate<=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomid)->get()->result();
		$totalroom1 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where('bookingstatus!=',$status)->where('roomid',$roomid)->get()->row();
		$totalroom2 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomid)->get()->row();
		$totalroom3 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate>=',$checkin)->where('checkoutdate<=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomid)->group_by('checkindate')->get()->result();
		$allbokedroom3 = (!empty($allbokedroom3)?max(array_column($totalroom3, 'allroom')):0);
		$totalroomfound=$this->db->select("count(roomid) as totalroom")->from('tbl_roomnofloorassign')->where('roomid',$roomid)->get()->row();
		$roomdetails=$this->db->select("roomdetails.*,room_image.room_imagename")->from('roomdetails')->join('room_image','room_image.room_id=roomdetails.roomid','left')->where('roomid',$roomid)->get()->row();
		$numberlist=$this->db->select("*")->from('tbl_roomnofloorassign')->where('roomid',$roomid)->get()->result();
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
		if($this->session->userdata('UserID')== FALSE){
			$data['userinfo']='';
			}
		else{
			$userinfo=$this->db->select("*")->from('customerinfo')->where('customerid',$this->session->userdata('UserID'))->get()->row();
			$data['userinfo']=$userinfo;
			}
		$data['roominfo']=$roomdetails;
		$data['condition']=  $this->hotel_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','10');
		$data['content']=$this->load->view('details',$data,TRUE);
		$this->load->view('index',$data);
		}
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
 public function bookedroom(){
	     $this->load->library('form_validation');
         $this->form_validation->set_rules('f_name',display('f_name'),'required|xss_clean|trim');
		 $this->form_validation->set_rules('l_name',display('l_name'),'required|xss_clean|trim');
		 $this->form_validation->set_rules('email',display('email'),'required|xss_clean|trim|valid_email');
		 $this->form_validation->set_rules('phone',display('phone'),'required|xss_clean|trim|is_natural');
		 $this->form_validation->set_rules('guest',display('guest'),'xss_clean|trim');
		 $this->form_validation->set_rules('password',display('password'),'xss_clean|trim');
		 $this->form_validation->set_rules('specialinstruction',display('specialinstruction'),'xss_clean|trim');
		
		if ($this->form_validation->run() === true){ 
		$this->cart->destroy();
		$t_room=$this->input->post('t_room',true);
		$sessiondata = array('t_room' =>$t_room);
		$this->session->set_userdata($sessiondata);
	 	$checkindate=$this->input->post('checkin',true);
		$checkoutdate=$this->input->post('checkout',true);
		$adult=$this->input->post('adult',true);
		$children=$this->input->post('children',true);
		$f_name=$this->input->post('f_name',true);
		$l_name=$this->input->post('l_name',true);
		$email=$this->input->post('email',true);
		$phone=$this->input->post('phone',true);
		$address=$this->input->post('address',true);
		$guestfullname=$this->input->post('guest',true);
		$specialinstruction=$this->input->post('specialinstruction',true);
		$roomid=$this->input->post('roomid',true);
		$roomtype=$this->input->post('roomtype',true);
		$roomrate=$this->input->post('roomrate',true);
		$amount=$this->input->post('amount',true);
		$discount=$this->input->post('discount',true);
		$isaccount=$this->input->post('isaccount',true);
		$password='';
		if(!empty($isaccount)){
			$password=$this->input->post('password',true);
			}
		$tax=$this->settinginfo->vat;
		$servicetharge=$this->settinginfo->servicecharge;

		$taxamount=($amount*$tax)/100;
		$serviceamnt=($amount*$servicetharge)/100;
		$grandtyotal=($amount+$taxamount+$serviceamnt);
		if($this->session->userdata('UserID')== FALSE){
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
		
		//insert customer
		$postData = array(
		   'firstname'     	    		=> $f_name,
		   'customernumber' 	        => $sino,
		   'lastname' 	        		=> $l_name,
		   'cust_phone' 	         	=> $phone,
		   'address' 	         		=> $address,
		   'email' 	             		=> $email,
		   'pass' 	             		=> md5($password),
		   'signupdate'					=> date('Y-m-d')
		  );
		$this->db->insert('customerinfo',$postData);
		$customerid = $this->db->insert_id();
		
		$coa = $this->hotel_model->headcode();
        if($coa->HeadCode!=NULL){
            $headcode=$coa->HeadCode+1;
        }
        else{
            $headcode="102030101";
        }
		//insert Coa for Customer Receivable
		$c_name = $f_name." ".$l_name;
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
		
	 	$sessiondata = array(
			'UserID' =>$customerid,
			'UserEmail' =>$email
			);
		$this->session->set_userdata($sessiondata);
		
		}
		
		$data_items = array(
				   'id'      	=> $roomid,
				   'name'       => $roomtype,
				   'qty'     	=> 1,
				   'roomrate'	=> $roomrate,
				   'price'   	=> $amount,
				   'totalprice' => $grandtyotal,
				   'checkin'    => $checkindate,
				   'checkout'   => $checkoutdate,
				   'adult'      => $adult,
				   'children'   => $children,
				   'tax'        => $tax,
				   'scharge'    => $servicetharge,
				   'discount'   => $discount,
				   'customerid' => $customerid,
				   'fullName' 	=>$guestfullname,
				   'special' 	=>$specialinstruction
				);
    			$this->cart->insert($data_items);
				$this->session->unset_userdata('checkin');
				$this->session->unset_userdata('checkout');
				$this->session->unset_userdata('children');
				$this->session->unset_userdata('adults');
				$this->session->unset_userdata('roomid');
				redirect("checkout");
             }
			 else{
					 $checkindate=$this->input->post('checkin',true);
					 $checkoutdate=$this->input->post('checkout',true);
					 $adult=$this->input->post('adult',true);
					 $roomid=$this->input->post('roomid',true);
					 $this->session->set_flashdata('exception',display('please_try_again'));
					 redirect('roomdetails');
				 }
	 }
	
 public function checkout(){
	 	$cart = $this->cart->contents();
		$userinfo=$this->db->select("*")->from('customerinfo')->where('customerid',$this->session->userdata('UserID'))->get()->row();
		$data['userinfo']=$userinfo;
		$data['paymentmethod']=$this->db->select("*")->from('payment_method')->get()->result();
	    $data['title']="Confirm your Booking";
		$data['content']=$this->load->view('checkout',$data,TRUE);
		$this->load->view('index',$data);
	 }
public function login(){
		$data['title']="Sign in to Book your Hotel";
		$data['content']=$this->load->view('signin',$data,TRUE);
		$this->load->view('index',$data);
	}
public function loginsubmit(){
		 $this->form_validation->set_rules('email',display('email'),'required');
	     $this->form_validation->set_rules('password',display('password'),'required');
		 if ($this->form_validation->run()) {
			
			 $email = $this->input->post('email',TRUE);
             $password = md5($this->input->post('password'));
			 $exist=$this->db->select("*")->from('customerinfo')->where('email',$email)->where('pass',$password)->get()->row();
			 if(!empty($exist)){
				 $sessiondata = array(
			'UserID' =>$exist->customerid,
			'UserName' =>$exist->firstname.' '.$exist->lastname,
			'UserEmail' =>$exist->email
			);
		         $this->session->set_userdata($sessiondata);
				 header("Location: ".$this->config->base_url());
				 }
			 else{
				  $this->session->set_flashdata('exception',display('invalid_credentials'));
				  $data['title']="Confirm your Booking";
				  $data['content']=$this->load->view('signin',$data,TRUE);
				  $this->load->view('index',$data);
				 }
			 }
		 else{
			    $data['title']="Confirm your Booking";
				$data['content']=$this->load->view('signin',$data,TRUE);
				$this->load->view('index',$data);
			 }
	}
public function paymentconfirm(){
		$payment_method=$this->input->post('pmethod',true);
		$check_gateway=$this->db->select('*')->from('payment_method')->where('payment_method_id',$payment_method)->get()->row();
		if($check_gateway->is_active!=0){
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
		$status=1;
		$cart = $this->cart->contents();
		foreach($cart as $item){
		$exits = $this->db->select("*")->from('booked_info')->where('checkindate<=',$item['checkin'])->where('checkoutdate>',$item['checkin'])->where('bookingstatus!=',$status)->where('roomid',$item['id'])->get()->result();
		$numberlist=$this->db->select("*")->from('tbl_roomnofloorassign')->where('roomid',$item['id'])->get()->result();
		$totalroom = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<=',$item['checkin'])->where('checkoutdate>',$item['checkin'])->where('bookingstatus!=',$status)->where('roomid',$item['id'])->get()->row();
		$totalroomfound=$this->db->select("count(roomid) as totalroom")->from('tbl_roomnofloorassign')->where('roomid',$item['id'])->get()->row();
		$roomlist='';
		foreach($numberlist as $singleno){
			$roomlist.=$singleno->roomno.',';
			}//endnumberlist
		$gtroomno=rtrim($roomlist,',');
		if(empty($exits)){
			
				$freeroom=$gtroomno;
			}
		else{
			$bookedroom="";
			foreach($exits as $booked){
				$bookedroom.=$booked->room_no.',';
			}
		$getbookedall=rtrim($bookedroom,',');
		$allbokedroom=$totalroom->allroom;
		$allfreeroom=$totalroomfound->totalroom;
				if($allfreeroom>$allbokedroom){
					$output=$this->Differences($getbookedall, $gtroomno);
					$freeroom=$output;
				}
				else{
					$freeroom='';
					}
			}
		$t_room = $this->session->userdata('t_room');
		$i=0;
		$freeroom=explode(',',$freeroom);
		if(count($freeroom)==0|count($freeroom)==''|count($freeroom)==null){
			redirect('fail/'.$bookingnumber);
		}
		while($t_room!=0){
			$t_freeroom[$t_room-1]=$freeroom[$i];
			$t_room--;
			$i++;
		}
		$totalFreeroom=implode(',',$t_freeroom);
		 $postData = array(
		   'booking_number' 	     => $bookingnumber,
		   'date_time' 	             => date('Y-m-d H:i:s'),
		   'roomid' 	             => $item['id'],
		   'nuofpeople'              => $item['adult'],
		   'children'              	 => $item['children'],
		   'total_room'              => $this->session->userdata('t_room'),
		   'room_no'              	 => $totalFreeroom,
		   'roomrate'                => $item['roomrate'],
		   'total_price'             => $item['totalprice'],
		   'offer_discount'          => $item['discount'],
		   'full_guest_name'         => $item['fullName'],
		   'special_request'         => $item['special'],
		   'checkindate'             => $item['checkin'],
		   'checkoutdate'            => $item['checkout'],
		   'cutomerid' 	             => $this->session->userdata('UserID'),
		   'bookingstatus' 	         => 0
		  );
			$this->db->insert('booked_info',$postData);
		     }//endcart
			 $this->cart->destroy();
		       if($payment_method==2){
					redirect('hotel/paymentgateway/'.$bookingnumber.'/'.$payment_method); 
				 }
				 else if($payment_method==3){
					redirect('hotel/paymentgateway/'.$bookingnumber.'/'.$payment_method); 
				 }
				 else if($payment_method==5){
					redirect('hotel/paymentgateway/'.$bookingnumber.'/'.$payment_method); 
				 }
				 else{
					redirect('hotel/successful/'.$bookingnumber.'/'.$payment_method);
					 }
		}
		else{
			$this->session->set_userdata('message', 'Sorry this payment gateway is currently inactive');
			redirect('orderdelevered/');
		}
	}
public function insert_payment($orderinfo,$pmethod){
	$paid_amount = $orderinfo->total_price;
	$booking_no =  $orderinfo->booking_number;
	$this->db->set('paid_amount', 'paid_amount+'.$paid_amount, FALSE);
	$this->db->where('booking_number', $booking_no);
	$this->db->update('booked_info');

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
	$invoice = $bknumber.$nextno; 
	
	$paydata = array(
   'bookingnumber' 	         => $booking_no,
   'invoice' 	             => $invoice,
   'paydate' 	             => date('Y-m-d'),
   'paymenttype' 	         => $pmethod,
   'paymentamount' 	         => $paid_amount,
  ); 
   $this->db->insert('tbl_guestpayments',$paydata);
   $saveid=$this->session->userdata('id');
   $customerid=$orderinfo->cutomerid;
   $cusifo = $this->db->select('*')->from('customerinfo')->where('customerid',$customerid)->get()->row();
   $headn = $cusifo->customernumber.'-'.$cusifo->firstname.' '.$cusifo->lastname;
   $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName',$headn)->get()->row();
   $customer_headcode = $coainfo->HeadCode;
  
  //Customer debit for Rent Value
		$newdate= date('Y-m-d');
		 $cosdr = array(
		  'VNo'            =>  $invoice,
		  'Vtype'          =>  'CIV',
		  'VDate'          =>  $newdate,
		  'COAID'          =>  $customer_headcode,
		  'Narration'      =>  'Customer debit for Rent Invoice#'.$invoice,
		  'Debit'          =>  $paid_amount,
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
		  'VNo'            =>  $invoice,
		  'Vtype'          =>  'CIV',
		  'VDate'          =>  $newdate,
		  'COAID'          =>  10107,
		  'Narration'      =>  'Hotel Credit for Rent Invoice#'.$invoice,
		  'Debit'          =>  0,
		  'Credit'         =>  $paid_amount,
		  'StoreID'        =>  0,
		  'IsPosted'       => 1,
		  'CreateBy'       => $saveid,
		  'CreateDate'     => $newdate,
		  'IsAppove'       => 1
		);  
		 $this->db->insert('acc_transaction',$sc);
		 
		 // Customer Credit for paid amount.
		  $cc =array(
		  'VNo'            =>  $invoice,
		  'Vtype'          =>  'CIV',
		  'VDate'          =>  $newdate,
		  'COAID'          =>  $customer_headcode,
		  'Narration'      =>  'Customer Credit for Rent Invoice#'.$invoice,
		  'Debit'          =>  0,
		  'Credit'         =>  $paid_amount,
		  'StoreID'        =>  0,
		  'IsPosted'       => 1,
		  'CreateBy'       => $saveid,
		  'CreateDate'     => $newdate,
		  'IsAppove'       => 1
		);  
		 $this->db->insert('acc_transaction',$cc);
		
		 //Cash In hand Debit for paid value
		 $cdv = array(
		  'VNo'            =>  $invoice,
		  'Vtype'          =>  'CIV',
		  'VDate'          =>  $newdate,
		  'COAID'          =>  1020101,
		  'Narration'      =>  'Cash in hand Debit For Invoice#'.$invoice,
		  'Debit'          =>  $paid_amount,
		  'Credit'         =>  0,
		  'StoreID'        =>  0,
		  'IsPosted'       =>  1,
		  'CreateBy'       => $saveid,
		  'CreateDate'     => $newdate,
		  'IsAppove'       => 1
		); 
		 $this->db->insert('acc_transaction',$cdv);
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
public function paymentgateway($orderid,$paymentid){
		  $data['title']="Payment information";
		  $data['orderinfo']  	       = $this->hotel_model->read('*', 'booked_info', array('booking_number' => $orderid));
		  $bookedInfo = $this->db->select('*')->from('booked_info')->where('booking_number',$orderid)->get()->row();
		  $data['paymentinfo']  	   = $this->hotel_model->read('*', 'paymentsetup', array('paymentid' => $paymentid));
		  $data['customerinfo']  	   = $this->hotel_model->read('*', 'customerinfo', array('customerid' => $bookedInfo->cutomerid));
		  $customer                    = $this->hotel_model->read('*', 'customerinfo', array('customerid' => $bookedInfo->cutomerid));
		 $commonsetting=$this->hotel_model->read('*', 'common_setting', array('id' => 1));
		  if($paymentid==5){
			$full_name = $customer->firstname.' '.$customer->lastname;
			$email = $customer->email;
			$phone = $customer->cust_phone;
			$amount =  $data['orderinfo']->total_price;
			$transactionid = $orderid;
			$address = $customer->address;
			
			$post_data = array();
			$post_data['store_id'] = SSLCZ_STORE_ID;
			$post_data['store_passwd'] = SSLCZ_STORE_PASSWD;
			$post_data['total_amount'] =  $data['orderinfo']->total_price;
			$post_data['currency'] =  $data['paymentinfo']->currency;
			$post_data['tran_id'] = $orderid;
			$post_data['success_url'] =  base_url()."hotel/successful/".$orderid."/".$paymentid;
			$post_data['fail_url'] = base_url()."hotel/fail/".$orderid;
			$post_data['cancel_url'] = base_url()."hotel/cancilorder/".$orderid;

			# CUSTOMER INFORMATION
			$post_data['cus_name'] = $customer->firstname.' '.$customer->lastname;
			$post_data['cus_email'] = $customer->email;
			$post_data['cus_add1'] = $customer->address;
			$post_data['cus_add2'] = "";
			$post_data['cus_city'] = "";
			$post_data['cus_state'] = "";
			$post_data['cus_postcode'] = "";
			$post_data['cus_country'] = "";
			$post_data['cus_phone'] = $customer->cust_phone;
			$post_data['cus_fax'] = "";

			# SHIPMENT INFORMATION
			$post_data['ship_name'] = "";
			$post_data['ship_add1 '] = "";
			$post_data['ship_add2'] = "";
			$post_data['ship_city'] = "";
			$post_data['ship_state'] = "";
			$post_data['ship_postcode'] = "";
			$post_data['ship_country'] = "";

			# OPTIONAL PARAMETERS
			$post_data['value_a'] = "";
			$post_data['value_b '] = "";
			$post_data['value_c'] = "";
			$post_data['value_d'] = "";

			$this->load->library('session');
			$session = array(
				'tran_id' => $post_data['tran_id'],
				'amount' => $post_data['total_amount'],
				'currency' => $post_data['currency']
			);
			$this->session->set_userdata('tarndata', $session);
			$this->load->library('sslcommerz');
			echo "<h3>Wait...SSLCOMMERZ Payment Processing....</h3>";
			if($this->sslcommerz->RequestToSSLC($post_data, false))
			{
				redirect('fail/'.$orderid);
			}
			
		  }
		 
		  else if($paymentid==3){
			$item_name = "Room Information";
            //Set variables for paypal form
            $returnURL = base_url()."hotel/successful/".$orderid."/".$paymentid; //payment success url
            $cancelURL = base_url()."hotel/cancilorder/".$orderid; //payment cancel url
            $notifyURL = base_url('hotel/ipn'); //ipn url
           
             // set form auto fill data
            $this->paypal_lib->add_field('return', $returnURL);
            $this->paypal_lib->add_field('cancel_return', $cancelURL);
            $this->paypal_lib->add_field('notify_url', $notifyURL);

            // item information
            $this->paypal_lib->add_field('item_number',  $orderid);
            $this->paypal_lib->add_field('item_name', $item_name);
            $this->paypal_lib->add_field('amount', $data['orderinfo']->total_price);  
            $this->paypal_lib->add_field('quantity', 1);    

            // additional information 
            $this->paypal_lib->add_field('custom', 'paynow');
            $this->paypal_lib->image(base_url($commonsetting->logo));
            // generates auto form
            $this->paypal_lib->paypal_auto_form();
			  }
		}
	public function ipn()
			{
				//paypal return transaction details array
				$paypalInfo    = $this->input->post(); 
				$data['user_id']        = $paypalInfo['custom'];
				$data['product_id']     = $paypalInfo["item_number"];
				$data['txn_id']         = $paypalInfo["txn_id"];
				$data['payment_gross']  = $paypalInfo["mc_gross"];
				$data['currency_code']  = $paypalInfo["mc_currency"];
				$data['payer_email']    = $paypalInfo["payer_email"];
				$data['payment_status'] = $paypalInfo["payment_status"];
		
				$paypalURL = $this->paypal_lib->paypal_url;     
				$result    = $this->paypal_lib->curlPost($paypalURL,$paypalInfo);
			}
	public function successful($orderid,$paymentid){
		   $orderinfo  	       = $this->hotel_model->read('*', 'booked_info', array('booking_number' => $orderid));
		   $customerid 	   = $orderinfo->cutomerid;
		   $pmethod = $paymentid;
		   if($pmethod!=1 && $pmethod!=4)
		   $this->insert_payment($orderinfo,$pmethod);
           $type = "processing";
           $this->lsoft_setting->send_sms($orderid, $customerid, $type);
		   $this->session->set_userdata('message', display('order_successfully'));
			redirect('hotel/orderdelevered/');	
		}	
	public function fail($orderid){
		$this->session->set_userdata('message', display('order_fail'));
		redirect('hotel/orderdelevered/');		
		  
		}	
	public function cancilorder($orderid){
		  $this->session->set_userdata('message', display('order_fail'));
		  redirect('hotel/orderdelevered/');
		}
	public function orderdelevered(){
		redirect('');
		$data['title']="Booking Complete";
		$data['content']=$this->load->view('complete',$data,TRUE);
		$this->load->view('index',$data);
		}
	public function register(){
			$data['title']="Register";
			$data['content']=$this->load->view('register',$data,TRUE);
			$this->load->view('index',$data);
		}
	public function signup(){
			 $this->form_validation->set_rules('f_name',display('f_name'),'required|xss_clean|trim');
			 $this->form_validation->set_rules('l_name',display('l_name'),'required|xss_clean|trim');
			 $this->form_validation->set_rules('emial',display('emial'),'required|xss_clean|trim|valid_email');
	         $this->form_validation->set_rules('password',display('password'),'required|xss_clean|trim');
		     if ($this->form_validation->run()) {
			
			 $f_name = $this->input->post('f_name',TRUE);
			 $l_name = $this->input->post('l_name',TRUE);
			 $phone = $this->input->post('phone',TRUE);
			 $emial = $this->input->post('emial',TRUE);
             $password = md5($this->input->post('password'));
			 
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
		
		//insert customer
		$postData = array(
		   'firstname'     	    		=> $f_name,
		   'customernumber' 	        => $sino,
		   'lastname' 	        		=> $l_name,
		   'cust_phone' 	        			=> $phone,
		   'email' 	             		=> $this->input->post('emial',TRUE),
		   'pass' 	             		=> $password,
		   'signupdate'					=> date('Y-m-d')
		  );
		$this->db->insert('customerinfo',$postData);
		$customerid = $this->db->insert_id();
		
		$coa = $this->hotel_model->headcode();
        if($coa->HeadCode!=NULL){
            $headcode=$coa->HeadCode+1;
        }
        else{
            $headcode="102030101";
        }
		//insert Coa for Customer Receivable
		$c_name = $f_name." ".$l_name;
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
		
	 	$sessiondata = array(
			'UserID' =>$customerid,
			'UserName' =>$guestfullname,
			'UserEmail' =>$email
			);
		$this->session->set_userdata($sessiondata);
		header("Location: ".$this->config->base_url());	
			 }
		 else{
			   $data['title']="Register";
			   $data['content']=$this->load->view('register',$data,TRUE);
			    $this->load->view('index',$data);
			 }
		}
public function logout(){
	    $this->session->unset_userdata('UserID');
		$this->session->unset_userdata('UserName');
		$this->session->unset_userdata('UserEmail');
		header("Location: ".$this->config->base_url());
	}
public function about()
	{
	    $data['title']="About Our Hotel";
		$data['team_info']=  $this->hotel_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','5');
		$data['company']=  $this->hotel_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','9');
		$data['about_smallbig']=  $this->hotel_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','6');
		$data['content']=$this->load->view('about',$data,TRUE);
		$this->load->view('index',$data);
	}
public function contact()
	{
	    $data['title']="Contact Us";
		$data['team_info']=  $this->hotel_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','5');
		$data['about_smallbig']=  $this->hotel_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','6');
		$data['content']=$this->load->view('contact',$data,TRUE);
		$this->load->view('index',$data);
	}
public function sendemail(){
		
$send_email = $this->hotel_model->read('*', 'email_config', array('email_config_id' => 1));	
$fullname=$this->input->post('firstname',TRUE);
$email=$this->input->post('email',TRUE);
$text=$this->input->post('comments',TRUE);
$phone=$this->input->post('phone',TRUE);
$subject="Contact Inquery";
$emailtext='<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Hi '.$fullname.',</p>
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Phone:'.$phone.'</p>
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">'.$text.'</p>';
$config = array(
	'protocol'  => $send_email->protocol,
	'smtp_host' => $send_email->smtp_host,
	'smtp_port' => $send_email->smtp_port,
	'smtp_user' => $send_email->sender,
	'smtp_pass' => $send_email->smtp_password,
	'mailtype'  => $send_email->mailtype,
	'charset'   => 'utf-8'
		);


$this->load->library('email');
$this->email->initialize($config);
$this->email->set_newline("\r\n");
$this->email->set_mailtype("html");
$this->email->from($email, 'Contact Info');
$this->email->to($send_email->sender);
$this->email->subject($subject);
$this->email->message($emailtext);
$this->email->send();
$this->session->set_flashdata('message', display('contact_send'));
redirect('contact/');
}
public function subscribe(){
$fromemail=$this->input->post('email',TRUE);
$subject="Customer Subscription";
$exitsemail=$this->hotel_model->read('*', 'subscribe_emaillist', array('email' => $fromemail));
if(empty($exitsemail)){
$send_email = $this->hotel_model->read('*', 'email_config', array('email_config_id' => 1));	
$config = array(
		'protocol'  => $send_email->protocol,
		'smtp_host' => $send_email->smtp_host,
		'smtp_port' => $send_email->smtp_port,
		'smtp_user' => $send_email->sender,
		'smtp_pass' => $send_email->smtp_password,
		'mailtype'  => $send_email->mailtype,
		'charset'   => 'utf-8'
	);

	$this->load->library('email');
	$this->email->initialize($config);
	$this->email->set_newline("\r\n");
	$this->email->set_mailtype("html");
	$htmlContent="Thanks for your Subscription";;
	$this->email->from($send_email->sender, 'Hungry Eat');
	$this->email->to($fromemail);
	$this->email->subject($subject);
	$this->email->message($htmlContent);
	$this->email->send();
	$subs['email'] = $fromemail;
	$subs['dateinsert'] = date('Y-m-d H:i:s');
	$this->hotel_model->insert_data('subscribe_emaillist', $subs);
	}
							
	}
	public function gallery()
	{
	    $data['title']="Our Gallery";
		$data['gallery_type']=$this->db->select("DISTINCT(title)")->from('tbl_slider')->where('Sltypeid',8)->get()->result();
		$data['galleries']=  $this->hotel_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','8');
		$data['content']=$this->load->view('gallery',$data,TRUE);
		$this->load->view('index',$data);
	}
	public function privacy()
	{
	    $data['title']="Privacy Policy";
		$data['content']=$this->load->view('privacy',$data,TRUE);
		$this->load->view('index',$data);
	}
	public function terms()
	{
	    $data['title']="Our Terms & Condition";
		$data['content']=$this->load->view('terms',$data,TRUE);
		$this->load->view('index',$data);
	}	
}