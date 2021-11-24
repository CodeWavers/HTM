<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->load->model(array(
			'report_model'
		));	
		 $this->load->library('cart');
    }
 
    public function index(){
		$data['title'] = display('booking_report');
		$data['module'] = "reports";
		$data['page']   = "report_search"; 

		$this->load->library('pagination'); 
        #
        #pagination starts
        #
        $config["base_url"]       = base_url('reports/report/index/'); 
        $config["total_rows"]     = $this->db->count_all('booked_info'); 
        $config["per_page"]       = 15;
        $config["uri_segment"]    = 4; 
        $config["num_links"]      = 5;  
        /* This Application Must Be Used With BootStrap 4 * */
        $config['full_tag_open']='<ul class="pagination pagination-md">';
        $config['full_tag_close']='</ul>';
		$config['first_link'] = false;
		$config['first_tag_open'] = '<li class="page-item disabled">';
        $config['first_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item"><a class="page-link active">';
        $config['cur_tag_close'] = '</a></li>';
		$config['next_link'] = '<i class="ti-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = '</a></li>';
		$config['prev_link'] = '<i class="ti-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = '</li>';
		$config['last_link'] =false;
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["bookings"] = $this->report_model->read($config["per_page"], $page); 
        $data["links"] = $this->pagination->create_links(); 
        #
        #pagination ends
        #   
	    echo Modules::run('template/layout', $data); 
		}
	public function getinvoice(){
		$status=$this->input->post('status',TRUE);
		$startdates=$this->input->post('start_date',TRUE);
		$endate=$this->input->post('to_date',TRUE);
		$data['bookinfo']   = $this->report_model->getlist($status,$startdates,$endate);
		$data['module'] = "reports";  
        $data['page']   = "getbookingreport";
		$this->load->view('reports/getbookingreport', $data);   
		}
	public function viewdetails($id){
		$details=$this->report_model->details($id);

        $booking_details=$this->room_type_by_booking_number($details->booking_number);
        $room_type=$this->room_type_by_booking_number($details->booking_number);
        $room_name='';

        foreach ($room_type as $rr){

            $room_name .=$rr->roomtype.',';

        }

        $rooms=$this->room_no_by_booking_number($details->booking_number);

        $room_no='';
        foreach ($rooms as $rs){

            $room_no .=$rs->room_no.',';

        }
		$data['booking_details']   = $rooms;
		$data['bookinfo']   = $details;
		$data['room_name']   = $room_name;
		$data['room_no']   = $room_no;
		$data['customerinfo']   = $this->report_model->customerinfo($details->cutomerid);
		$data['paymentinfo']   = $this->report_model->paymentinfo($details->booking_number);
		$data['booking_service']   = $this->report_model->booked_service($details->booking_number);

		//echo '<pre>';print_r($booking_details);exit();
		$data['storeinfo']=$this->report_model->storeinfo();
		$data['commominfo']=$this->report_model->commoninfo();
		$data['currency']=$this->report_model->currencysetting($data['storeinfo']->currency);
		$data['module'] = "reports";
	    $data['page']   = "bookindetails";   
	    echo Modules::run('template/layout', $data); 
		}
	public function customer_receit($id){
		$details=$this->report_model->details($id);
		$data['bookinfo']   = $details;
		$data['customerinfo']   = $this->report_model->customerinfo($details->cutomerid);
		$data['paymentinfo']   = $this->report_model->paymentinfo($details->booking_number);
		$data['commoninfo']=$this->report_model->commoninfo();
		$data['storeinfo']=$this->report_model->storeinfo();
		$data['module'] = "reports";
	    $data['page']   = "guest_invoice";   
	    echo Modules::run('template/layout', $data); 
		}
	public function productreport($id = null)
    {
		$this->permission->method('reports','read')->redirect();
        $data['title']    = display('purchase_report'); 
		$first_date = str_replace('/','-',$this->input->post('from_date',TRUE));
		$start_date= date('Y-m-d' , strtotime($first_date));
		$second_date = str_replace('/','-',$this->input->post('to_date',TRUE));
		$end_date= date('Y-m-d' , strtotime($second_date));
        $data['preport']  = $this->report_model->pruchasereport($start_date,$end_date);
		$settinginfo=$this->report_model->settinginfo();
		$data['setting']=$settinginfo;
		$data['currency']=$this->report_model->currencysetting($settinginfo->currency);
        $data['module'] = "reports";
        $data['page']   = "prechasereport";   
        echo Modules::run('template/layout', $data); 
    }
	
	
    public function purchasereport()
    {
	    $this->permission->method('reports','read')->redirect();
        $data['title']    = display('purchase_report'); 
		$first_date = str_replace('/','-',$this->input->post('from_date',TRUE));
		$start_date= date('Y-m-d' , strtotime($first_date));
		$second_date = str_replace('/','-',$this->input->post('to_date',TRUE));
		$end_date= date('Y-m-d' , strtotime($second_date));
        $data['preport']  = $this->report_model->pruchasereport($start_date,$end_date);
		$settinginfo=$this->report_model->settinginfo();
		$data['setting']=$settinginfo;
		$data['currency']=$this->report_model->currencysetting($settinginfo->currency);
        $data['module'] = "reports";
        $data['page']   = "getpreport";  
		$this->load->view('reports/getpreport', $data);  
 
    }
	public function stockreport()
    {
		$this->permission->method('reports','read')->redirect();
		$data['title']    = display('stock_report'); 
        $data['stockreport']  = $this->report_model->getstocklist();
		$settinginfo=$this->report_model->settinginfo();
		$data['setting']=$settinginfo;
		$data['currency']=$this->report_model->currencysetting($settinginfo->currency);
        $data['module'] = "reports";
        $data['page']   = "purchaseview";   
        echo Modules::run('template/layout', $data); 
 
    }
    public function get_room_id_by_room_no($room_no){

        $room_id=$this->db->select('roomid')->from('tbl_roomnofloorassign')->where('roomno',$room_no)->get()->row()->roomid;

        return $room_id;
    }

    public function get_room_rate_by_room_id($room_id){

        $room_rate=$this->db->select('rate')->from('roomdetails')->where('roomid',$room_id)->get()->row()->rate;

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
