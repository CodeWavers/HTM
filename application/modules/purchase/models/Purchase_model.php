<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_model extends CI_Model {
	
	private $table = 'purchaseitem';
 
	public function create()
	{
		$saveid=$this->session->userdata('id');
		$p_id = $this->input->post('product_id', TRUE);
		$purchase_date = str_replace('/','-',$this->input->post('purchase_date',TRUE));
		$newdate= date('Y-m-d' , strtotime($purchase_date));
		$expire_date = str_replace('/','-',$this->input->post('expire_date',TRUE));
		$exdate= date('Y-m-d' , strtotime($expire_date));
		$data=array(
			'invoiceid'				=>	$this->input->post('invoice_no',TRUE),
			'suplierID'			    =>	$this->input->post('suplierid', TRUE),
			'total_price'	        =>	$this->input->post('grand_total_price',TRUE),
			'details'	            =>	$this->input->post('purchase_details',TRUE),
			'purchasedate'		    =>	$newdate,
			'purchaseexpiredate'	=>	$exdate,
			'savedby'			    =>	$saveid
		);
		 $this->db->insert($this->table,$data);
		$returnid = $this->db->insert_id();
		
		$rate = $this->input->post('product_rate', TRUE);
		$quantity = $this->input->post('product_quantity', TRUE);
		$t_price = $this->input->post('total_price', TRUE);
		
		for ($i=0, $n=count($p_id); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_rate = $rate[$i];
			$product_id = $p_id[$i];
			$total_price = $t_price[$i];
			
			$data1 = array(
				'purchaseid'		=>	$returnid,
				'proid'			    =>	$product_id,
				'quantity'			=>	$product_quantity,
				'price'				=>	$product_rate,
				'totalprice'		=>	$total_price,
				'purchaseby'		=>	$saveid,
				'purchasedate'		=>	$newdate,
				'purchaseexpiredate'=>	$exdate
			);

			if(!empty($quantity))
			{
				$this->db->insert('purchase_details',$data1);
			}
		}
		// Acc transaction
		$recv_id = date('YmdHis');
		$receive_transection = array(
					'VNo'            =>  $this->input->post('invoice_no',TRUE),
					'Vtype'          =>  'PO',
					'VDate'          =>  $newdate,
					'COAID'          =>  10107,
					'Narration'      =>  'PO Receive Receive No '.$recv_id,
					'Debit'          =>  $this->input->post('grand_total_price',TRUE),
					'Credit'         =>  0,
					'StoreID'        => 0,
					'IsPosted'       => 1,
					'CreateBy'       => $saveid,
					'CreateDate'     => $newdate,
					'IsAppove'       => 1
				); 
		$this->db->insert('acc_transaction',$receive_transection);
		
		
		$supinfo =$this->db->select('*')->from('supplier')->where('supid',$this->input->post('suplierid', TRUE))->get()->row();
		$sup_head = $supinfo->suplier_code.'-'.$supinfo->supName;
		$sup_coa = $this->db->select('*')->from('acc_coa')->where('HeadName',$sup_head)->get()->row();
		  //  Supplier credit
	  $poCredit = array(
		  'VNo'            =>  $this->input->post('invoice_no',TRUE),
		  'Vtype'          =>  'PO',
		  'VDate'          =>  $newdate,
		  'COAID'          =>  $sup_coa->HeadCode,
		  'Narration'      =>  'PO received For PO No.'.$this->input->post('invoice_no',TRUE).' Receive No.'.$recv_id,
		  'Debit'          =>  0,
		  'Credit'         =>  $this->input->post('grand_total_price',TRUE),
		  'StoreID'        =>  0,
		  'IsPosted'       =>  1,
		  'CreateBy'       =>  $saveid,
		  'CreateDate'     =>  $newdate,
		  'IsAppove'       =>  1
    	); 
       $this->db->insert('acc_transaction',$poCredit);


	   // Supplier Debit for cash Amount
	    $podebitpaidamount = array(
		  'VNo'            =>  $this->input->post('invoice_no',TRUE),
		  'Vtype'          =>  'PO',
		  'VDate'          =>  $newdate,
		  'COAID'          =>  $sup_coa->HeadCode,
		  'Narration'      =>  'Paid For PO No.'.$this->input->post('invoice_no', TRUE).' Receive No.'.$recv_id,
		  'Debit'          =>  $this->input->post('grand_total_price',TRUE),// paid amount*****
		  'Credit'         =>  0,
		  'StoreID'        =>  0,
		  'IsPosted'       =>  1,
		  'CreateBy'       =>  $saveid,
		  'CreateDate'     =>  $newdate,
		  'IsAppove'       =>  1
    	); 
       $this->db->insert('acc_transaction',$podebitpaidamount);
	   
	   //Cash in Hand  Cdedit.
	    $podebitpaidamount = array(
		  'VNo'            =>  $this->input->post('invoice_no',TRUE),
		  'Vtype'          =>  'PO',
		  'VDate'          =>  $newdate,
		  'COAID'          =>  1020101,
		  'Narration'      =>  'Paid For PO No.'.$this->input->post('invoice_no',TRUE).' Receive No.'.$recv_id,
		  'Debit'          =>  0,
		  'Credit'         =>  $this->input->post('grand_total_price',TRUE),// paid amount*****
		  'StoreID'        =>  0,
		  'IsPosted'       =>  1,
		  'CreateBy'       =>  $saveid,
		  'CreateDate'     =>  $newdate,
		  'IsAppove'       =>  1
    	); 
       $this->db->insert('acc_transaction',$podebitpaidamount);
		return true;
	
	}
	
	public function delete($id = null)
	{
		$this->db->where('purID',$id)
			->delete($this->table);

		$this->db->where('purchaseid',$id)
			->delete('purchase_details');

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 




	public function update()
	{
		$id=$this->input->post('purID', TRUE);
		$saveid=$this->session->userdata('id');
		$p_id = $this->input->post('product_id', TRUE);
		$oldinvoice=$this->input->post('oldinvoice',TRUE);
		$oldsupplier= $this->input->post('oldsupplier',TRUE);
		$length= count($p_id);
		$purchase_date = str_replace('/','-',$this->input->post('purchase_date', TRUE));
		$newdate= date('Y-m-d' , strtotime($purchase_date));
		$expire_date = str_replace('/','-',$this->input->post('expire_date', TRUE));
		$exdate= date('Y-m-d' , strtotime($expire_date));
		$data=array(
			'invoiceid'				=>	$this->input->post('invoice_no',TRUE),
			'suplierID'			    =>	$this->input->post('suplierid', TRUE),
			'total_price'	        =>	$this->input->post('grand_total_price',TRUE),
			'details'	            =>	$this->input->post('purchase_details',TRUE),
			'purchasedate'		    =>	$newdate,
			'purchaseexpiredate'	=>	$exdate,
			'savedby'			    =>	$saveid
		);
		 $this->db->where('purID',$id)
			->update($this->table, $data);
		
		
		$rate = $this->input->post('product_rate',TRUE);
		$quantity = $this->input->post('product_quantity',TRUE);
		$t_price = $this->input->post('total_price',TRUE);
		
		for ($i=0, $n=count($p_id); $i < $n; $i++){
			$product_quantity = $quantity[$i];
			$product_rate = $rate[$i];
			$product_id = $p_id[$i];
			$total_price = $t_price[$i];
			$this->db->select('*');
            $this->db->from('purchase_details');
            $this->db->where('purchaseid',$id);
			$this->db->where('proid',$product_id);
            $query = $this->db->get();
			if ($query->num_rows() > 0) {
				
				$dataupdate = array(
					'purchaseid'		=>	$id,
					'proid'		        =>	$product_id,
					'quantity'			=>	$product_quantity,
					'price'				=>	$product_rate,
					'totalprice'		=>	$total_price,
					'purchaseby'		=>	$saveid,
					'purchasedate'		=>	$newdate,
					'purchaseexpiredate'=>	$exdate
				);	
			
				if(!empty($quantity))
				{
					$this->db->where('purchaseid', $id);
					$this->db->where('proid', $product_id);
					$this->db->update('purchase_details', $dataupdate);
				}
			}
			else{
				$data1 = array(
					'purchaseid'		=>	$id,
					'proid'		        =>	$product_id,
					'quantity'			=>	$product_quantity,
					'price'				=>	$product_rate,
					'totalprice'		=>	$total_price,
					'purchaseby'		=>	$saveid,
					'purchasedate'		=>	$newdate
				);
				if(!empty($quantity))
				{
					$this->db->insert('purchase_details',$data1);
				}
			}
		}
		
			$this->db->select('*');
            $this->db->from('purchase_details');
            $this->db->where('purchaseid',$id);
            $query = $this->db->get();
			$details=$query->result_array();
			$test=array();
			$k=0;
			foreach($details as $single){
				$k++;
				$test[$k]=$single['proid'];
				}
			$result=array_diff($test,$p_id);
			if(!empty($result)){
				foreach($result as $delval){
					$this->db->where('proid', $delval);
					$this->db->where('purchaseid',$id);
					$del=$this->db->delete('purchase_details',TRUE); 
					}
			}
			
			$supinfo =$this->db->select('*')->from('supplier')->where('supid',$oldsupplier)->get()->row();
			$sup_head = $supinfo->suplier_code.'-'.$supinfo->supName;
			$sup_coa = $this->db->select('*')->from('acc_coa')->where('HeadName',$sup_head)->get()->row();
			
			$trans_coa = $this->db->select('*')->from('acc_transaction')->where('VNo',$oldinvoice)->where('COAID','10107')->get()->row();
			$updateid=$trans_coa->ID;
			$invoice=$this->input->post('invoice_no',TRUE);
			$invoicetotal=$this->input->post('grand_total_price',TRUE);
			  $receive_transection=[
			   'VNo'              => $invoice,
			   'VDate'            => $newdate,
			   'Debit'            => $invoicetotal,
			   'UpdateBy'         => $saveid,
			   'UpdateDate'       => $newdate
			  ];
		    $this->db->where('ID',$updateid);
	        $this->db->update('acc_transaction',$receive_transection);
			
	
		$store_inventory = $this->db->select('*')->from('acc_transaction')->where('VNo',$oldinvoice)->where('COAID','1020101')->get()->row();
	    $updateinventoryid=$store_inventory->ID;
			
		$supold_coa= $this->db->select('*')->from('acc_transaction')->where('VNo',$oldinvoice)->where('COAID',$sup_coa->HeadCode)->where('Credit>',0)->get()->row();	
		$updatesupid=$supold_coa->ID;
		
		$supold_coa= $this->db->select('*')->from('acc_transaction')->where('VNo',$oldinvoice)->where('COAID',$sup_coa->HeadCode)->where('Debit>',0)->get()->row();	
		$Debitupdatesupid=$supold_coa->ID;
		
		//  Supplier credit
		  $poCredit = array(
			  'VNo'            =>  $invoice,
			  'VDate'          =>  $newdate,
			  'Credit'         =>  $this->input->post('grand_total_price',TRUE),
			  'UpdateBy'       =>  $saveid,
			  'UpdateDate'     =>  $newdate,
			); 
		$this->db->where('ID',$updatesupid);
		$this->db->update('acc_transaction',$poCredit);	
	
	  // Supplier Debit for cash Amount
	    $podebitpaidamount = array(
		  'VNo'            =>  $invoice,
		  'VDate'          =>  $newdate,
		  'Debit'         =>   $this->input->post('grand_total_price',TRUE),
		  'UpdateBy'       =>  $saveid,
		  'UpdateDate'     =>  $newdate,
    	); 
       $this->db->where('ID',$Debitupdatesupid);
       $this->db->update('acc_transaction',$podebitpaidamount);	
	   
	   //Cash in Hand  Cdedit.
	    $creditcashpaidamount = array(
		  'VNo'            =>  $invoice,
		  'VDate'          =>  $newdate,
		  'Credit'         =>  $this->input->post('grand_total_price',TRUE),// paid amount*****
		  'UpdateBy'       =>  $saveid,
		  'UpdateDate'     =>  $newdate,
    	); 
        $this->db->where('ID',$updateinventoryid);
	    $this->db->update('acc_transaction',$creditcashpaidamount);
		return true;
	
	
	}
	
	
	public function makeproduction()
	{
		$saveid=$this->session->userdata('id');
		$p_id = $this->input->post('product_id', TRUE);
		$purchase_date = str_replace('/','-',$this->input->post('purchase_date', TRUE));
		$newdate= date('Y-m-d' , strtotime($purchase_date));
		$data=array(
			'itemid'				=>	$this->input->post('foodid',TRUE),
			'itemquantity'			=>	$this->input->post('pro_qty',TRUE),
			'saveddate'		    	=>	$newdate,
			'savedby'			    =>	$saveid
		);
		$this->db->insert('production',$data);
		$returnid = $this->db->insert_id();
		$quantity = $this->input->post('product_quantity', TRUE);
		
		for ($i=0, $n=count($p_id); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_id = $p_id[$i];
			
			$data1 = array(
				'productionid'		=>	$returnid,
				'ingredientid'		=>	$product_id,
				'qty'				=>	$product_quantity,
				'createdby'			=>	$saveid,
				'created_date'		=>	$newdate
			);

			if(!empty($quantity))
			{
				$this->db->insert('production_details',$data1);
			}
		}
		return true;
	
	}

    public function read()
	{
	    $this->db->select('purchaseitem.*,supplier.supName');
        $this->db->from($this->table);
		$this->db->join('supplier','purchaseitem.suplierID = supplier.supid','left');
        $this->db->order_by('purID', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 

	public function findById($id = null)
	{ 
		return $this->db->select("*")->from($this->table)
			->where('purID',$id) 
			->get()
			->row();
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
	public function checkitem($product_name)
	{ 
	$this->db->select('*');
	$this->db->from('products');
	$this->db->where('is_active',1);
	$this->db->where('product_name', $product_name);
	$query = $this->db->get();
	if ($query->num_rows() > 0) {
		return $query->result_array();	
	}
	return false;
	}
	public function finditem($product_name)
		{ 
		$this->db->select('*');
		$this->db->from('products');
		$this->db->where('is_active',1);
		$this->db->like('product_name', $product_name);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
		}
	public function get_total_product($product_id){
		$this->db->select('SUM(quantity) as total_purchase');
		$this->db->from('purchase_details');
		$this->db->where('proid',$product_id);
		$total_purchase = $this->db->get()->row();
		
		$available_quantity = $total_purchase->total_purchase ;
		
		$data2 = array(
			'total_purchase'  => $available_quantity
			);
		

		return $data2;
		}
 public function iteminfo($id){
	 	$this->db->select('purchase_details.*,products.id,products.product_name,unit_of_measurement.uom_short_code');
		$this->db->from('purchase_details');
		$this->db->join('products','purchase_details.proid=products.id','left');
		$this->db->join('unit_of_measurement','unit_of_measurement.id = products.uom_id','inner');
		$this->db->where('purchaseid',$id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();	
		}
		return false;
		
	 }
//item Dropdown
 public function item_dropdown()
	{
		$data = $this->db->select("*")
			->from('item_foods')
			->get()
			->result();

		$list[''] = 'Select '.display('item_name');
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->ProductsID] = $value->ProductName;
			return $list;
		} else {
			return false; 
		}
	}
 //ingredient Dropdown
 public function ingrediant_dropdown()
	{
		$data = $this->db->select("*")
			->from('products')
			->where('is_active',1) 
			->get()
			->result();

		$list[''] = 'Select '.display('item_name');
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->id] = $value->product_name;
			return $list;
		} else {
			return false; 
		}
	}
//item Dropdown
 public function supplier_dropdown()
	{
		$data = $this->db->select("*")
			->from('supplier')
			->get()
			->result();

		$list[''] = display('supplier_name');
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->supid] = $value->supName;
			return $list;
		} else {
			return false; 
		}
	}
	public function product_dropdown()
	{
		$data = $this->db->select("*")
			->from('products')
			->get()
			->result();

		$list[''] = 'Select '.display('product_name');
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->id] = $value->product_name;
			return $list;
		} else {
			return false; 
		}
	}
