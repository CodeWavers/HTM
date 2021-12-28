<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roomreservation_model extends CI_Model
{

    private $table = 'booked_info';


    public function create($data = array(),$bookingnumber,$status)
    {


                $room=$this->input->post('slroomno',true);
                $total_room=$this->input->post('numofroom',true);
                $rate=$this->input->post('roomrate',true);
                $of_discount=$this->input->post('offer_discount',true);
              $checkin=$this->input->post('check_in',true);
                $checkout=$this->input->post('check_out',true);

    //    echo '<pre>';print_r(count($room));exit();


        for ($i = 0, $n = count($room); $i < $n; $i++) {

            $room_no = $room[$i];
         //   $t_room = $total_room[$i];
          //  $room_rate = $rate[$i];
            $offer_discount = $of_discount[$i];

             $room_id=$this->get_room_id_by_room_no($room_no);
             $room_rate=$this->get_room_rate_by_room_id($room_id);
             $offer_discount=$this->get_offer_rate_by_room_id($room_id,$room_rate,$checkin,$checkout);


             $old_room=$this->db->select('room_no')->from('booked_room')->where('room_no',$room_no)->get()->row();

            $this->db->set('is_old',1);
            $this->db->where(array('room_no'=>$old_room->room_no));
            $this->db->update('booked_room');


            $data1 = array(
                'booking_number'=>$bookingnumber,
                'roomid'=>$room_id,
                'room_no'=>$room_no,
                'total_room'=>1,
                'offer_discount'=>$offer_discount,
                'room_rate'=>$room_rate,
                'status'=>$status,

            );





                $this->db->insert('booked_room', $data1);


        }
      //  echo '<pre>';print_r($data1);exit();

        return $this->db->insert($this->table, $data);
    }


    public function get_room_id_by_room_no($room_no){

        $room_id=$this->db->select('roomid')->from('tbl_roomnofloorassign')->where('roomno',$room_no)->get()->row()->roomid;

        return $room_id;
    }

    public function get_room_rate_by_room_id($room_id){

        $room_rate=$this->db->select('rate')->from('roomdetails')->where('roomid',$room_id)->get()->row()->rate;

        return $room_rate;
    }
    public function get_room_rate_by_room_id_object($room_id){

        $room_rate=$this->db->select('rate')->from('roomdetails')->where('roomid',$room_id)->get()->result();

        return $room_rate;
    }

    public function get_offer_rate_by_room_id($room_id,$room_rate,$checkin,$checkout){

        $datetime1 = date_create($checkin);


        $datetime2 = date_create($checkout);
        $interval = date_diff($datetime1, $datetime2);
        $totalamount=$room_rate*$interval->format('%a');


        $firstdate = $checkin;
        $lastdate = $checkout;
        $datediff = strtotime($lastdate) - strtotime($firstdate);
        $datediff = floor($datediff/(60*60*24));
        $afterDiscount=0;
        $discount=0;
        for($i = 0; $i < $datediff; $i++){
            $alldays= date("Y-m-d", strtotime($firstdate . ' + ' . $i . 'day'));
            $getroom=$this->db->select("*")->from('tbl_room_offer')->where('roomid',$room_id)->where('offer_date',$alldays)->get()->row();


            if(!empty($getroom)){
                $singleDiscount=$room_rate-$getroom->offer;
                $afterDiscount=$afterDiscount+$singleDiscount;
                $discount+=$getroom->offer;

            }

        }

        return $discount;
    }



//    public function create($data = array(),$bookingnumber,$status)
//    {
//
//
//
//
//
//        $room=$this->input->post('slroomno', TRUE);
//
//
//
//       // $room_Array = explode(',',$room);
//        //echo print_r($room_Array);exit();
//        foreach ($room as $key => $value) {
//
//
//            $data4['room'] = $value;
//
//
//
//            $pending_room=array(
//                'booking_number'=>$bookingnumber,
//                'room_no'=>$value,
//                'status'=>0,
//
//            );
//
//          //  echo '<pre>';print_r($pending_room);exit();
//
//            $confirmed_room=array(
//                'booking_number'=>$bookingnumber,
//                'room_no'=>$value,
//                'status'=>4,
//
//            );
//            $checked_room=array(
//                'booking_number'=>$bookingnumber,
//                'room_no'=>$value,
//                'status'=>2,
//
//            );
//
//
//            $exists=$this->db->select('*')->from('booked_room')->where(array('booking_number'=>$bookingnumber,'room_no'=>$value))->get()->num_rows();
//           // $rooms=$this->db->select('*')->from('booked_room')->where(array('booking_number'=>$bookingnumber,'room_no'=>$value))->get()->num_rows();
//
//            // echo '<pre>';print_r($exists);exit();
//
//            if($status==4){
//                if ($exists == 0){
//                    $this->db->insert('booked_room',$confirmed_room);
//                }
//
//            }
//
//            if($status==2){
//                if ($exists == 0){
//                    $this->db->insert('booked_room',$checked_room);
//                }
//
//            }
//
//            if($status==0){
//                if ($exists == 0){
//                    $this->db->insert('booked_room',$pending_room);
//                }
//
//            }
//
//
//
//
//
//        }
//
//        return $this->db->insert($this->table, $data);
//    }

    public function delete($id = null)
    {
        $this->db->where('bookedid', $id)
            ->delete($this->table);

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data = array(), $bookingnumber,$status)
    {

        $room=$this->input->post('room_no', TRUE);
        //$service_id = $this->input->post('service', TRUE);
        $service_id = $this->input->post('service', TRUE);
        $variation_id = $this->input->post('variation_id', TRUE);
        $rate = $this->input->post('rate', TRUE);
        $change_room = $this->input->post('change_room', TRUE);
        $checkin = $this->input->post('check_in', TRUE);
        $checkout = $this->input->post('check_out', TRUE);
        if (!empty($change_room)){
            $this->db->where(array('booking_number'=>$bookingnumber));
            $this->db->delete('booked_room');

            for ($i = 0, $n = count($change_room); $i < $n; $i++) {

                $room_no = $change_room[$i];



                $room_id=$this->get_room_id_by_room_no($room_no);
                $room_rate=$this->get_room_rate_by_room_id($room_id);
                $offer_discount=$this->get_offer_rate_by_room_id($room_id,$room_rate,$checkin,$checkout);


                $old_room=$this->db->select('room_no')->from('booked_room')->where('room_no',$room_no)->get()->row();

                $this->db->set('is_old',1);
                $this->db->where(array('room_no'=>$old_room->room_no));
                $this->db->update('booked_room');


                $data1 = array(
                    'booking_number'=>$bookingnumber,
                    'roomid'=>$room_id,
                    'room_no'=>$room_no,
                    'total_room'=>1,
                    'offer_discount'=>$offer_discount,
                    'room_rate'=>$room_rate,
                    'status'=>$status,

                );





                $this->db->insert('booked_room', $data1);


            }
        }
        else{
            $room_Array = explode(',',$room);
            // echo '<pre>';print_r($room_Array);exit();
            foreach ($room_Array as $key => $value) {


                $data4['room'] = $value;



                $booked_room=array(
                    'booking_number'=>$bookingnumber,
                    'room_no'=>$value,
                    'status'=>2,

                );

                $pending_room=array(
                    'booking_number'=>$bookingnumber,
                    'room_no'=>$value,
                    'status'=>0,

                );

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


                // echo '<pre>';print_r($exists);exit();

                if($status==2){
                    $this->db->where(array('booking_number'=>$bookingnumber,'room_no'=>$value));
                    $this->db->update('booked_room',$checked_room);

                }

                if ($status==4){

                    $this->db->where(array('booking_number'=>$bookingnumber,'room_no'=>$value));
                    $this->db->update('booked_room',$confirmed_room);


                }

                if ($status==0){

                    $this->db->where(array('booking_number'=>$bookingnumber,'room_no'=>$value));
                    $this->db->update('booked_room',$pending_room);

                }

                if ( $status==1){
                    $this->db->set('status',1);
                    $this->db->where(array('booking_number'=>$bookingnumber,'room_no'=>$value));
                    $this->db->update('booked_room');


                }

                if ($status==3 ){
                    $this->db->set('status',3);
                    $this->db->where(array('booking_number'=>$bookingnumber,'room_no'=>$value));
                    $this->db->update('booked_room');


                }

            }
        }



            foreach ($service_id as $key => $value) {

                $data_service['service_no'] = $value;
                $data_service['booking_number'] = $bookingnumber;
                $data_service['variation_no'] = $variation_id[$key];
                $data_service['rate'] = $rate[$key];

               // echo '<pre>';print_r($data_service);exit();

                if (!empty($value) && !empty($rate[$key]) && !empty($variation_id[$key])) {
                    $this->db->insert('booked_services', $data_service);
                }
            }










        return $this->db->where('bookedid', $data["bookedid"])
            ->update($this->table, $data);
    }

    public function read($limit = null, $start = null)
    {
        $this->db->select('booked_info.*,roomdetails.roomtype');
        $this->db->from($this->table);
        $this->db->join('roomdetails', 'roomdetails.roomid=booked_info.roomid', 'left');
        $this->db->order_by('booked_info.bookedid', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function headcode()
    {
        $query = $this->db->query("SELECT MAX(HeadCode) as HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '102030%'");
        return $query->row();
    }

    public function findById($id = null)
    {
        $this->db->select('*,sum(b.total_room) as totalRoom');
        $this->db->from('booked_info a');
        $this->db->join('booked_room b','a.booking_number=b.booking_number');
        $this->db->where('a.bookedid', $id);
        $this->db->order_by('a.bookedid', 'desc');
        $query = $this->db->get();
        return $query->row();
    }


    public function countlist()
    {
        $this->db->select('booked_info.*,roomdetails.roomtype');
        $this->db->from($this->table);
        $this->db->join('roomdetails', 'roomdetails.roomid=booked_info.roomid', 'left');
        $this->db->order_by('booked_info.bookedid', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    public function allrooms()
    {
        $data = $this->db->select("*")
            ->from('roomdetails')
            ->get()
            ->result();

        $list[''] = 'Select Room';
        if (!empty($data)) {
            foreach ($data as $value)
                $list[$value->roomid] = $value->roomtype;
            return $list;
        } else {
            return false;
        }
    }

    public function customerlist()
    {
        $data = $this->db->select("*")
            ->from('customerinfo')
            ->get()
            ->result();

        $list[''] = 'Select Guest';

        if (!empty($data)) {
            foreach ($data as $value)
                $list[$value->customerid] = $value->firstname . ' ' . $value->lastname;
            return $list;
        } else {
            return $list;
        }
    }

    public function allpayments()
    {
        return $data = $this->db->select("tbl_guestpayments.*,booked_info.bookedid")
            ->from('tbl_guestpayments')
            ->join('booked_info', 'booked_info.booking_number=tbl_guestpayments.bookingnumber', 'left')
            ->get()
            ->result();
    }

    public function paymentlist()
    {
        $data = $this->db->select("*")
            ->from('payment_method')
            ->get()
            ->result();

        $list[''] = 'Select Payment';
        if (!empty($data)) {
            foreach ($data as $value)
                $list[$value->payment_method_id] = $value->payment_method;
            return $list;
        } else {
            return false;
        }
    }

    public function createpayment($data = array())
    {
        return $this->db->insert('tbl_guestpayments', $data);
    }

    public function updatepayment($data = array())
    {
        return $this->db->where('payid', $data["payid"])
            ->update('tbl_guestpayments', $data);
    }

    public function findBypayId($id = null)
    {
        $this->db->select('*');
        $this->db->from('tbl_guestpayments');
        $this->db->where('bookingnumber', $id);
        $this->db->order_by('payid', 'desc');
        $query = $this->db->get();
        return $query->row();
    }

    public function var_by_service_id($service_id)
    {
        $this->db->select('*');
        $this->db->from('service_variation');
        $this->db->where('service_id', $service_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function rate_by_service_id($var_id)
    {
        $this->db->select('rate');
        $this->db->from('service_variation');
        $this->db->where('id', $var_id);
        $query = $this->db->get();
//        if ($query->num_rows() > 0) {
//            return $query->result();
//        }
        return $query->row();
    }

    public function chargeinfo()
    {
        $this->db->select('*');
        $this->db->from('setting');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function booked_service($bookno){
        $this->db->select('d.variation_name,a.variation_no,a.rate,c.service_name,a.service_no');
        $this->db->from('booked_services a');
        $this->db->join('service_table c','a.service_no=c.service_id','left');
        $this->db->join('service_variation d','a.variation_no=d.id','left');
        $this->db->where('a.booking_number',$bookno);
        //$this->db->group_by('a.id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function room_type_by_booking_number($booking_number){

        $room_type=$this->db->select('*')
            ->from('booked_room a')
            ->join('roomdetails b','a.roomid=b.roomid','left')
            ->where('a.booking_number',$booking_number)
            ->group_by('a.roomid')
            ->get()->result();

        return $room_type;

    }

    public function room_no_by_booking_number($booking_number){

        $rooms=$this->db->select('*')
            ->from('booked_room a')
            ->where('a.booking_number',$booking_number)
            ->get()->result();

        return $rooms;

    }


    public function floor_rooms(){
        $this->db->select('*');
        $this->db->from('tbl_floor a');
        //    $this->db->where('a.status',1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result= $query->result();
        }
        $data = array();



        $sl =1;

        if (!empty($result)) {
            foreach ($result as $r) {


                $room_no = $this->db->select('a.roomno,z.roomtype,d.*,c.*,x.*,d.room_no as rooms,d.status as st')
                    ->from('tbl_floorplan a')
                    ->join('tbl_roomnofloorassign b', 'a.roomno=b.roomno', 'left')
                    ->join('roomdetails z', 'z.roomid=b.roomid', 'left')
                    ->join('booked_room d', 'a.roomno=d.room_no', 'left')
                    ->join('booked_info c', 'd.booking_number=c.booking_number', 'left')
                    ->join('customerinfo x', 'c.cutomerid=x.customerid', 'left')
                    ->where('a.floorName', $r->floorid)
                    ->order_by('a.floorplanid', 'asc')
                    ->group_by('a.roomno')
                    ->get()->result();


                //   echo '<pre>';print_r($room_no);exit();
                $rooms = '';


                foreach ($room_no as $ro) {



                    if ( $ro->st == '4') {
                        // $rooms .=$title;
//                        echo '<pre>';print_r($ro);exit();
                        $rooms .='
                                <div class="col-sm-6 room" data-toggle="popover-hover"   title="' . $ro->firstname . ' ' . $ro->lastname . '" data-bn="' . $ro->booking_number . '" data-phone="' . $ro->cust_phone . '" data-email="' . $ro->email . '" data-ci="' . $ro->checkindate . '" data-co="' . $ro->checkoutdate . '" >
                                    <a href="'.base_url().'room_reservation/booking-information/'.$ro->bookedid.'"> <div class="card mb-2" style="background-color: #0073e6;height: 110px">
                                         <div
                                                 class="card-header card-header-danger card-header-icon text-center " style="background-color: #0d95e8;">
                                             <div class="card-icon d-flex align-items-center justify-content-center">
                                                 <p class="card-category text-uppercase fs-12 font-weight-bold pa" style="color: whitesmoke">
                                                    ' . $ro->rooms . ' </p>
                                             </div>


                                         </div>
                                         <div class="" style="padding:auto;">
                                             <div class="" >
                                                 <p class="card-category fs-10 text-uppercase font-weight-bold text-center pt-3" style="color: whitesmoke">
                                                    ' . $ro->roomtype . '</p>
                                             </div>
                                         </div>
                                     </div></a>
                                 </div> 




                        ';

                    }

                    else if ($ro->st == '2') {
                        // $rooms .=$title;
                        $rooms .='
                                <div class="col-sm-6 room" data-toggle="popover-hover"   title="' . $ro->firstname . ' ' . $ro->lastname . '"  data-bn="' . $ro->booking_number . '" data-phone="' . $ro->cust_phone . '" data-email="' . $ro->email . '" data-ci="' . $ro->checkindate . '" data-co="' . $ro->checkoutdate . '" >
                                    <a href="'.base_url().'room_reservation/booking-information/'.$ro->bookedid. '"> <div class="card mb-2" style="background-color: #10b33f;height: 110px">
                                         <div
                                                 class="card-header card-header-info card-header-icon text-center " style="background-color: #05847e;">
                                             <div class="card-icon d-flex align-items-center justify-content-center">
                                                 <p class="card-category text-uppercase fs-12 font-weight-bold" style="color: whitesmoke">
                                                    ' . $ro->rooms . ' </p>
                                             </div>


                                         </div>
                                         <div class="" style="padding:auto;">
                                             <div class="" >
                                                 <p class="card-category fs-10 text-uppercase font-weight-bold text-center pt-3" style="color: whitesmoke">
                                                    ' . $ro->roomtype . '</p>
                                             </div>
                                         </div>
                                     </div></a>
                                 </div> 




                        ';

                    }
                    else if ($ro->st == '0') {
                        // $rooms .=$title;
                        $rooms .='
                                <div class="col-sm-6 room" data-toggle="popover-hover"   title="' . $ro->firstname . ' ' . $ro->lastname . '"  data-bn="' . $ro->booking_number . '" data-phone="' . $ro->cust_phone . '" data-email="' . $ro->email . '" data-ci="' . $ro->checkindate . '" data-co="' . $ro->checkoutdate . '" >
                                    <a href="'.base_url().'room_reservation/booking-information/'.$ro->bookedid. '">
                                     <div class="card mb-2" style="background-color: #c7222a;height: 110px">
                                         <div
                                                 class="card-header card-header-warning card-header-icon text-center " style="background-color: #690719">
                                             <div class="card-icon d-flex align-items-center justify-content-center">
                                                 <p class="card-category text-uppercase fs-12 font-weight-bold" style="color: whitesmoke">
                                                    ' . $ro->rooms . ' </p>
                                             </div>


                                         </div>
                                         <div class="" style="padding:auto;">
                                             <div class="" >
                                                 <p class="card-category fs-10 text-uppercase font-weight-bold text-center pt-3" style="color: whitesmoke">
                                                    ' . $ro->roomtype . '</p>
                                             </div>
                                         </div>
                                     </div></a>
                                 </div> 




                        ';

                    }



                    else {

                        $rooms .= '
                                 <div class="col-sm-6 room" >
                                     <div class="card mb-2" style="background-color: #ffffff;height: 110px">
                                         <div
                                                 class="card-header card-header-success card-header-icon text-center " style="background-color: #d0dce3">
                                             <div class="card-icon d-flex align-items-center justify-content-center">
                                                 <p class="card-category text-uppercase fs-12 font-weight-bold" style="color: whitesmoke">
                                                    ' . $ro->roomno . ' </p>
                                             </div>


                                         </div>
                                         <div class="" style="padding:auto;">
                                             <div class="" >
                                                 <p class="card-category text-uppercase fs-10 font-weight-bold text-center pt-3" style="color: black">
                                                    ' . $ro->roomtype . '</p>
                                             </div>
                                         </div>
                                     </div>
                                 </div>




                        ';
                    }


                }

                // echo '<pre>';print_r($booked_room);exit();
                $data[] = array(
                    'sl' => $sl,
                    'floor_name' => $r->floorname,
                    'room_nos' => $rooms,

                );

                $sl++;
            }
        }

        return $data;
    }

}
