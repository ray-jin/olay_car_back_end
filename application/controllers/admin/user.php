<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->helper('form');
		
		$this->load->config('tank_auth', TRUE);
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		
		$this->load->library('form_validation');

		if (!$this->tank_auth->is_logged_in())
		{
			redirect('/auth/login/');
		}
		$this->load->model('manage_m');
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

                $data['s_username'] = isset($_REQUEST['s_username']) ? trim($_REQUEST['s_username']) : "" ;
		$result = $this->users->get_object_list(-1,$start_no,$per_page,$data['s_username']);
		$total_page = $result['total'];
		$data['user_list'] = $result['rows'];
		
		$base_url = site_url("user?a=1");
		$data['pagenation'] = $this->manage_m->_create_pagenation($per_page, $total_page, $base_url);
		$data['post_key'] = "user";
               
                
		$this->load->view('user/user_list_v',$data);	
	}
	
	function user_add() {
		$data = $this->_proc_post_add();
		$data['post_key'] = "user";
		$this->load->view('user/user_add_v', $data);
	}
		
	function user_edit() {		
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select task!";
			return;
		}
		
		$data = $this->_proc_post_edit($post_id);
		$data['post_key'] = "user";	
		$data['post'] = $this->manage_m->get_specific_data($post_id, "users");
		$this->load->view('user/user_edit_v', $data);
	}
		
	function user_del() {
            $post_id = $this->uri->segment(4, 0);
            if (empty($post_id)) {
                    echo "select task!";
                    return;
            }
            $user=$this->users->get_user_by_id($post_id);
            if ($user->level==0){
                $this->users->delete_user($post_id);
            }
            else{
                echo "You can not delete adminstrator!";
                 return;
            }

            redirect("admin/user");
	}
	
	private function &_proc_post_add() {
		$this->load->library('upload');
		
		$this->form_validation->set_rules('femail', 'Email', 'trim|required|valid_email|xss_clean');
                $this->form_validation->set_rules('fusername', 'UserName', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fpassword', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fconfirm', 'Confirm', 'trim|required|xss_clean');
		
		$qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{
			if( !$this->manage_m->confrim_email($this->input->post('femail')) )
			{
				$data['show_errors'] = $this->config->item('duplicate_mail');
				return $data;
			}
			
			$tbl_name = "users";
			$new_idx = $this->manage_m->get_next_insert_idx($tbl_name);
		
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
				
				if ( $this->input->post('fpassword') != $this->input->post('fconfirm') ) {
					$data['show_errors'] = "Invalid Password";
				} else {
					
					$hasher = new PasswordHash($this->config->item('phpass_hash_strength', 'tank_auth'), $this->config->item('phpass_hash_portable', 'tank_auth') );
					$hashed_password = $hasher->HashPassword($this->input->post('fpassword'));				//}
					
					$qry = array_merge(	
						$qry,
						array(
							'id'		=> $new_idx,						
							'username'  => $this->input->post('fusername'),
							'email'		=> $this->input->post('femail'),
							'password'	=> $hashed_password,
							'phone'		=> $this->input->post('fphone'),							
						)
					);
					
					if($this->db->insert($tbl_name, $qry)){
					//	$data['show_message'] = "Successfully added!";
						redirect("admin/user");
					}
				}
			}			
		}//end run
		
		return $data;
    }
    
    private function &_proc_post_edit($new_idx) { 
    	$this->load->library('upload');
    	             
        $this->form_validation->set_rules('femail', 'Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('fusername', 'UserName', 'trim|required|xss_clean');            
        $this->form_validation->set_rules('fpassword', 'Password', 'trim|required');
	$this->form_validation->set_rules('cfpassword', 'Confirm Password', 'trim|required');	
		$qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{	
                    $suffix = " and id != ".$this->input->post('fid');
                            
                    if( !$this->manage_m->confrim_email($this->input->post('femail'),$suffix ))
                    {
                            $data['show_errors'] = $this->config->item('duplicate_mail');
                            return $data;
                    }
                    if( $this->input->post('fpassword')!=$this->input->post('cfpassword'))
                    {
                            $data['show_errors'] = "Passwords should be match";
                            return $data;
                    }
			$tbl_name = "users";	
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
				//if (strlen(trim($this->input->post('fpassword')))>0) {

				$hasher = new PasswordHash($this->config->item('phpass_hash_strength', 'tank_auth'), $this->config->item('phpass_hash_portable', 'tank_auth') );
				$hashed_password = $hasher->HashPassword($this->input->post('fpassword'));				//}
				//$fbanned = ($this->input->post('fbanned')=="on") ? "1" ; "0";
                                $fbanned="0";
                                if ($this->input->post('fbanned')=="on")
                                    $fbanned="1";
                                
				$qry = array_merge(	
					$qry,
					array(
						'id'		=> $new_idx,						
						'username'  => $this->input->post('fusername'),
						'email'		=> $this->input->post('femail'),
						'password'	=> $hashed_password,
						'phone'		=> $this->input->post('fphone'),
                                                'banned'	=> $fbanned,
						'type'		=> $this->input->post('ftype'),
						'verified_pt' => $this->input->post('fverified'),
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
		$strSql = "DELETE FROM users WHERE id='$idx' ";
		$this->db->query($strSql);		
    }
 }
?>
