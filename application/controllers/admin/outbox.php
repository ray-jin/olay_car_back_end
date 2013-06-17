<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class outbox extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->model('messages');
                $this->load->model('manage_m'); // a little different
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
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$start_no = empty($_REQUEST['per_page'])? 0:$_REQUEST['per_page'];		
		$per_page = $this->config->item('max_count_per_page');

                $data['sender_name'] = isset($_REQUEST['sender_name']) ? trim($_REQUEST['sender_name']) : "" ;
                $data['receiver_name'] = isset($_REQUEST['receiver_name']) ? trim($_REQUEST['receiver_name']) : "" ;
                                
		//$result = $this->messages->list_messages(0,1000,$data['sender_name'] ,$data['receiver_name']);                
                $result = $this->messages->list_messages($start_no,$per_page,$data['sender_name'],$data['receiver_name'],"outbox" );
                
		$total_page = $result['total'];
		$data['message_list'] = $result['rows'];
		
		$base_url = site_url("admin/outbox?a=1")."&sender_name=".$data['sender_name']."&receiver_name=". $data['receiver_name'];
		$data['pagenation'] = $this->messages->_create_pagenation($per_page, $total_page, $base_url);
		$data['post_key'] = "outbox";
                $data['start_no'] =$start_no;
		$this->load->view('outbox/outbox_list_v',$data);	
	}
        
        function outbox_del() {
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select task!";
			return;
		}
		$this->_proc_post_del($post_id);
		
		redirect("admin\outbox");
	}
	
        function outbox_add() {
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$data = $this->_proc_post_add();
		$data['post_key'] = "outbox";
		$this->load->view('outbox/outbox_add_v', $data);
	}
        
       function outbox_edit() {
            if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select message!";
			return;
		}
		
		$data = $this->_proc_post_edit($post_id);
		$data['post_key'] = "outbox";	
		$data['post'] = $this->messages->get_specific_data($post_id,"outbox");
		$this->load->view('outbox/outbox_edit_v', $data);
	}
        
        private function &_proc_post_add() {
		$this->load->library('upload');
		
		$this->form_validation->set_rules('sender_id', 'Sender ID', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('receiver_id', 'Receiver ID', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('message', 'message', 'trim|required|xss_clean');                
		
		$qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{
					
			$tbl_name = "outbox";
			$new_idx = $this->messages->get_next_insert_idx($tbl_name);
		
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
			
                                $qry = array_merge(	
                                        $qry,
                                        array(
                                                'id'		=> $new_idx,						
                                                'sender_id'  => $this->input->post('sender_id'),
                                                'receiver_id'	=> $this->input->post('receiver_id'),                                                
                                                'message'=> $this->input->post('message'),
                                                'created' =>  date('Y-m-d H:i:s')
                                        )
                                );

                                if($this->db->insert($tbl_name, $qry)){
                                //	$data['show_message'] = "Successfully added!";
                                        redirect("admin/outbox");
                                }

			}			
		}//end run
		
		return $data;
        }
        
        private function &_proc_post_edit($new_idx) { 
    
       		$this->load->library('upload');
    	             
                $this->form_validation->set_rules('sender_id', 'Sender ID', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('receiver_id', 'Receiver ID', 'integer|trim|required|xss_clean');
                $this->form_validation->set_rules('message', 'message', 'trim|required|xss_clean');
                
                

                $qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{	
                
                    $tbl_name = "outbox";	
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
				

                                
				$qry = array_merge(	
					$qry,
					array(
						'id'		=> $new_idx,
						'sender_id'  => $this->input->post('sender_id'),
						'receiver_id'=> $this->input->post('receiver_id'),
						'message'	=> $this->input->post('message')
					)
				);
				
				$this->db->where('id', $new_idx);
				$this->db->update("outbox", $qry);
				
				$data['show_message'] = "Successfully updated!";

			}			
		}//end run	
			
		return $data;	
		
    } //end function
    
    private function _proc_post_del($idx) {    	
            $strSql = "DELETE FROM outbox WHERE id='$idx' ";
            $this->db->query($strSql);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


//Watch list completed