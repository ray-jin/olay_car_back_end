<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->model('comments');
                $this->load->helper('url');
                $this->load->library('form_validation');
                
	}
	
        function _remap($method) {
		$this->load->view('header_v');
		$this->{$method}();
		$this->load->view('footer_v');
	}
        
	function index()
	{
		$start_no = empty($_REQUEST['per_page'])? 0:$_REQUEST['per_page'];		
		$per_page = $this->config->item('max_count_per_page');

		$result = $this->comments->get_object_list($start_no,$per_page);
		$total_page = $result['total'];
		$data['comment_list'] = $result['rows'];
		
		$base_url = site_url("backend/bcomment?a=1");
		$data['pagenation'] = $this->comments->_create_pagenation($per_page, $total_page, $base_url);
		$data['post_key'] = "bcomment";
		$this->load->view('bcomment/bcomment_list_v',$data);	
	}
        
        function bcomment_del() {
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select task!";
			return;
		}
		$this->_proc_post_del($post_id);
		
		redirect("backend\bcomment");
	}
	
        function bcomment_add() {
		$data = $this->_proc_post_add();
		$data['post_key'] = "bcomment";
		$this->load->view('bcomment/bcomment_add_v', $data);
	}
        
       function bcomment_edit() {		
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select comment!";
			return;
		}
		
		$data = $this->_proc_post_edit($post_id);
		$data['post_key'] = "bcomment";	
		$data['post'] = $this->comments->get_specific_data($post_id);
		$this->load->view('bcomment/bcomment_edit_v', $data);
	}
        
        private function &_proc_post_add() {
		$this->load->library('upload');
		
		$this->form_validation->set_rules('uid', 'User ID', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('cid', 'Car ID', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('comment', 'Comment', 'trim|required|xss_clean');
                
		
		$qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{
					
			$tbl_name = "comment_cars";
			$new_idx = $this->comments->get_next_insert_idx($tbl_name);
		
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
				
					
                                $qry = array_merge(	
                                        $qry,
                                        array(
                                                'id'		=> $new_idx,						
                                                'uid'  => $this->input->post('uid'),
                                                'cid'		=> $this->input->post('cid'),							
                                                'comment'		=> $this->input->post('comment')							
                                        )
                                );

                                if($this->db->insert($tbl_name, $qry)){
                                //	$data['show_message'] = "Successfully added!";
                                        redirect("backend/bcomment");
                                }
				
			}			
		}//end run
		
		return $data;
        }
        
        private function &_proc_post_edit($new_idx) { 
    
       		$this->load->library('upload');
    	             
                $this->form_validation->set_rules('uid', 'User ID', 'trim|required|integer');
                $this->form_validation->set_rules('cid', 'Car ID', 'trim|required|integer');
                //$this->form_validation->set_rules('uid', 'User ID', 'trim|required|integer');
                //$this->form_validation->set_rules('fpassword', 'Password', 'trim');

                $qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{	
                    
                                                
			$tbl_name = "comment_cars";	
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
				

                                
				$qry = array_merge(	
					$qry,
					array(
						'id'		=> $new_idx,
						'cid'  => $this->input->post('cid'),
						'uid'		=> $this->input->post('uid'),
						'comment'	=> $this->input->post('comment')						
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
            $strSql = "DELETE FROM comment_cars WHERE id='$idx' ";
            $this->db->query($strSql);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


//Watch list completed