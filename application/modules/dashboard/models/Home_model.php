<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {


	public function checkUser($data = array())
	{
		return $this->db->select("
				user.id, 
				CONCAT_WS(' ', user.firstname, user.lastname) AS fullname,
				user.email, 
				user.image, 
				user.last_login,
				user.last_logout, 
				user.ip_address, 
				user.status, 
				user.is_admin, 
				IF (user.is_admin=1, 'Admin', 'User') as user_level
			")
			->from('user')
			->where('email', $data['email'])
			->where('password', md5($data['password']))
			->get();
	}

	public function userPermission($id = null)
	{
		return $this->db->select("
			module.controller, 
			module_permission.fk_module_id, 
			module_permission.create, 
			module_permission.read, 
			module_permission.update, 
			module_permission.delete
			")
			->from('module_permission')
			->join('module', 'module.id = module_permission.fk_module_id', 'full')
			->where('module_permission.fk_user_id', $id)
			->get()
			->result();
	}


	public function last_login($id = null)
	{
		return $this->db->set('last_login', date('Y-m-d H:i:s'))
			->set('ip_address', $this->input->ip_address())
			->where('id',$this->session->userdata('id'))
			->update('user');
	}

	public function last_logout($id = null)
	{
		return $this->db->set('last_logout', date('Y-m-d H:i:s'))
			->where('id', $this->session->userdata('id'))
			->update('user');
	}

	public function profile($id = null)
	{
		return $this->db->select("
			*, 
				CONCAT_WS(' ', firstname, lastname) AS fullname,
				IF (user.is_admin=1, 'Admin', 'User') as user_level
			")
			->from("user")
			->where("id", $id)
			->get()
			->row();
	}

	public function setting($data = array())
	{
		return $this->db->where('id', $data['id'])
			->update('user', $data);
	}
	
	public function countorder()
	{
		$this->db->select('*');
        $this->db->from('booked_info');
		 $this->db->where('bookingstatus=', 2);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return 0;
	}
	public function countpending()
	{
		$this->db->select('*');
        $this->db->from('booked_info');
		 $this->db->where('bookingstatus=', 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return 0;
	}
	public function countcancel()
	{
		$this->db->select('*');
        $this->db->from('booked_info');
		 $this->db->where('bookingstatus=', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return 0;
	}
	public function countcompleteorder()
	{
		$this->db->select('*');
        $this->db->from('customer_order');
		 $this->db->where('order_status', 4);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return 0;
	}
	
	public function todayorder()
	{
		$today=date('Y-m-d');
		$this->db->select('*');
        $this->db->from('booked_info');
		$this->db->where('date(date_time)', $today);
		$this->db->where('bookingstatus!=', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return 0;
	}
	
	public function totalcustomer()
	{
		$this->db->select('*');
        $this->db->from('customerinfo');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return 0;
	}
	public function customerlist()
	{
		$this->db->select('*');
        $this->db->from('customerinfo');
		$this->db->order_by('customerid', 'DESC');
		$this->db->limit(50);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();  
        }
        return false;
	}
	
	public function totalamount()
	{
		$today=date('Y-m-d');
		$this->db->select('SUM(total_price) as amount');
        $this->db->from('booked_info');
		$this->db->where('bookingstatus!=', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();  
        }
        return 0;
	}
	public function totalreservation()
	{
		$this->db->select('*');
        $this->db->from('tblreservation');
		$this->db->where('status', '2');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return 0;
	}
	public function todayorderlist()
	{
		$today=date('Y-m-d');
		$this->db->select('booked_info.*,customerinfo.cust_phone,customerinfo.firstname,customerinfo.lastname');
        $this->db->from('booked_info');
		$this->db->join('customerinfo','booked_info.cutomerid=customerinfo.customerid','left');
		$this->db->where('booked_info.checkindate', $today);
		$this->db->where('booked_info.bookingstatus!=', 1);
		$this->db->order_by('booked_info.bookedid', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();  
        }
        return false;
	}
	public function nextdayorderlist()
	{
	    $nextDate = date("Y-m-d", strtotime("+ 1 day"));;
		$this->db->select('booked_info.*,customerinfo.cust_phone,customerinfo.firstname,customerinfo.lastname');
        $this->db->from('booked_info');
		$this->db->join('customerinfo','booked_info.cutomerid=customerinfo.customerid','left');
		$this->db->where('booked_info.checkindate', $nextDate);
		$this->db->where('booked_info.bookingstatus!=', 1);
		$this->db->order_by('booked_info.bookedid', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();  
        }
        return false;
	}
	public function latestoredercount()
	{
		$this->db->select('*');
        $this->db->from('booked_info');
		$this->db->where('isSeen', 0);
		$this->db->or_where('isSeen',NULL);
		$this->db->order_by('booking_number', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows;  
        }
        return 0;
	}
	public function latestonline()
	{
		$this->db->select('customer_order.*,customer_info.customer_name,customer_info.customer_phone,rest_table.tablename');
        $this->db->from('customer_order');
		$this->db->join('customer_info','customer_order.customer_id=customer_info.customer_id','left');
		$this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
		$this->db->where('order_status!=', 5);
		$this->db->where('cutomertype', 2);
		$this->db->order_by('saleinvoice', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();  
        }
        return false;
	}
	public function latestreservation()
	{
		$this->db->select('tblreservation.*,customer_info.customer_name,customer_info.customer_phone,rest_table.tablename');
        $this->db->from('tblreservation');
		$this->db->join('customer_info','tblreservation.cid=customer_info.customer_id','left');
		$this->db->join('rest_table','tblreservation.tableid=rest_table.tableid','left');
		$this->db->where('tblreservation.status', 2);
		$this->db->order_by('tblreservation.reserveday', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();  
        }
        return false;
	}
	public function latestpending()
	{
		$this->db->select('customer_order.*,customer_info.customer_name,customer_info.customer_phone,rest_table.tablename');
        $this->db->from('customer_order');
		$this->db->join('customer_info','customer_order.customer_id=customer_info.customer_id','left');
		$this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
		$this->db->where('order_status', 1);
		$this->db->order_by('saleinvoice', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();  
        }
        return false;
	}
	
	public function monthlybookingamount($year,$month)
		{
			$groupby="GROUP BY YEAR(date_time), MONTH(date_time)";
			$amount='';
			$wherequery = "YEAR(date_time)='$year' AND month(date_time)='$month' AND total_price=paid_amount GROUP BY YEAR(date_time), MONTH(date_time)";
			$this->db->select('SUM(total_price) as amount');
			$this->db->from('booked_info');
			$this->db->where($wherequery, NULL, FALSE);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$result=$query->result(); 
				foreach($result as $row){
					$amount.=$row->amount.", ";
					}
				return trim($amount,', ');
			}
			return 0;
		}
	public function monthlybookingorder($year,$month)
		{
			$groupby="GROUP BY YEAR(date_time), MONTH(date_time)";
			$totalorder='';
			$wherequery = "YEAR(date_time)='$year' AND month(date_time)='$month' AND bookingstatus=2 AND total_price=paid_amount GROUP BY YEAR(date_time), MONTH(date_time)";
			$this->db->select('count(bookedid) as totalorder');
			$this->db->from('booked_info');
			$this->db->where($wherequery, NULL, FALSE);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$result=$query->result(); 
				foreach($result as $row){
					$totalorder.=$row->totalorder.", ";
					}
				return trim($totalorder,', ');
			}
			return 0;
		}
		public function monthlybookingpending($year,$month)
		{
			$groupby="GROUP BY YEAR(date_time), MONTH(date_time)";
			$totalorder='';
			$wherequery = "YEAR(date_time)='$year' AND month(date_time)='$month' AND bookingstatus=0 GROUP BY YEAR(date_time), MONTH(date_time)";
			$this->db->select('count(bookedid) as totalorder');
			$this->db->from('booked_info');
			$this->db->where($wherequery, NULL, FALSE);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$result=$query->result(); 
				foreach($result as $row){
					$totalorder.=$row->totalorder.", ";
					}
				return trim($totalorder,', ');
			}
			return 0;
		}
		public function monthlybookingcancel($year,$month)
		{
			$groupby="GROUP BY YEAR(date_time), MONTH(date_time)";
			$totalorder='';
			$wherequery = "YEAR(date_time)='$year' AND month(date_time)='$month' AND bookingstatus=1 GROUP BY YEAR(date_time), MONTH(date_time)";
			$this->db->select('count(bookedid) as totalorder');
			$this->db->from('booked_info');
			$this->db->where($wherequery, NULL, FALSE);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$result=$query->result(); 
				foreach($result as $row){
					$totalorder.=$row->totalorder.", ";
					}
				return trim($totalorder,', ');
			}
			return 0;
		}
		public function monthlybookingtotal($year,$month)
		{
			$groupby="GROUP BY YEAR(date_time), MONTH(date_time)";
			$totalorder='';
			$wherequery = "YEAR(date_time)='$year' AND month(date_time)='$month' AND bookingstatus!=1 GROUP BY YEAR(date_time), MONTH(date_time)";
			$this->db->select('count(bookedid) as totalorder');
			$this->db->from('booked_info');
			$this->db->where($wherequery, NULL, FALSE);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$result=$query->result(); 
				foreach($result as $row){
					$totalorder.=$row->totalorder.", ";
					}
				return trim($totalorder,', ');
			}
			return 0;
		}
	public function onlinesaleamount($year,$month)
		{
			$groupby="GROUP BY YEAR(order_date), MONTH(order_date)";
			$amount='';
			$wherequery = "YEAR(order_date)='$year' AND month(order_date)='$month' AND cutomertype=2 AND order_status!=5 GROUP BY YEAR(order_date), MONTH(order_date)";
			$this->db->select('SUM(totalamount) as amount');
			$this->db->from('customer_order');
			$this->db->where($wherequery, NULL, FALSE);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$result=$query->result(); 
				foreach($result as $row){
					$amount.=$row->amount.", ";
					}
				return trim($amount,', ');
			}
			return 0;
		}
	public function onlinesaleorder($year,$month)
		{
			$groupby="GROUP BY YEAR(order_date), MONTH(order_date)";
			$totalorder='';
			$wherequery = "YEAR(order_date)='$year' AND month(order_date)='$month' AND cutomertype=2 AND order_status!=5 GROUP BY YEAR(order_date), MONTH(order_date)";
			$this->db->select('count(order_id) as totalorder');
			$this->db->from('customer_order');
			$this->db->where($wherequery, NULL, FALSE);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$result=$query->result(); 
				foreach($result as $row){
					$totalorder.=$row->totalorder.", ";
					}
				return trim($totalorder,', ');
			}
			return 0;
		}
	
	public function offlinesaleamount($year,$month)
		{
			$groupby="GROUP BY YEAR(order_date), MONTH(order_date)";
			$amount='';
			$wherequery = "YEAR(order_date)='$year' AND month(order_date)='$month' AND cutomertype=1 AND order_status!=5 GROUP BY YEAR(order_date), MONTH(order_date)";
			$this->db->select('SUM(totalamount) as amount');
			$this->db->from('customer_order');
			$this->db->where($wherequery, NULL, FALSE);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$result=$query->result(); 
				foreach($result as $row){
					$amount.=$row->amount.", ";
					}
				return trim($amount,', ');
			}
			return 0;
		}
	public function offlinesaleorder($year,$month)
		{
			$groupby="GROUP BY YEAR(order_date), MONTH(order_date)";
			$totalorder='';
			$wherequery = "YEAR(order_date)='$year' AND month(order_date)='$month' AND cutomertype=1 AND order_status!=5 GROUP BY YEAR(order_date), MONTH(order_date)";
			$this->db->select('count(order_id) as totalorder');
			$this->db->from('customer_order');
			$this->db->where($wherequery, NULL, FALSE);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$result=$query->result(); 
				foreach($result as $row){
					$totalorder.=$row->totalorder.", ";
					}
				return trim($totalorder,', ');
			}
			return 0;
		}

    public function floor_rooms_test(){
        $this->db->select('*');
        $this->db->from('tbl_roomnofloorassign a');
      //  $this->db->where('a.status',1);
        //$this->db->group_by('a.id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result= $query->result();
        }

        return false;
    }
    public function floor_rooms(){
        $this->db->select('*');
        $this->db->from('tbl_floor a');
        //$this->db->join('tbl_floorplan b','a.floorid=b.floorName');
        $this->db->where('a.status',1);
        //$this->db->group_by('a.id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result= $query->result();
        }
        $data = array();

        //echo '<pre>';print_r($result);exit();

        $sl =1;
        foreach ($result as $r){

//            $room_A=$this->db->select('room_no')->from('booked_info')->get()->result_array();
//           // $room_Array = explode(',',$room_A[2]['room_no']);
//            $data4 = array();
//            $room_Array = '';
//            foreach ($room_A as $vs) {
//
//
//                $data4['room'] = $vs['room_no'];
//
//                $room_Array = explode(',',$data4['room']);
//
//            }

          //  echo '<pre>';print_r($room_Array);exit();
            $room_no=$this->db->select('a.roomno,a.status,z.roomtype,x.*,c.*')
                ->from('tbl_floorplan a')
                ->join('tbl_roomnofloorassign b','a.roomno=b.roomno','left')
                ->join('roomdetails z','z.roomid=b.roomid','left')
                ->join('booked_info c','a.booking_number=c.booking_number','left')
                ->join('customerinfo x','c.cutomerid=x.customerid','left')
                ->where('a.floorName',$r->floorid)
               // ->group_by('a.roomno')
                ->order_by('a.roomno','asc')
                ->get()->result();




           // echo '<pre>';print_r($room_no);exit();
                        $rooms='';



                    foreach ($room_no as $ro){

//                        $date=date('Y-m-d');
//
//                        $exits = $this->db->select("*")->from('booked_info')->where('checkoutdate>',$date)->where('bookingstatus!=',1)->where('roomid',$ro->roomid)->get()->result();
//                        $exit = $this->db->select("*")->from('booked_info')->where('checkoutdate>=',$date)->where('bookingstatus!=',1)->where('roomid',$ro->roomid)->get()->result();
//                        $check = $this->db->select("*")->from('booked_info')->where('checkoutdate<=',$date)->where('bookingstatus!=',1)->where('roomid',$ro->roomid)->get()->result();
//                        $totalroom1 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkoutdate>',$date)->where('bookingstatus!=',1)->where('roomid',$ro->roomid)->get()->row();
//                      $totalroom2 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkoutdate>=',$date)->where('bookingstatus!=',1)->where('roomid',$ro->roomid)->get()->row();
//                      $totalroom3 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkoutdate<=',$date)->where('bookingstatus!=',1)->where('roomid',$ro->roomid)->group_by('checkindate')->get()->result();
//                      $allbokedroom3 = (!empty($allbokedroom3)?max(array_column($totalroom3, 'allroom')):0);
//                      $totalroomfound=$this->db->select("count(roomid) as totalroom")->from('tbl_roomnofloorassign')->where('roomid',$ro->roomid)->get()->row();
//                      $roomdetails=$this->db->select("*")->from('roomdetails')->where('roomid',$ro->roomid)->get()->row();
//                      $numberlist=$this->db->select("*")->from('tbl_roomnofloorassign')->where('roomid',$ro->roomid)->get()->result();
//
//                        $roomlist='';
//                        foreach($numberlist as $singleno){
//                            $roomlist.=$singleno->roomno.',';
//                        }
//                        $gtroomno=rtrim($roomlist,',');
//                        if(empty($exits)&&empty($exit)&&empty($check)){
//                            $data['freeroom']=$gtroomno;
//                            $data['isfound']=0;
//                        }
//                        else{
//                            $bookedroom="";
//                            if(!empty($exit)){
//                                foreach($exits as $booked){
//                                    $bookedroom.=$booked->room_no.',';
//                                }
//                            }
//                            if(!empty($exit)){
//                                foreach($exit as $ex){
//                                    $bookedroom.=$ex->room_no.',';
//                                }
//                            }
//                            if(!empty($check)){
//                                foreach($check as $ch){
//                                    $bookedroom.=$ch->room_no.',';
//                                }
//                            }
//
//                            $getbookedall=rtrim($bookedroom,',');
//                            $allbokedroom1=$totalroom1->allroom;
//                            $allbokedroom2=$totalroom2->allroom;
//                            $allbokedroom=max((int)$allbokedroom1,(int)$allbokedroom2,(int)$allbokedroom3);
//                            $allfreeroom=$totalroomfound->totalroom;
//                            if($allfreeroom>$allbokedroom){
//                                $output=$this->Differences($getbookedall, $gtroomno);
//                                if(!empty($output)){
//                                    $data['freeroom']=$output;
//                                    $data['isfound']='1';
//                                }
//                                else{
//                                    $data['freeroom']='';
//                                    $data['isfound']='2';
//                                }
//                            }
//                            else{
//                                $data['freeroom']='';
//                                $data['isfound']='2';
//                            }
//                        }


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




            );

            $sl++;
        }

        return $data;
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

}
 