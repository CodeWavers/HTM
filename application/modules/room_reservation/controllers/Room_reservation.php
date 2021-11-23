<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_reservation extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'roomreservation_model'

        ));

        //$this->load->model('home_model');
    }
    public function bookingdatatable(){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $columns = array(
            0 => 'booked_info.bookedid',
            1 => 'booking_number',
            2 => 'cutomerid',
            3 => 'cust_phone',
            4 => 'roomtype',
            5 => 'room_no',
            6 => 'checkindate',
            7 => 'checkoutdate',
            8 => 'date_time',
            9 => 'bookingstatus',
            10 => 'paid_amount',
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
        $sql = "SELECT booked_info.*,customerinfo.*,roomdetails.roomtype FROM booked_info Left Join roomdetails ON roomdetails.roomid=booked_info.roomid Left Join customerinfo ON customerinfo.customerid=booked_info.cutomerid";


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
                $invoice='<a href="'.base_url().'reports/booking-details/'.$value->bookedid.'" class="btn btn-warning btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Invoice" title="Invoice"><i class="ti-receipt"></i></a>';
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
            else if($value->bookingstatus==3){
                $status="Checked Out";
            }
            else if($value->bookingstatus==2){
                $status="Checked In";
            }
            else if($value->bookingstatus==4){
                $status="Confirmed";
            }
            if($value->paid_amount<$value->total_price){
                $paymentStatus="Pending";
            }
            else{
                $paymentStatus="Success";
            }
            $row[] =$i;
            $row[] =$value->booking_number;
            $row[] =$value->firstname.' '.$value->lastname;
            $row[] =$value->cust_phone;
            $row[] =$value->roomtype;
            $row[] =$value->room_no;
            $row[] =$value->checkindate;
            $row[] =$value->checkoutdate;
            $row[] =$value->date_time;
            $row[] =$status;
            $row[] =$paymentStatus;
            $row[] =$update.$invoice.$view.$Payment;
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


//    public function create($id = null)
//    {
//        $data['title'] = display('room_reservation');
//        $this->form_validation->set_rules('guest',display('guest'),'required|xss_clean');
//        $this->form_validation->set_rules('room_name',display('room_name'),'required|xss_clean');
//        $this->form_validation->set_rules('no_of_people',display('no_of_people'),'required|xss_clean');
//        $this->form_validation->set_rules('check_in',display('check_in'),'required|xss_clean');
//        $this->form_validation->set_rules('check_out',display('check_out'),'required|xss_clean');
//        $saveid=$this->session->userdata('id');
//        $this->input->post('discount',true);
//        $data['intinfo']="";
//        if ($this->form_validation->run()) {
//            if(empty($this->input->post('bookedid', TRUE))) {
//                $bookinginfo=$this->db->select("*")->from('booked_info')->order_by('bookedid','desc')->get()->row();
//                if(!empty($bookinginfo)){
//                    $bookno=$bookinginfo->bookedid;
//                }
//                else{
//                    $bookno = "00000000";
//                }
//
//                $nextno=$bookno+1;
//                $bk_length = strlen((int)$nextno);
//
//                $bkstr = '00000000';
//                $bknumber = substr($bkstr, $bk_length);
//                $bookingnumber = $bknumber.$nextno;
//                $length=count($this->input->post('slroomno',TRUE));
//
//                $room=$this->input->post('slroomno',TRUE);
//                $roomnosel='';
//                $custID=$this->input->post('guest', TRUE);
//                for($i=0;$i<$length;$i++){
//                    $roomnosel.=$room[$i].',';
//                }
//                $roomnosel=rtrim($roomnosel,',');
//
//                $room_id=$this->input->post('room_name',TRUE);
////                $length_room=count($this->input->post('room_name',TRUE));
////                $roomidsel='';
////                for($i=0;$i<$length_room;$i++){
////                    $roomidsel.=$room_id[$i].',';
////                }
////                $roomidsel=rtrim($roomidsel,',');
//
//               // echo '<pre>';print_r($roomidsel);exit();
//
//
//                $postData = array(
//                    'bookedid'     	     	 => $this->input->post('bookedid', TRUE),
//                    'booking_number' 	     => $bookingnumber,
//                    'date_time' 	             => date('Y-m-d H:i:s'),
//                    'roomid' 	             => $room_id,
//                    'nuofpeople'              => $this->input->post('no_of_people',TRUE),
//                    'total_room'              => $this->input->post('numofroom',TRUE),
//                    'room_no'              	 => $roomnosel,
//                    'roomrate'                => $this->input->post('roomrate',TRUE),
//                    'total_price'             => $this->input->post('gramount',TRUE),
//                    'offer_discount'          => $this->input->post('discount',TRUE),
//                    // 'offer_discount'          => '',
//                    'coments'                 => '',
//                    'checkindate'             => $this->input->post('check_in',TRUE),
//                    'checkoutdate'            => $this->input->post('check_out',TRUE),
//                    'cutomerid' 	             => $this->input->post('guest', TRUE),
//                    'bookingstatus' 	         =>  $this->input->post('booking_status', TRUE),
//
//                );
//
//             //   echo '<pre>';print_r($postData);exit();
//
//                $status=$this->input->post('booking_status', TRUE);
//                $this->permission->method('room_reservation','create')->redirect();
//
//
//                if($this->roomreservation_model->create($postData,$bookingnumber,$status)) {
//                    $type = "processing";
//                    // $response = $this->lsoft_setting->send_sms($bookingnumber, $custID, $type);
//                    // $data = json_decode($response);
//                    // $msg = $data->message;
//                    $this->session->set_flashdata('message', display('save_successfully'));
//                    //if($msg)
//                    // $this->session->set_userdata('msg', $msg);
//                    redirect('room_reservation/room-booking');
//                } else {
//                    $this->session->set_flashdata('exception',  display('please_try_again'));
//                }
//                redirect('room_reservation/room-booking');
//            } else {
//                $this->permission->method('room_reservation','update')->redirect();
//                $roomnosel=$this->input->post('room_no', TRUE);
//                $room_no=$this->input->post('room_no', TRUE);
//                $status = $this->input->post('status', TRUE);
//                $bookingnumber = $this->input->post('bookingnumber', TRUE);
//                $custID = $this->input->post('guest', TRUE);
//                if(empty($roomnosel)){
//                    $length=count($this->input->post('slroomno',TRUE));
//                    $room=$this->input->post('slroomno',TRUE);
//                    $roomnosel='';
//                    for($i=0;$i<$length;$i++){
//                        $roomnosel.=$room[$i].',';
//                    }
//                    $roomnosel=rtrim($roomnosel,',');
//                }
//                $service_tax=$this->db->select("*")->from('setting')->get()->result_array();
//
//                $check_in=date_create($this->input->post('check_out_old', TRUE));
//                $check_out=date_create($this->input->post('check_out', TRUE));
//                $check_out_new=$this->input->post('check_out', TRUE);
//                $check_out_old=$this->input->post('check_out_old', TRUE);
//                $room_rate=$this->input->post('room_rate', TRUE);
//
//                // $interval = $check_in->date_diff($check_out);
//                $interval=  date_diff($check_in,$check_out);
//
//                $total_room_rate=($interval->days)*$room_rate;
//
//                $service_charge=$service_tax[0]['servicecharge']*($total_room_rate/100);
//                $tax=$service_tax[0]['vat']*($total_room_rate/100);
//
//                $tt=$total_room_rate+$service_charge+$tax;
//
//                // echo '<pre>';print_r($tt);exit();
//
//                if ($check_out_new > $check_out_old){
//
//                    $total_price=$this->input->post('grand_total', TRUE)+$tt;
//                }else{
//
//                    $total_price= $this->input->post('grand_total', TRUE);
//                }
//
//                $data['room_reservation']   = (Object) $updateData = array(
//                    'room_no'              	 => $roomnosel,
//                    'bookedid'     	     	 => $this->input->post('bookedid', TRUE),
//                    'total_price' 	         => $total_price,
//                    'service_total' 	         => $this->input->post('service_total', TRUE),
//                    'checkoutdate' 	         => $this->input->post('check_out', TRUE),
//                    'bookingstatus' 	         => $this->input->post('status', TRUE)
//                );
//                if ($this->roomreservation_model->update($updateData,$bookingnumber,$status)) {
////		if($status==2){
////			$type = "completeorder";
////			$response = $this->lsoft_setting->send_sms($bookingnumber, $custID, $type);
////			$data = json_decode($response);
////			$msg = $data->message;
////
////		}
////		if($status==1){
////			$type = "cancel";
////			$response = $this->lsoft_setting->send_sms($bookingnumber, $custID, $type);
////			$data = json_decode($response);
////			$msg = $data->message;
////		}
//                    $this->session->set_flashdata('message', display('update_successfully'));
//                    //if($msg)
//                    //$this->session->set_userdata('msg', $msg);
//                } else {
//                    $this->session->set_flashdata('exception',  display('please_try_again'));
//                }
//                redirect("room_reservation/booking-list");
//            }
//        } else {
//            if(!empty($id)) {
//                $data['title'] = display('reservation_edit');
//                $data['intinfo']   = $this->roomreservation_model->findById($id);
//            }
//            $data["roomlist"] = $this->roomreservation_model->allrooms();
//            $data["customerlist"] = $this->roomreservation_model->customerlist();
//            $data['module'] = "room_reservation";
//            $data['page']   = "addbooking";
//            echo Modules::run('template/layout', $data);
//        }
//
//    }
    public function create($id = null)
    {
        $data['title'] = display('room_reservation');
        $this->form_validation->set_rules('guest',display('guest'),'required|xss_clean');
       // $this->form_validation->set_rules('room_name',display('room_name'),'required|xss_clean');
       // $this->form_validation->set_rules('no_of_people',display('no_of_people'),'required|xss_clean');
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

                $room_id=$this->input->post('room_name',TRUE);
//                $length_room=count($this->input->post('room_name',TRUE));
//                $roomidsel='';
//                for($i=0;$i<$length_room;$i++){
//                    $roomidsel.=$room_id[$i].',';
//                }
//                $roomidsel=rtrim($roomidsel,',');

               // echo '<pre>';print_r($roomidsel);exit();


                $postData = array(
                    'bookedid'     	     	 => $this->input->post('bookedid', TRUE),
                    'booking_number' 	     => $bookingnumber,
                    'date_time' 	             => date('Y-m-d H:i:s'),
                    'total_price'             => $this->input->post('gramount',TRUE),
                    'coments'                 => '',
                    'checkindate'             => $this->input->post('check_in',TRUE),
                    'checkoutdate'            => $this->input->post('check_out',TRUE),
                    'cutomerid' 	             => $this->input->post('guest', TRUE),
                    'bookingstatus' 	         =>  $this->input->post('booking_status', TRUE),

                );

             //   echo '<pre>';print_r($postData);exit();

                $status=$this->input->post('booking_status', TRUE);
                $this->permission->method('room_reservation','create')->redirect();


                if($this->roomreservation_model->create($postData,$bookingnumber,$status)) {
                    $type = "processing";
                    // $response = $this->lsoft_setting->send_sms($bookingnumber, $custID, $type);
                    // $data = json_decode($response);
                    // $msg = $data->message;
                    $this->session->set_flashdata('message', display('save_successfully'));
                    //if($msg)
                    // $this->session->set_userdata('msg', $msg);
                    redirect('room_reservation/room-booking');
                } else {
                    $this->session->set_flashdata('exception',  display('please_try_again'));
                }
                redirect('room_reservation/room-booking');
            } else {
                $this->permission->method('room_reservation','update')->redirect();
                $roomnosel=$this->input->post('room_no', TRUE);
                $room_no=$this->input->post('room_no', TRUE);
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
                $service_tax=$this->db->select("*")->from('setting')->get()->result_array();

                $check_in=date_create($this->input->post('check_out_old', TRUE));
                $check_out=date_create($this->input->post('check_out', TRUE));
                $check_out_new=$this->input->post('check_out', TRUE);
                $check_out_old=$this->input->post('check_out_old', TRUE);
                $room_rate=$this->input->post('room_rate', TRUE);

                // $interval = $check_in->date_diff($check_out);
                $interval=  date_diff($check_in,$check_out);

                $total_room_rate=($interval->days)*$room_rate;

                $service_charge=$service_tax[0]['servicecharge']*($total_room_rate/100);
                $tax=$service_tax[0]['vat']*($total_room_rate/100);

                $tt=$total_room_rate+$service_charge+$tax;

                // echo '<pre>';print_r($tt);exit();

                if ($check_out_new > $check_out_old){

                    $total_price=$this->input->post('grand_total', TRUE)+$tt;
                }else{

                    $total_price= $this->input->post('grand_total', TRUE);
                }

                $data['room_reservation']   = (Object) $updateData = array(
                    'room_no'              	 => $roomnosel,
                    'bookedid'     	     	 => $this->input->post('bookedid', TRUE),
                    'total_price' 	         => $total_price,
                    'service_total' 	         => $this->input->post('service_total', TRUE),
                    'checkoutdate' 	         => $this->input->post('check_out', TRUE),
                    'bookingstatus' 	         => $this->input->post('status', TRUE)
                );
                if ($this->roomreservation_model->update($updateData,$bookingnumber,$status)) {
//		if($status==2){
//			$type = "completeorder";
//			$response = $this->lsoft_setting->send_sms($bookingnumber, $custID, $type);
//			$data = json_decode($response);
//			$msg = $data->message;
//
//		}
//		if($status==1){
//			$type = "cancel";
//			$response = $this->lsoft_setting->send_sms($bookingnumber, $custID, $type);
//			$data = json_decode($response);
//			$msg = $data->message;
//		}
                    $this->session->set_flashdata('message', display('update_successfully'));
                    //if($msg)
                    //$this->session->set_userdata('msg', $msg);
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


        $booking_number=$data['intinfo']->booking_number;
        $roomname=$data['intinfo']->roomid;
        $checkin=$data['intinfo']->checkindate;
        $checkout=$data['intinfo']->checkoutdate;
        $status=1;
        $data['v_list']   = $this->roomreservation_model->booked_service($booking_number);
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
        $service_list=$this->db->select('*')->from('service_table')->get()->result();

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
        $data['service_list']   = $service_list;
        $data['service']   = $service_list;
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

        $sl=0;



        $dt=array();

         foreach ($roomname as $key => $value) {
             $exits = $this->db->select("*")->from('booked_info a')->join('booked_room b','a.booking_number=b.booking_number')
                 ->where('a.checkindate<=',$checkin)->where('a.checkoutdate>',$checkin)->where('a.bookingstatus!=',$status)->where('b.roomid',$value)->get()->result();

             $exit = $this->db->select("*")->from('booked_info a')->join('booked_room b','a.booking_number=b.booking_number')
                 ->where('a.checkindate<',$checkout)->where('a.checkoutdate>=',$checkout)->where('a.bookingstatus!=',$status)->where('b.roomid',$value)->get()->result();

             $check = $this->db->select("*")->from('booked_info a')->join('booked_room b','a.booking_number=b.booking_number')
                 ->where('a.checkindate>',$checkin)->where('a.checkoutdate<=',$checkout)->where('a.bookingstatus!=',$status)->where('b.roomid',$value)->get()->result();

             $totalroom1 = $this->db->select("SUM(b.total_room) as allroom")->from('booked_info a')->join('booked_room b','a.booking_number=b.booking_number')
                 ->where('a.checkindate<=',$checkin)->where('a.checkoutdate>',$checkin)->where('a.bookingstatus!=',$status)->where('b.roomid',$value)->get()->row();


             $totalroom2 = $this->db->select("SUM(b.total_room) as allroom")->from('booked_info a')->join('booked_room b','a.booking_number=b.booking_number')
                 ->where('a.checkindate<',$checkout)->where('a.checkoutdate>=',$checkout)->where('a.bookingstatus!=',$status)->where('b.roomid',$value)->get()->row();

             $totalroom3 = $this->db->select("SUM(b.total_room) as allroom")->from('booked_info a')->join('booked_room b','a.booking_number=b.booking_number')
                 ->where('a.checkindate>=',$checkin)->where('a.checkoutdate<=',$checkout)->where('a.bookingstatus!=',$status)->where('b.roomid',$value)->group_by('a.checkindate')->get()->result();
           //  echo '<pre>';print_r($totalroom3);exit();
             $allbokedroom3 = (!empty($allbokedroom3)?max(array_column($totalroom3, 'allroom')):0);

             $totalroomfound=$this->db->select("count(roomid) as totalroom")->from('tbl_roomnofloorassign')->where('roomid',$value)->get()->row();
             $roomdetails=$this->db->select("*")->from('roomdetails')->where('roomid',$value)->get()->row();
             $numberlist=$this->db->select("*")->from('tbl_roomnofloorassign')->where('roomid',$value)->get()->result();
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

             $sl++;
            $dt[]=array(

                'sl' =>$sl,
                'isfound' =>$data['isfound'],
                'freeroom' =>$data['freeroom'],
                'roomno' =>$value,
                'roominfo' =>$roomdetails,



            );
         }

       // echo '<pre>';print_r($dt);exit();





        $d['rooms']=$dt;
        $d['checkin']=$checkin;
        $d['checkout']=$checkout;
        $d['guest']=$guest;
        $d['roomno']=$roomname;
        $d['chargeinfo']=$this->roomreservation_model->chargeinfo();
        $d['module'] = "room_reservation";
        $d['page']   = "bookinginfo";

       // echo '<pre>';print_r($d);exit();

        $this->load->view('room_reservation/bookinginfo', $d);
    }
//    public function checkroom()
//    {
//        $guest = $this->input->post('guest', true);
//        $roomname = $this->input->post('room_name', true);
//
////        $room_array='';
////        foreach ($roomname as $key => $value) {
////            $room_array.=$value;
////        }
//        $status = 1;
//        $checkin = $this->input->post('check_in', true);
//        $checkout = $this->input->post('check_out', true);
//
//
//        $this->db->select('*');
//        $this->db->from('booked_info');
//        $this->db->where('checkindate<=',$checkin);
//        $this->db->where('checkoutdate>',$checkin);
//        $this->db->where('bookingstatus!=', $status);
//        $this->db->where('roomid',$roomname[0]);
//        foreach ($roomname as $key => $value) {
//            if($key > 0) {
//                $this->db->or_where('roomid',$value);
//                    }
//        }
//        $exits=$this->db->get()->result();
//
//      //  echo '<pre>';print_r($exits); exit();
//
//        $this->db->select('*');
//           $this->db ->from('booked_info');
//            $this->db->where('bookingstatus!=', $status);
//            $this->db->where('checkindate<', $checkout);
//            $this->db->where('checkoutdate>=', $checkout);
//
//           $this->db ->where('roomid',$roomname[0]);
//        foreach ($roomname as $key => $value) {
//            if($key > 0) {
//                $this->db->or_where('roomid',$value);
//}
//        }
//        $exit = $this->db->get()->result();
//        // echo '<pre>';print_r($exit); exit();
//        $this->db->select('*');
//           $this->db ->from('booked_info');
//            $this->db->where('bookingstatus!=', $status);
//        $this->db->where('checkindate>', $checkin);
//        $this->db->where('checkoutdate<=', $checkout);
//            $this->db->where('roomid',$roomname[0]);
//        foreach ($roomname as $key => $value) {
//            if($key > 0) {
//                $this->db->or_where('roomid',$value);
//}
//        }
//        $check =$this->db->get()->result();
//
//
//
//         $this->db->select("SUM(total_room) as allroom");
//            $this->db->from('booked_info')->where('checkindate<=', $checkin);
//            $this->db->where('checkoutdate>', $checkin)->where('bookingstatus!=', $status);
//        $this->db->where('roomid',$roomname[0]);
//        foreach ($roomname as $key => $value) {
//            if($key > 0) {
//                $this->db->or_where('roomid',$value);
//            }
//        }
//       // $totalroom1 = $this->db->get()->result();;
//        $totalroom1 = $this->db->get()->row();;
//
//       // echo '<pre>';print_r($totalroom1);exit();
//
//
//         $this->db->select("SUM(total_room) as allroom");
//           $this->db ->from('booked_info')->where('checkindate<', $checkout);
//           $this->db ->where('checkoutdate>=', $checkout);
//              $this->db   ->where('bookingstatus!=', $status);
//        $this->db->where('roomid',$roomname[0]);
//        foreach ($roomname as $key => $value) {
//            if($key > 0) {
//                $this->db->or_where('roomid',$value);
//            }
//        }
//            $totalroom2 =$this->db->get()->row();
//            //$totalroom2 =$this->db->get()->result();
//
//
//        $this->db->select("SUM(total_room) as allroom");
//        $this->db->from('booked_info');
//        $this->db->where('checkindate>=', $checkin);
//        $this->db->where('checkoutdate<=', $checkout);
//        $this->db->where('bookingstatus!=', $status);
//        $this->db->where('roomid',$roomname[0]);
//        foreach ($roomname as $key => $value) {
//
//            if($key > 0) {
//                $this->db->or_where('roomid',$value);
//            }
//        }
//        $this->db ->group_by('checkindate');
//        $totalroom3 =  $this->db ->get()->result();
//
////        echo '<pre>';print_r($key);exit();
//        $allbokedroom3 = (!empty($allbokedroom3) ? max(array_column($totalroom3, 'allroom')) : 0);
//
//       $this->db->select("count(roomid) as totalroom");
//        $this->db->from('tbl_roomnofloorassign');
//           $this->db->where('roomid',$roomname[0]);
//        foreach ($roomname as $key => $value) {
//            if($key > 0) {
//                $this->db->or_where('roomid',$value);
//            }
//        }
//        $totalroomfound = $this->db ->get()->row();
//
//       // echo '<pre>';print_r($totalroomfound);exit();
//        //$totalroomfound = $this->db ->get()->result();
//
//        $this->db->select("*");
//        $this->db->from('roomdetails');
//              $this->db->where('roomid',$roomname[0]);
//        foreach ($roomname as $key => $value) {
//            if($key > 0) {
//                $this->db->or_where('roomid',$value);
//            }
//        }
//        $roomdetails =$this->db ->get()->result();
//      //  echo '<pre>';print_r($roomdetails);exit();
//
//        $this->db->select("*");
//            $this->db->from('tbl_roomnofloorassign');
//            $this->db->join('roomdetails','roomdetails.roomid=tbl_roomnofloorassign.roomid');
//        $this->db->where('tbl_roomnofloorassign.roomid',$roomname[0]);
//        foreach ($roomname as $key => $value) {
//            if($key > 0) {
//                $this->db->or_where('tbl_roomnofloorassign.roomid',$value);
//            }
//        }
//        $numberlist=$this->db->get()->result();
//
//        $service_list = $this->db->select('*')->from('roomfacilitytype')->get()->result();
//        $roomlist = '';
//        foreach ($numberlist as $singleno) {
//            $roomlist .= $singleno->roomno. ',';
//          //  $roomlist .= $singleno->roomno.'('.$singleno->roomtype.')'. ',';
//
//        }
//      //  echo '<pre>';print_r($roomlist);exit();
//
//        $gtroomno = rtrim($roomlist, ',');
//        if (empty($exits) && empty($exit) && empty($check)) {
//            $data['freeroom'] = $gtroomno;
//            $data['isfound'] = 0;
//        } else {
//            $bookedroom = "";
//            if (!empty($exit)) {
//                foreach ($exits as $booked) {
//                    $bookedroom .= $booked->room_no . ',';
//                }
//            }
//            if (!empty($exit)) {
//                foreach ($exit as $ex) {
//                    $bookedroom .= $ex->room_no . ',';
//                }
//            }
//            if (!empty($check)) {
//                foreach ($check as $ch) {
//                    $bookedroom .= $ch->room_no . ',';
//                }
//            }
//
//            $getbookedall = rtrim($bookedroom, ',');
//            $allbokedroom1 = $totalroom1->allroom;
//            $allbokedroom2 = $totalroom2->allroom;
//            $allbokedroom = max((int)$allbokedroom1, (int)$allbokedroom2, (int)$allbokedroom3);
//            $allfreeroom = $totalroomfound->totalroom;
//            if ($allfreeroom > $allbokedroom) {
//                $output = $this->Differences($getbookedall, $gtroomno);
//                if (!empty($output)) {
//                    $data['freeroom'] = $output;
//                    $data['isfound'] = '1';
//                } else {
//                    $data['freeroom'] = '';
//                    $data['isfound'] = '2';
//                }
//            } else {
//                $data['freeroom'] = '';
//                $data['isfound'] = '2';
//            }
//        }
//        $data['checkin'] = $checkin;
//        $data['checkout'] = $checkout;
//        $data['guest'] = $guest;
//        $data['roomno'] = $roomname;
//
//        $data['roominfo'] = $roomdetails;
//      //  $data['roominfo2'] = $roomdetails;
//
//        $data['service_list'] = $service_list;
//        $data['chargeinfo'] = $this->roomreservation_model->chargeinfo();
//        $data['module'] = "room_reservation";
//        $data['page'] = "bookinginfo";
//
//
//      //  echo '<pre>';print_r($roomdetails);exit();
//        $this->load->view('room_reservation/bookinginfo', $data);
//
//
//    }

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

    public function change_booking_status(){


        $bookingnumber = $this->input->post('booking_number', true);
        $status = $this->input->post('booking_status', true);
        $room=$this->input->post('room_no', TRUE);

        $this->db->set('bookingstatus',$status);
        $this->db->where('booking_number',$bookingnumber);
        $this->db->update('booked_info');


        $room_Array = explode(',',$room);

        foreach ($room_Array as $key => $value) {


            $data4['room'] = $value;



            $booked_room=array(
                'booking_number'=>$bookingnumber,
                'room_no'=>$value,
                'status'=>2,

            );

//            $pending_room=array(
//                'booking_number'=>$bookingnumber,
//                'room_no'=>$value,
//                'status'=>2,
//
//            );

            $confirmed_room=array(
                'booking_number'=>$bookingnumber,
                'room_no'=>$value,
                'status'=>4,

            );
            $checked_room=array(
                'booking_number'=>$bookingnumber,
                'room_no'=>$value,
                'status'=>2,

            );



            $exists=$this->db->select('*')->from('booked_room')->where(array('booking_number'=>$bookingnumber,'room_no'=>$value))->get()->num_rows();
            $rooms=$this->db->select('*')->from('booked_room')->where(array('booking_number'=>$bookingnumber,'room_no'=>$value))->get()->num_rows();

            // echo '<pre>';print_r($exists);exit();

            if($status==4){

                    $this->db->where(array('booking_number'=>$bookingnumber,'room_no'=>$value));
                    $this->db->update('booked_room',$confirmed_room);


            }

            if ($status==2){


                $this->db->where(array('booking_number'=>$bookingnumber,'room_no'=>$value));
                $this->db->update('booked_room',$checked_room);


            }





            if ($status==3 || $status==1){
                $this->db->where('booking_number',$bookingnumber);
                $this->db->delete('booked_room');


            }

        }







        redirect("dashboard/home");



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
                $discount = $this->input->post('discount',TRUE);
                $paid_amount = $this->input->post('amount',TRUE);

                if($total_amount-$paid_amount>=0){
                    $this->db->set('paid_amount', 'paid_amount+'.$paid_amount, FALSE);
                    //$this->db->set('discount', $discount);
                    $this->db->set('total_price', $total_amount);
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
                        'IsPosted'       =>  1,
                        'CreateBy'       =>  $saveid,
                        'CreateDate'     =>  $newdate,
                        'IsAppove'       =>  1
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

    public function var_by_service() {


        $CI = & get_instance();
        $CI->load->model('roomreservation_model');
        $service_id = $this->input->post('service_id',TRUE);

        $variation = $CI->roomreservation_model->var_by_service_id($service_id);
        $var_selected = $this->input->post('var_selected',TRUE);


        // $var_list[]='';

        foreach ($variation as $v) {
            $var_list[]=array('var_name'=>$v->variation_name,'var_id'=>$v->id);

        }
        $var= "";

        //echo '<pre>';print_r($var_list);exit();
        if (empty($var_list)) {
            $var .="No Variation Found !";
        }else{
            $var .="<select name=\"variation_id[]\"  class=\" form-control\" id=\"\">";
            $var .= "<option value=''>Select Variation</option>";
            foreach ($var_list as $s) {

                $var .="<option  value=".$s['var_id'].">".$s['var_name']."</option>";
//                if(!empty($var_selected) && ($s['var_id'] == $var_selected)){
//                    $var .="<option selected value=".$s['var_id'].">".$s['var_name']."</option>";
//                }else{
//                    $var .="<option value=".$s['var_id'].">".$s['var_name']."</option>";
//                }

            }
            $var .="</select>";
        }

        // echo '<pre>';print_r($var);exit();

        $data['var_list']  =$var;
        // $data['service_id']  =$var_list['service_id'];



        //$data2['txnmber']        = $num_column;
        echo json_encode($data);

    }

    public function rate_by_service() {


        $CI = & get_instance();
        $CI->load->model('roomreservation_model');
        $var_id = $this->input->post('var_id',TRUE);

        $variation = $CI->roomreservation_model->rate_by_service_id($var_id);


        $data['rate']  =$variation->rate;
        // $data['service_id']  =$var_list['service_id'];



        //$data2['txnmber']        = $num_column;
        echo json_encode($data);



    }


    public function room_view(){


        $this->permission->method('room_reservation','read')->redirect();
        $floor_rooms=$this->roomreservation_model->floor_rooms();
        $data['title']    = display('room_view');
        $data['floor_rooms'] = $floor_rooms;
        #
        #pagination ends
        #
        $data['module'] = "room_reservation";
        $data['page']   = "room_view";
        echo Modules::run('template/layout', $data);



    }


}
