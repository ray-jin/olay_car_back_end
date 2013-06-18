<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Saved_search extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->model('saved_searches');
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
            
                $data['userid'] = isset($_REQUEST['userid']) ? trim($_REQUEST['userid']) : "" ;
                $result = $this->saved_searches->list_saved_searches($start_no,$per_page,$data['userid'] );
		$total_page = $result['total'];
		$data['saved_search_list'] = $result['rows'];
		                
		$base_url = site_url("admin/saved_search?a=1")."&userid=".$data['userid'];
		$data['pagenation'] = $this->saved_searches->_create_pagenation($per_page, $total_page, $base_url);
		$data['post_key'] = "saved_search";
                $data['start_no'] =$start_no;
		$this->load->view('saved_search/saved_search_list_v',$data);	
	}
        
        function saved_search_del() {
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select task!";
			return;
		}
		$this->_proc_post_del($post_id);
		
		redirect("admin\saved_search");
	}
	
        function saved_search_add() {
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$data = $this->_proc_post_add();
		$data['post_key'] = "saved_search";
		$this->load->view('saved_search/saved_search_add_v', $data);
	}
        
       function saved_search_edit() {	
            if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
            
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select saved_search!";
			return;
		}
		
		$data = $this->_proc_post_edit($post_id);
		$data['post_key'] = "saved_search";	
		$data['post'] = $this->saved_searches->get_specific_data($post_id);
		$this->load->view('saved_search/saved_search_edit_v', $data);
	}
        
        private function &_proc_post_add() {
				
		$this->form_validation->set_rules('uid', 'User Id', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('search_string', 'Search String', 'trim|required');
		
		$qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{
					
			$tbl_name = "saved_searches";
			$new_idx = $this->saved_searches->get_next_insert_idx($tbl_name);
		
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
			
                                $qry = array_merge(	
                                        $qry,
                                        array(
                                                'id'		=> $new_idx,						
                                                'uid'  => $this->input->post('uid'),
                                                'search_string'	=> $this->input->post('search_string'),                                                
                                        )
                                );

                                if($this->db->insert($tbl_name, $qry)){
                                //	$data['show_message'] = "Successfully added!";
                                        redirect("admin/saved_search");
                                }

			}			
		}//end run
		
		return $data;
        }
        
        private function &_proc_post_edit($new_idx) { 
        	             
               $this->form_validation->set_rules('uid', 'User Id', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('search_string', 'Search String', 'trim|required');
                
                $qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{	
                
                    $tbl_name = "saved_searches";	
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
				
				$qry = array_merge(	
					$qry,
					array(						
						'uid'  => $this->input->post('uid'),
						'search_string' => $this->input->post('search_string'),
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
            $strSql = "DELETE FROM saved_searches WHERE id='$idx' ";
            $this->db->query($strSql);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


//Watch list completed