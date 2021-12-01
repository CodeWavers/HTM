<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MX_Controller {
    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'home_model'
        ));


    }


public function index(){
    date_default_timezone_set("Asia/Dhaka");

        $date_time=date('Y-m-d H:i:s');


        $booking_number=$this->db->select('booking_number')->from('booked_info')->where('payment_deadline <=',$date_time)->get()->result();


        //echo '<pre>';print_r($booking_number);exit();
            $this->db->set('bookingstatus',1);
            $this->db->where('payment_deadline <=',$date_time);
            $this->db->update('booked_info');

        foreach ($booking_number as $bn){
            $this->db->set('status',1);
            $this->db->where('booking_number',$bn->booking_number);
            $this->db->update('booked_room');

        }








}
	
}
