<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Make extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->model('makes');
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
            
                $data['name'] = isset($_REQUEST['name']) ? trim($_REQUEST['name']) : "" ;
                $result = $this->makes->list_makes($start_no,$per_page,$data['name'] );
		$total_page = $result['total'];
		$data['make_list'] = $result['rows'];
		                
		$base_url = site_url("admin/make?a=1")."&name=".$data['name'];
		$data['pagenation'] = $this->makes->_create_pagenation($per_page, $total_page, $base_url);
		$data['post_key'] = "make";
                $data['start_no'] =$start_no;
		$this->load->view('make/make_list_v',$data);	
	}
        
        function make_del() {
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select task!";
			return;
		}
		$this->_proc_post_del($post_id);
		
		redirect("admin\make");
	}
	
        function make_add() {
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$data = $this->_proc_post_add();
		$data['post_key'] = "make";
		$this->load->view('make/make_add_v', $data);
	}
        
       function make_edit() {	
            if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
            
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select make!";
			return;
		}
		
		$data = $this->_proc_post_edit($post_id);
		$data['post_key'] = "make";	
		$data['post'] = $this->makes->get_specific_data($post_id);
		$this->load->view('make/make_edit_v', $data);
	}
        
        private function &_proc_post_add() {
				
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
                $this->form_validation->set_rules('desc', 'Description', 'trim');                
		
		$qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{
					
			$tbl_name = "makes";
			$new_idx = $this->makes->get_next_insert_idx($tbl_name);
		
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
			
                                $qry = array_merge(	
                                        $qry,
                                        array(
                                                'id'		=> $new_idx,						
                                                'name'  => $this->input->post('name'),
                                                'desc'	=> $this->input->post('desc'),                                                
                                        )
                                );

                                if($this->db->insert($tbl_name, $qry)){
                                //	$data['show_message'] = "Successfully added!";
                                        redirect("admin/make");
                                }

			}			
		}//end run
		
		return $data;
        }
        
        private function &_proc_post_edit($new_idx) { 
        	             
                $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
                $this->form_validation->set_rules('desc', 'Description', 'trim');             
                
                $qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{	
                
                    $tbl_name = "makes";	
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
				
				$qry = array_merge(	
					$qry,
					array(						
						'name'  => $this->input->post('name'),
						'desc'		=> $this->input->post('desc'),                                                
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
            $strSql = "DELETE FROM makes WHERE id='$idx' ";
            $this->db->query($strSql);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


//Watch list completed