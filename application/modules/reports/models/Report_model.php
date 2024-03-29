<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class report_model extends CI_Model {
	
 
	public function getlist($status,$fromdate,$todate)
	{
		
		$betweenq="booked_info.checkindate >='".$fromdate."' AND booked_info.checkoutdate <='".$todate."'";
		$this->db->select('booked_info.*,roomdetails.roomtype');
        $this->db->from('booked_info');
		$this->db->join('roomdetails','roomdetails.roomid=booked_info.roomid','left');
		$this->db->where($betweenq);
		$this->db->where('booked_info.bookingstatus',$status);
        $this->db->order_by('booked_info.bookedid', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }

        return false;
	
	}
	public function getstocklist()
	{
		
		$this->db->select("products.product_name,unit_of_measurement.uom_name,unit_of_measurement.uom_short_code,purchase_details.*,SUM(purchase_details.quantity) as qty,SUM(product_out.out_qty) as out_qty,SUM(purchase_details.price) as sumprice");
		$this->db->from('purchase_details');
		$this->db->join('products','products.id = purchase_details.proid', 'left');
		$this->db->join('product_out','product_out.product_id = purchase_details.proid', 'left');
		$this->db->join('unit_of_measurement','unit_of_measurement.id = products.uom_id', 'Inner');
		$this->db->group_by('purchase_details.proid');
		$this->db->order_by('purchase_details.purchaseid','desc');
		$query = $this->db->get();
		return $query->result();
	
	}
	
	public function details($id)
	{

		$this->db->select('booked_info.*,booked_room.*,SUM(booked_room.total_room) as totalRoom,roomdetails.roomtype,roomdetails.rate');
        $this->db->from('booked_info');
		$this->db->join('booked_room','booked_room.booking_number=booked_info.booking_number','left');
		$this->db->join('roomdetails','roomdetails.roomid=booked_room.roomid','left');
		$this->db->where('booked_info.bookedid',$id);
		$query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();    
        }
        return false;
	
	}
	
	public function customerinfo($cid){
			$this->db->select('*');
			$this->db->from('customerinfo');
			$this->db->where('customerid',$cid);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row();    
			}
			return false;
		}
	public function storeinfo(){
			$this->db->select('*');
			$this->db->from('setting');
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row();    
			}
			return false;
		}
	public function commoninfo(){
			$this->db->select('*');
			$this->db->from('common_setting');
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row();    
			}
			return false;
		}
	public function paymentinfo($bookno){
			$this->db->select('tbl_guestpayments.*,payment_method.payment_method,booked_info.paid_amount');
			$this->db->from('tbl_guestpayments');
			$this->db->join('payment_method','payment_method.payment_method_id=tbl_guestpayments.paymenttype','left');
			$this->db->join('booked_info','booked_info.booking_number=tbl_guestpayments.bookingnumber','left');
			$this->db->where('tbl_guestpayments.bookingnumber',$bookno);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row();    
			}
			return false;
		}

		public function booked_service($bookno){
			$this->db->select('d.variation_name,a.rate,c.service_name');
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
		
	public function pruchasereport($start_date,$end_date)
	{
		$dateRange = "a.purchasedate BETWEEN '$start_date%' AND '$end_date%'";
		$this->db->select("a.*,b.supid,b.supName");
		$this->db->from('purchaseitem a');
		$this->db->join('supplier b','b.supid = a.suplierID');
		$this->db->where($dateRange, NULL, FALSE); 	
		$this->db->order_by('a.purchasedate','desc');
		$query = $this->db->get();
		return $query->result();
	} 
	public function settinginfo()
	{ 
		return $this->db->select("*")->from('setting')
			->get()
			->row();
	}
	public function currencysetting($id = null)
	{ 
		return $this->db->select("*")->from('currency')
			->where('currencyid',$id) 
			->get()
			->row();
	}
	
	public function read($limit = null, $start = null)
	{
	    $this->db->select('booked_info.*,booked_room.*,roomdetails.roomtype,customerinfo.*');
        $this->db->from('booked_info');
		$this->db->join('booked_room','booked_room.booking_number=booked_info.booking_number','left');
		$this->db->join('roomdetails','roomdetails.roomid=booked_room.roomid','left');
		$this->db->join('customerinfo','customerinfo.customerid=booked_info.cutomerid','left');
        $this->db->order_by('booked_info.bookedid', 'desc');
        $this->db->group_by('bookedid');
        $this->db->limit($limit, $start);
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
	

}
