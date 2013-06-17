<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_profile extends CI_Controller
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

                $data['s_fullname'] = isset($_REQUEST['s_fullname']) ? trim($_REQUEST['s_fullname']) : "" ;
		$result = $this->users->get_user_profile_list(-1,$start_no,$per_page,$data['s_fullname']);
		
		$total_page = $result['total'];
		$data['user_list'] = $result['rows'];
		
		$base_url = site_url("user?a=1")."&s_fullname=".$data['s_fullname'];
		$data['pagenation'] = $this->manage_m->_create_pagenation($per_page, $total_page, $base_url);
		$data['post_key'] = "user_profile";
		$this->load->view('user_profile/user_profile_list_v',$data);	
	}
        
	function user_profile_add() {
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
            $data = $this->_proc_post_add();
            $data['post_key'] = "user";
            $this->load->view('user_profile/user_profile_add_v', $data);
	}
        
	function user_profile_edit() {	
             if(!$this->tank_auth->is_logged_in()) {
                redirect("auth/login");
            }
            $post_id = $this->uri->segment(4, 0);
            if (empty($post_id)) {
                    echo "select task!";
                    return;
            }
            $data = $this->_proc_post_edit($post_id);
            $data['post_key'] = "user";	
            $data['post'] = $this->manage_m->get_specific_data($post_id, "users");
            $this->load->view('user_profile/user_profile_edit_v', $data);
                
	}
		
	private function &_proc_post_add() {
		$this->load->library('upload');
		
		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required|xss_clean|integer');
                $this->form_validation->set_rules('ufuname', 'Fullname', 'trim');
		$this->form_validation->set_rules('current_car', 'Current Car', 'trim');
		$this->form_validation->set_rules('about_me', 'About me', 'trim');
                $this->form_validation->set_rules('loc', 'Postcode', 'trim|min_length[2]|max_length[4]');
		
		$qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{		
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
                        
                            if( !$this->users->get_user_by_id($this->input->post('user_id'))) 
                            {
                                $data['show_errors'] = "No such user";
                                return $data;
                            }
                            if( $this->users->get_profile_by_userid($this->input->post('user_id'))) 
                            {
                                $data['show_errors'] = "Profile already exist";
                                return $data;
                            }

                            $tbl_name = "user_profiles";
                        
                            if ($this->users->update_user_profile($this->input->post('user_id'),$this->input->post('ufuname'),
                                    $this->input->post('current_car'),$this->input->post('about_me'),$this->input->post('loc'),"","",false)){
//function update_user_profile($uid,$ufuname,$c_car,$a_me,$loc,$img_loc,$username,$img_del=true),                            
                           	redirect("admin/user_profile");
                            }
                            else{
                                $data['show_errors'] = "Error occured";
                            }
				
			}			
		}//end run
		
		return $data;
    }
    
    private function &_proc_post_edit($new_idx) { 
        $user=$this->manage_m->get_specific_data($new_idx, "users");
        $new_idx=$user['user_profile_id'];
    	$this->load->library('upload');
    	
        $this->form_validation->set_rules('ufuname', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('current_car', 'Current Car', 'trim');
        $this->form_validation->set_rules('about_me', 'About me', 'trim');
        $this->form_validation->set_rules('loc', 'Postcode', 'trim|min_length[2]|max_length[4]');

		$qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{	
                    $suffix = " and id != ".$this->input->post('fid');
                    $tbl_name = "user_profiles";
                    if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
                      
                                $this->upload->set_allowed_types('gif|jpg|png');
                                $this->upload->set_upload_path(UPLOAD_PATH);
                                $this->upload->set_max_filesize($this->config->item('max_img_size'));
                            //    if($auser)
                              //      $config['upload_path'].="\\".$auser->username;
                                $img_loc=$this->input->post('img_loc');
                                $img_del=false;
                                if ( $this->upload->do_user_profile_upload($user['username'],'profile_img'))
                                {
                                     $img_loc=$user['username']."/profile/".$this->upload->file_name;
                                     $img_del=true;
                                }                      
				$this->users->update_user_profile($user['id'],$this->input->post('ufuname'),$this->input->post('current_car'),
                                        $this->input->post('about_me'),$this->input->post('loc'),$img_loc,$user['username'],$img_del);
				$data['show_message'] = "Successfully updated!";

			}			
		}//end run	
			
		return $data;
    } //end function
 
 }
?>
