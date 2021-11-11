<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roomreservation_model extends CI_Model
{

    private $table = 'booked_info';

    public function create($data = array())
    {
        return $this->db->insert($this->table, $data);
    }

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
//            $checked_room=array(
//                'booking_number'=>$bookingnumber,
//                'room_no'=>$value,
//                'status'=>4,
//
//            );

//            $chck_out_room=array(
//                'booking_number'=>$bookingnumber,
//                'room_no'=>$value,
//                'status'=>5,
//
//            );

            $exists=$this->db->select('*')->from('booked_room')->where(array('booking_number'=>$bookingnumber,'room_no'=>$value))->get()->num_rows();
            $rooms=$this->db->select('*')->from('booked_room')->where(array('booking_number'=>$bookingnumber,'room_no'=>$value))->get()->num_rows();

           // echo '<pre>';print_r($exists);exit();

            if($status==4){
                if ($exists == 0){
                    $this->db->insert('booked_room',$booked_room);
                }

            }

            if ($status==2){

                $this->db->set('status',2);
                $this->db->where(array('booking_number'=>$bookingnumber,'room_no'=>$value));
                $this->db->update('booked_room',$confirmed_room);


            }



           if ($status==3){
                $this->db->where('booking_number',$bookingnumber);
                $this->db->delete('booked_room');


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
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('bookedid', $id);
        $this->db->order_by('bookedid', 'desc');
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
                    ->order_by('a.roomno', 'asc')
                    ->group_by('a.roomno')
                    ->get()->result();


                // echo '<pre>';print_r($room_no);exit();
                $rooms = '';


                foreach ($room_no as $ro) {



                    if (($ro->st) && $ro->st == 4) {
                        // $rooms .=$title;
                        $rooms .='
                                <div class="col-sm-6 room" data-toggle="popover-hover"   title="' . $ro->firstname . ' ' . $ro->lastname . '"  data-phone="' . $ro->cust_phone . '" data-email="' . $ro->email . '" data-ci="' . $ro->checkindate . '" data-co="' . $ro->checkoutdate . '" >
                                    <a href="'.base_url().'room_reservation/booking-information/'.$ro->bookedid.'"> <div class="card mb-2" style="background-color: #0073e6">
                                         <div
                                                 class="card-header card-header-danger card-header-icon text-center " style="background-color: #0d95e8">
                                             <div class="card-icon d-flex align-items-center justify-content-center">
                                                 <p class="card-category text-uppercase fs-12 font-weight-bold" style="color: whitesmoke">
                                                    ' . $ro->rooms . ' </p>
                                             </div>


                                         </div>
                                         <div class="card-footer p-3 " style="padding:auto;max-height: 80px">
                                             <div class="" >
                                                 <p class="card-category fs-10 font-weight-bold text-center" style="color: whitesmoke">
                                                    ' . $ro->roomtype . '</p>
                                             </div>
                                         </div>
                                     </div></a>
                                 </div> 




                        ';

                    }

                    elseif (($ro->st) && $ro->st == 2) {
                        // $rooms .=$title;
                        $rooms .='
                                <div class="col-sm-6 room" data-toggle="popover-hover"   title="' . $ro->firstname . ' ' . $ro->lastname . '"  data-phone="' . $ro->cust_phone . '" data-email="' . $ro->email . '" data-ci="' . $ro->checkindate . '" data-co="' . $ro->checkoutdate . '" >
                                    <a href="'.base_url().'room_reservation/booking-information/'.$ro->bookedid. '"> <div class="card mb-2" style="background-color: #123d1f">
                                         <div
                                                 class="card-header card-header-info card-header-icon text-center " style="background-color: #05847e">
                                             <div class="card-icon d-flex align-items-center justify-content-center">
                                                 <p class="card-category text-uppercase fs-12 font-weight-bold" style="color: whitesmoke">
                                                    ' . $ro->rooms . ' </p>
                                             </div>


                                         </div>
                                         <div class="card-footer p-3 " style="padding:auto;max-height: 80px">
                                             <div class="" >
                                                 <p class="card-category fs-10 font-weight-bold text-center" style="color: whitesmoke">
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
                                     <div class="card mb-2" style="background-color: #ffffff">
                                         <div
                                                 class="card-header card-header-success card-header-icon text-center " style="background-color: #d0dce3">
                                             <div class="card-icon d-flex align-items-center justify-content-center">
                                                 <p class="card-category text-uppercase fs-12 font-weight-bold" style="color: whitesmoke">
                                                    ' . $ro->roomno . ' </p>
                                             </div>


                                         </div>
                                         <div class="card-footer p-3 " style="padding:auto;max-height: 80px">
                                             <div class="" >
                                                 <p class="card-category  fs-10 font-weight-bold text-center" style="color: black">
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
