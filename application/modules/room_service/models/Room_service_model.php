<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_service_model extends CI_Model {
	
	private $table = 'service_table';
 
	public function create($data = array())
	{
        $this->db->insert($this->table, $data);

        $service_id=$this->db->insert_id();

        $v_name=  $this->input->post('v_name', TRUE);
        $rate=  $this->input->post('rate', TRUE);

        if ( ! empty($v_name) && ! empty($rate) )
        {

            foreach ($v_name as $key => $value )
            {


                $data_service['variation_name'] = $value;
                $data_service['service_id']=$service_id;
                $data_service['rate']=$rate[$key];


                //  echo '<pre>';print_r($data);
                // $this->ProductModel->add_products($data);
                if ( ! empty($data_service))
                {
                    $this->db->insert('service_variation', $data_service);
                }
            }

        }

		return $data;
	}
	public function delete($id = null)
	{
		$this->db->where('service_id',$id)
			->delete($this->table);


		$this->db->where('service_id',$id)
			->delete('service_variation');

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 
	public function update($data = array())
	{
        $this->db->where('service_id',$data["service_id"])
            ->delete('service_variation');


        $v_name=  $this->input->post('v_name_e', TRUE);
        $rate=  $this->input->post('rate_e', TRUE);

        if ( ! empty($v_name) && ! empty($rate) )
        {

            foreach ($v_name as $key => $value )
            {


                $data_service['variation_name'] = $value;
                $data_service['service_id']=$data["service_id"];
                $data_service['rate']=$rate[$key];


                //  echo '<pre>';print_r($data);
                // $this->ProductModel->add_products($data);
                if ( ! empty($data_service))
                {
                    $this->db->insert('service_variation', $data_service);
                }
            }

        }

		return $this->db->where('service_id',$data["service_id"])
			->update($this->table, $data);
	}

    public function read()
	{
	   $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('service_id ', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
             $result= $query->result();
        }

        $data = array();

        $sl =1;
        if (!empty($result)) {
            foreach ($result as $r) {

                $var = $this->db->select('*')->from('service_variation')->where('service_id', $r->service_id)->get()->result();
                $html = '';
                foreach ($var as $v) {

//                        $html.='<ul class="">
//
//                <li>'.$v->variation_name.'=>'.$v->rate.'</li>
//                </ul>';

                    $html .= '<ul class="list-group">

                <li class="list-group-item d-flex justify-content-between align-items-center" >
    ' . $v->variation_name . '
    <span class="badge badge-danger-soft badge-pill">৳ ' . number_format($v->rate, 2) . '</span>
  </li>
                </ul>';


                }
                $data[] = array(
                    'sl' => $sl,
                    'var_list' => $html,
                    'service_id' => $r->service_id,
                    'service_name' => $r->service_name,

                );

                $sl++;
            }
        }

        return $data;
	} 

	public function findById($id = null)
	{ 
		return $this->db->select("*")->from($this->table)
			->where('service_id',$id)

			->get()
			->row();
	}

	public function variation_list_by_id($id = null)
	{
		return $this->db->select("*")->from('service_variation')
			->where('service_id',$id)
			->get()
			->result();
	}

 
public function countlist()
	{
		$this->db->select('*');
        $this->db->from($this->table);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}
    
}
