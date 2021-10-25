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
        $service_id = $this->input->post('service', TRUE);
        $variation_id = $this->input->post('variation_id', TRUE);
        $rate = $this->input->post('rate', TRUE);

        $room_Array = explode(',',$room);

        foreach ($room_Array as $key => $value) {


            $data4['room'] = $value;


            if($status==2){
                $this->db->where('roomno', $data4['room']);
                $this->db->set(array('booking_number'=>$bookingnumber,'status'=>1));
                $this->db->update('tbl_floorplan');
            }else{
                $this->db->where('roomno', $data4['room']);
                $this->db->set(array('booking_number'=>'','status'=>0));
                $this->db->update('tbl_floorplan');

            }
             // echo '<pre>';print_r($data4);
            // $this->ProductModel->add_products($data);
           // if (!empty($data4)) {
              //  $this->db->insert('booked_services', $data_service);
           // }
        }

   //     echo '<pre>';print_r($myArray);exit();

        if (!empty($service_id) && !empty($rate)) {

            foreach ($service_id as $key => $value) {


                $data_service['service_no'] = $value;
                $data_service['booking_number'] = $bookingnumber;
                $data_service['variation_no'] = $variation_id[$key];
                $data_service['rate'] = $rate[$key];


                //  echo '<pre>';print_r($data);
                // $this->ProductModel->add_products($data);
                if (!empty($data_service)) {
                    $this->db->insert('booked_services', $data_service);
                }
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
        $this->db->where('a.status',1);
        //$this->db->group_by('a.id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result= $query->result();
        }
        $data = array();

        $sl =1;
        foreach ($result as $r){



            $room_no=$this->db->select('*')
                ->from('tbl_roomnofloorassign a')
                ->join('roomdetails b','a.roomid=b.roomid')
                ->join('booked_info c','a.roomno=c.room_no','left')
                ->join('customerinfo x','c.cutomerid=x.customerid','left')
                ->where('floorid',$r->floorid)
                ->group_by('a.roomno')
                ->order_by('a.roomno','asc')
                ->get()->result();

            $rooms='';



            foreach ($room_no as $ro){

                if ($ro->status == 1){
                    $rooms .='
                                 <div class="col-sm-4 room" data-toggle="popover-hover"   title="'.$ro->firstname.' '.$ro->lastname.'"  data-phone="'.$ro->cust_phone.'" data-email="'.$ro->email.'" >
                                     <div class="card card-stats statistic-box mb-4" style="background-color: #0073e6">
                                         <div
                                                 class="card-header card-header-danger card-header-icon text-center " style="background-color: #0d95e8">
                                             <div class="card-icon d-flex align-items-center justify-content-center">
                                                 <p class="card-category text-uppercase fs-20 font-weight-bold" style="color: whitesmoke">
                                                    '.$ro->roomno.' </p>
                                             </div>


                                         </div>
                                         <div class="card-footer p-3 " style="padding:auto;max-height: 84.24px">
                                             <div class="" >
                                                 <p class="card-category text-uppercase fs-12 font-weight-bold text-center" style="color: whitesmoke">
                                                    '.$ro->roomtype.'</p>
                                             </div>
                                         </div>
                                     </div>
                                 </div>




                        ';

                }
                else{

                    $rooms .= '
                                 <div class="col-sm-4 room" >
                                     <div class="card card-stats statistic-box mb-4" style="background-color: #ffffff">
                                         <div
                                                 class="card-header card-header-success card-header-icon text-center " style="background-color: #d0dce3">
                                             <div class="card-icon d-flex align-items-center justify-content-center">
                                                 <p class="card-category text-uppercase fs-20 font-weight-bold" style="color: whitesmoke">
                                                    ' .$ro->roomno.' </p>
                                             </div>


                                         </div>
                                         <div class="card-footer p-3 " style="padding:auto;max-height: 84.24px">
                                             <div class="" >
                                                 <p class="card-category text-uppercase fs-12 font-weight-bold text-center" style="color: black">
                                                    '.$ro->roomtype.'</p>
                                             </div>
                                         </div>
                                     </div>
                                 </div>




                        ';
                }




            }

            // echo '<pre>';print_r($booked_room);exit();
            $data[]=array(
                'sl'=>$sl,
                'floor_name'=>$r->floorname,
                'room_nos'=>$rooms,
                // 'booked_room'=>$booked_room,


            );

            $sl++;
        }

        return $data;
    }

    }