public function suplierinfo($id){
	return $this->db->select("*")->from('supplier')
			->where('supid',$id) 
			->get()
			->row();
	
	}
public function countlist()
	{
		
	    $this->db->select('purchaseitem.*,supplier.supName');
        $this->db->from($this->table);
		$this->db->join('supplier','purchaseitem.suplierID = supplier.supid','left');
		
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}
 public function invoicebysupplier($id){
	 	 $this->db->select('*');
         $this->db->from($this->table);
		 $this->db->where('suplierID',$id);
		 $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();  
        }
        return false;
	 }
public function getinvoice($id){
	 	 $this->db->select('*');
         $this->db->from($this->table);
		 $this->db->where('invoiceid',$id);
		 $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();  
        }
        return false;
	 }
	public function pur_return_insert(){
				/*purchase Return Insert*/
				$po_no =  $this->input->post('invoice',TRUE);
				$createby=$this->session->userdata('id');
				$createdate=date('Y-m-d H:i:s');
				$postData = array(
				'po_no'			        =>	$po_no,
				'supplier_id'		    =>	$this->input->post('supplier_id', TRUE),
				'return_date'           =>  $this->input->post('return_date',TRUE),
				'totalamount'           =>  $this->input->post('grand_total_price',TRUE),
				'return_reason'         =>  $this->input->post('reason',TRUE),
				'createby'		        =>	$createby,
				'createdate'		    =>	$createdate
				); 
				$grand_total_price=$this->input->post('grand_total_price',TRUE);
				$this->db->insert('purchase_return',$postData);
				$id =$this->db->insert_id();
				/***************End**********************/
				/*update Purchase stock and Amount*/
				 $this->db->select('*');
                 $this->db->from($this->table);
				 $this->db->where('invoiceid',$po_no);
				 $query = $this->db->get();
				 $purchase= $query->row();
				 $purchaseid=$purchase->purID;
				 $updategrandtotal=$purchase->total_price-$grand_total_price;
				 $updateData = array('total_price'   =>	$updategrandtotal);
				 $this->db->where('invoiceid',$po_no)
				 ->update('purchaseitem', $updateData); 
				/***************End**********************/
				
				$p_id = $this->input->post('product_id',TRUE);
				$pq = $this->input->post('total_price',TRUE);
				$rate = $this->input->post('prate',TRUE);
				$quantity = $this->input->post('total_qntt',TRUE);
				for ($i=0, $n=count($p_id); $i <= $n; $i++) {
					$product_quantity = $quantity[$i];
					$product_rate = $rate[$i];
					$product_id = $p_id[$i];
					$removeprice=$pq[$i];
					if($product_quantity>0){
					$data = array(
					'preturn_id'        =>  $id,
					'product_id'		=>	$product_id,
					'qty'			    =>	$product_quantity,
					'product_rate'	    =>	$rate[$i],
					);
					 $this->db->insert('purchase_return_details',$data);
					 $this->db->select('*');
					 $this->db->from('purchase_details');
					 $this->db->where('purchaseid',$purchaseid);
					 $this->db->where('proid',$product_id);
					 $query = $this->db->get();
					  if ($query->num_rows() > 0) {
					 $purchasedetails= $query->row();
					 $rateprice=$product_quantity*$product_rate;
					 $qtotalpr=$purchasedetails->totalprice-$removeprice;
					 $adjustqty=$purchasedetails->quantity-$product_quantity;
					$qtyData = array(
					'quantity'   =>	$adjustqty,
					'totalprice'   => $qtotalpr);
					 $this->db->where('purchaseid',$purchaseid)
					->where('proid',$product_id)
					->update('purchase_details', $qtyData);
					  }
					  }
				}
		$recv_id = date('YmdHis');
		$supinfo =$this->db->select('*')->from('supplier')->where('supid',$this->input->post('supplier_id', TRUE))->get()->row();
		$sup_head = $supinfo->suplier_code.'-'.$supinfo->supName;
		$sup_coa = $this->db->select('*')->from('acc_coa')->where('HeadName',$sup_head)->get()->row();

	  //  Supplier credit
	  
	  $poCredit = array(
		  'VNo'            =>  $this->input->post('invoice',TRUE),
		  'Vtype'          =>  'PO',
		  'VDate'          =>  $createdate,
		  'COAID'          =>  $sup_coa->HeadCode,
		  'Narration'      =>  'P Return For '.$po_no,
		  'Debit'          =>  $grand_total_price,
		  'Credit'         =>  0,
		  'StoreID'        =>  0,
		  'IsPosted'       =>  1,
		  'CreateBy'       =>  $createby,
		  'CreateDate'     =>  $createdate,
		  'IsAppove'       =>  1
    	); 
       $this->db->insert('acc_transaction',$poCredit);
	   // Acc transaction
	   $receive_transection = array(
					'VNo'            =>  $this->input->post('invoice',TRUE),
					'Vtype'          =>  'PO',
					'VDate'          =>  $createdate,
					'COAID'          =>  10107,
					'Narration'      =>  'Purchase Return For PO No'.$po_no,
					'Debit'          =>  0,
					'Credit'         =>  $grand_total_price,
					'StoreID'        => 0,
					'IsPosted'       => 1,
					'CreateBy'       => $createby,
					'CreateDate'     => $createdate,
					'IsAppove'       => 1
				); 
		$this->db->insert('acc_transaction',$receive_transection);
		return true;
		}
	public function readinvoice()
	{
	    $this->db->select('purchase_return.*,supplier.supName');
        $this->db->from('purchase_return');
		$this->db->join('supplier','purchase_return.supplier_id = supplier.supid','left');
        $this->db->order_by('purchase_return.preturn_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 	
	public function countreturnlist()
	{
		
	    $this->db->select('purchase_return.*,supplier.supName');
        $this->db->from('purchase_return');
		$this->db->join('supplier','purchase_return.supplier_id = supplier.supid','left');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}
  public function findByreturnId($id = null)
	{ 
		 $this->db->select('purchase_return.*,supplier.supName');
        $this->db->from('purchase_return');
		$this->db->join('supplier','purchase_return.supplier_id = supplier.supid','left');
		$this->db->where('preturn_id',$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();  
        }
        return false;
	}
  public function returniteminfo($id){
	 	$this->db->select('purchase_return_details.*,products.product_name,unit_of_measurement.uom_short_code');
		$this->db->from('purchase_return_details');
		$this->db->join('products','purchase_return_details.product_id=products.id','left');
		$this->db->join('unit_of_measurement','unit_of_measurement.id = products.uom_id','inner');
		$this->db->where('preturn_id',$id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();	
		}
		return false;
		
	 }
}
