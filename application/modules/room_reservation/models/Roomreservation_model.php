<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roomreservation_model extends CI_Model {
	
	private $table = 'booked_info';
 
	public function create($data = array())
	{
		return $this->db->insert($this->table, $data);
	}
	public function delete($id = null)
	{
		$this->db->where('bookedid',$id)
			->delete($this->table);

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 
	public function update($data = array(),$bookingnumber)
	{

      $service_id=  $this->input->post('service', TRUE);
      $rate=  $this->input->post('rate', TRUE);

        if ( ! empty($service_id) && ! empty($rate) )
        {

            foreach ($service_id as $key => $value )
            {


                $data_service['service_no'] = $value;
                $data_service['booking_number']=$bookingnumber;
                $data_service['rate']=$rate[$key];


                //  echo '<pre>';print_r($data);
                // $this->ProductModel->add_products($data);
                if ( ! empty($data_service))
                {
                    $this->db->insert('booked_services', $data_service);
                }
            }

        }


		return $this->db->where('bookedid',$data["bookedid"])
			->update($this->table, $data);
	}

    public function read($limit = null, $start = null)
	{
	    $this->db->select('booked_info.*,roomdetails.roomtype');
        $this->db->from($this->table);
		$this->db->join('roomdetails','roomdetails.roomid=booked_info.roomid','left');
        $this->db->order_by('booked_info.bookedid', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 
	public function headcode(){
        $query=$this->db->query("SELECT MAX(HeadCode) as HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '102030%'");
        return $query->row();
    }
	
	public function findById($id = null)
	{ 
		$this->db->select('*');
        $this->db->from($this->table);
		$this->db->where('bookedid',$id); 
        $this->db->order_by('bookedid', 'desc');
        $query = $this->db->get();
	    return $query->row();
	} 

 
public function countlist()
	{
		$this->db->select('booked_info.*,roomdetails.roomtype');
        $this->db->from($this->table);
		$this->db->join('roomdetails','roomdetails.roomid=booked_info.roomid','left');
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
			foreach($data as $value)
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
			foreach($data as $value)
				$list[$value->customerid] = $value->firstname.' '.$value->lastname;
			return $list;
		} else {
			return $list; 
		}
	}
public function allpayments(){
	return $data = $this->db->select("tbl_guestpayments.*,booked_info.bookedid")
			->from('tbl_guestpayments')
			->join('booked_info','booked_info.booking_number=tbl_guestpayments.bookingnumber','left')
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
			foreach($data as $value)
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
		return $this->db->where('payid',$data["payid"])
			->update('tbl_guestpayments', $data);
	}
public function findBypayId($id = null)
	{ 
		$this->db->select('*');
        $this->db->from('tbl_guestpayments');
		$this->db->where('bookingnumber',$id); 
        $this->db->order_by('payid', 'desc');
        $query = $this->db->get();
	    return $query->row();
	}
	public function chargeinfo(){
		$this->db->select('*');
		$this->db->from('setting');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();    
		}
		return false;
	} 
}
