<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Watch extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->model('watches');
                $this->load->model('manage_m'); // a little different
                $this->load->helper('url');
                $this->load->library('form_validation');
                $this->load->model('cars'); // a little different
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
                $result = $this->watches->list_watches($start_no,$per_page,$data['username'] );
                
		$total_page = $result['total'];
		$data['watch_list'] = $result['rows'];
		
		$base_url = site_url("admin/watch?a=1")."&username=".$data['username'];
                
		$data['pagenation'] = $this->watches->_create_pagenation($per_page, $total_page, $base_url);
		$data['post_key'] = "watch";
                $data['start_no'] =$start_no;
		$this->load->view('watch/watch_list_v',$data);	
	}
        
        function watch_del() {
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select task!";
			return;
		}
		$this->_proc_post_del($post_id);
		
		redirect("admin\watch");
	}
	
        function watch_add() {
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$data = $this->_proc_post_add();
		$data['post_key'] = "watch";
		$this->load->view('watch/watch_add_v', $data);
	}
        
       function watch_edit() {	
            if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select watch!";
			return;
		}
		
		$data = $this->_proc_post_edit($post_id);
		$data['post_key'] = "watch";	
		$data['post'] = $this->watches->get_specific_data($post_id);
		$this->load->view('watch/watch_edit_v', $data);
	}
        
        private function &_proc_post_add() {
		$this->load->library('upload');
		
		$this->form_validation->set_rules('uid', 'User ID', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('cid', 'Car ID', 'integer|trim|required|xss_clean');                                
		
		$qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{
					
			$tbl_name = "watch_cars";
			$new_idx = $this->watches->get_next_insert_idx($tbl_name);
		
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
				
					
                                $qry = array_merge(	
                                        $qry,
                                        array(
                                                'id'		=> $new_idx,						
                                                'uid'  => $this->input->post('uid'),
                                                'cid'		=> $this->input->post('cid'),							
                                               
                                        )
                                );

                                if($this->db->insert($tbl_name, $qry)){
                                //	$data['show_message'] = "Successfully added!";
                                        redirect("admin/watch");
                                }
				
			}			
		}//end run
		
		return $data;
        }
        
        private function &_proc_post_edit($new_idx) { 
    
       		$this->load->library('upload');
    	             
                $this->form_validation->set_rules('uid', 'User ID', 'trim|required|integer');
                $this->form_validation->set_rules('cid', 'Car ID', 'trim|required|integer');
            
                $qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{	
                    
                                                
			$tbl_name = "watch_cars";	
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
				
				$qry = array_merge(	
					$qry,
					array(
						'id'		=> $new_idx,
						'cid'  => $this->input->post('cid'),
						'uid'		=> $this->input->post('uid'),						
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
            $strSql = "DELETE FROM watch_cars WHERE id='$idx' ";
            $this->db->query($strSql);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


//Watch list completed