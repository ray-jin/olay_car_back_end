<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Offer extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->model('offers');
                $this->load->helper('url');
                $this->load->model('manage_m'); // a little different
                $this->load->library('form_validation');
                
	}
	
        function _remap($method) {
		$this->load->view('header_v');
		$this->{$method}();
		$this->load->view('footer_v');
	}
        
	function index()
	{
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$start_no = empty($_REQUEST['per_page'])? 0:$_REQUEST['per_page'];		
		$per_page = $this->config->item('max_count_per_page');
            
                $data['username'] = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : "" ;
//		$result = $this->offers->get_object_list($start_no,$per_page);
                $result = $this->offers->list_offers($start_no,$per_page,$data['username'] );
		$total_page = $result['total'];
		$data['offer_list'] = $result['rows'];
		                
		$base_url = site_url("admin/offer?a=1")."&username=".$data['username'];
		$data['pagenation'] = $this->offers->_create_pagenation($per_page, $total_page, $base_url);
		$data['post_key'] = "offer";
                $data['start_no'] =$start_no;
		$this->load->view('offer/offer_list_v',$data);	
	}
        
        function offer_del() {
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select task!";
			return;
		}
		$this->_proc_post_del($post_id);
		
		redirect("admin\offer");
	}
	
        function offer_add() {
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$data = $this->_proc_post_add();
		$data['post_key'] = "offer";
		$this->load->view('offer/offer_add_v', $data);
	}
        
       function offer_edit() {	
            if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
            
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select offer!";
			return;
		}
		
		$data = $this->_proc_post_edit($post_id);
		$data['post_key'] = "offer";	
		$data['post'] = $this->offers->get_specific_data($post_id);
		$this->load->view('offer/offer_edit_v', $data);
	}
        
        private function &_proc_post_add() {
		$this->load->library('upload');
		
		$this->form_validation->set_rules('uid', 'User ID', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('cid', 'Car ID', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('price', 'price', 'is_natural|numeric|trim|required|xss_clean');                
		
		$qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{
					
			$tbl_name = "offer_cars";
			$new_idx = $this->offers->get_next_insert_idx($tbl_name);
		
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
			
                                $qry = array_merge(	
                                        $qry,
                                        array(
                                                'id'		=> $new_idx,						
                                                'uid'  => $this->input->post('uid'),
                                                'cid'	=> $this->input->post('cid'),
                                                'price'	=> $this->input->post('price'),
                                                'message'=> $this->input->post('message'),
                                                'created' =>  date('Y-m-d H:i:s')
                                        )
                                );

                                if($this->db->insert($tbl_name, $qry)){
                                //	$data['show_message'] = "Successfully added!";
                                        redirect("admin/offer");
                                }

			}			
		}//end run
		
		return $data;
        }
        
        private function &_proc_post_edit($new_idx) { 
    
       		$this->load->library('upload');
    	             
                $this->form_validation->set_rules('uid', 'User ID', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('cid', 'Car ID', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('price', 'Price', 'is_natural|numeric|trim|required|xss_clean');
                
                

                $qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{	
                
                    $tbl_name = "offer_cars";	
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
				

                                
				$qry = array_merge(	
					$qry,
					array(
						'id'		=> $new_idx,
						'cid'  => $this->input->post('cid'),
						'uid'		=> $this->input->post('uid'),
                                                'price' => $this->input->post('price'),
						'message'	=> $this->input->post('message')
					)
				);
				
				$this->db->where('id', $new_idx);
				$this->db->update($tbl_name, $qry);
				
				$data['show_message'] = "Successfully updated!";

			}			
		}//end run	
			
		return $data;	
		
    } //end function
    
    private function _proc_post_del($idx) {    	
            $strSql = "DELETE FROM offer_cars WHERE id='$idx' ";
            $this->db->query($strSql);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


//Watch list completed