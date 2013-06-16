<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Car extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->model('cars'); // a little different
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

         //   $result = $this->cars->get_object_list($start_no,$per_page);
            
         //   $total_page = $result['total'];
          //  $data['car_list'] = $result['rows'];
           
		
            $base_url = site_url("admin/car?a=1");
          //  $data['pagenation'] = $this->cars->_create_pagenation($per_page, $total_page, $base_url);
            $data['post_key'] = "car";
         //   $data['start_no'] =$start_no;
            $this->load->view('car/car_list_v',$data);	
                
	}
        
        function car_del() {
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select task!";
			return;
		}
		$this->cars->delete_car($post_id);
		redirect("admin\car");                
            
        }
	
        function car_add() {
            $data = $this->_proc_post_add();
            $data['post_key'] = "car";
            $this->load->view('car/car_add_v', $data);
	}
        
       function car_edit() {		
		$post_id = $this->uri->segment(4, 0);
		if (empty($post_id)) {
			echo "select car!";
			return;
		}
		
		$data = $this->_proc_post_edit($post_id);
		$data['post_key'] = "car";	
		$data['post'] = $this->cars->get_specific_data($post_id);
		$this->load->view('car/car_edit_v', $data);
	}
        
        private function &_proc_post_add() {
		$this->load->library('upload');
		
                $this->form_validation->set_rules('registration', 'Registration', 'trim|required|xss_clean');
                $this->form_validation->set_rules('price', 'price', 'is_natural|required|numeric|trim|xss_clean');
                
		$qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{
			$tbl_name = "cars";
			$new_idx = $this->manage_m->get_next_insert_idx($tbl_name);
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
                                 
                                $this->upload->set_allowed_types('gif|jpg|png');
                                $this->upload->set_upload_path(UPLOAD_PATH);
                                $this->upload->set_max_filesize($this->config->item('max_img_size'));
                                                              
                                $auser= $this->users->get_user_by_id(ADMIN_USER_ID, true);
                                
                                $fnames=$this->upload->do_multi_upload_car($auser->username,$new_idx);
                                if (is_array($fnames))
                                {
                                    $registration=$_POST['registration'];
                                    $price=$_POST['price'];                 

                                    if ($this->cars->create_car($auser->id,$registration,$price,$fnames,$auser->username,$new_idx)!=-1){
                                        redirect("admin/car");
                                    }
                                    else{
                                        $data['show_errors'] = "Error occured";
                                    }
                                }
                                
                                
                                
                                
			}			
		}//end run
		
		return $data;
        }
        
        private function &_proc_post_edit($new_idx) { 
    
       		$this->load->library('upload');
    	        
                $this->form_validation->set_rules('price', 'Price', 'trim|numeric|required');
                $this->form_validation->set_rules('registration', 'Registration', 'trim|required');
                $this->form_validation->set_rules('make', 'Make', 'trim');
                $this->form_validation->set_rules('model', 'Model', 'trim');
                $this->form_validation->set_rules('year', 'Year', 'trim|integer');
                $this->form_validation->set_rules('fuel_type', 'Fuel Type', 'trim');
                $this->form_validation->set_rules('transmission', 'Transmission', 'trim');
                $this->form_validation->set_rules('mileage', 'Mileage', 'trim|integer|greater_than[-1]');
                $this->form_validation->set_rules('make', 'Make', 'trim');
                $this->form_validation->set_rules('desc', 'Description', 'trim');

                $qry = array();
		$data = array();
		
		if ($this->form_validation->run())
		{	
                                      
			$tbl_name = "cars";	
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
                                $auser= $this->users->get_user_by_id(ADMIN_USER_ID, true);
                                                  
                                $this->upload->set_allowed_types('*');
                                $this->upload->set_upload_path(UPLOAD_PATH);
                                $this->upload->set_max_filesize($this->config->item('max_img_size'));
				//$fnames=$this->upload->do_multi_upload_car($auser->username,$this->input->post('id'));
                                $fnames=array("","","","","","","");
                                $img_del=array();
                                
                                if ( $this->upload->do_car_file_upload($auser->username,$this->input->post('id'),"file_url_1"))
                                {
                                     $fnames[1]=$this->upload->file_name;
                                     $img_del[1]=true;
                                }  
                                if ( $this->upload->do_car_file_upload($auser->username,$this->input->post('id'),"file_url_2"))
                                {
                                     $fnames[2]=$this->upload->file_name;
                                     $img_del[2]=true;
                                }
                                if ( $this->upload->do_car_file_upload($auser->username,$this->input->post('id'),"file_url_3"))
                                {
                                     $fnames[3]=$this->upload->file_name;
                                 
                                }
                                if ( $this->upload->do_car_file_upload($auser->username,$this->input->post('id'),"file_url_4"))
                                {
                                     $fnames[4]=$this->upload->file_name;
                                 
                                }
                                if ( $this->upload->do_car_file_upload($auser->username,$this->input->post('id'),"file_url_5"))
                                {
                                     $fnames[5]=$this->upload->file_name;
                                 
                                }
                                if (is_array($fnames)){                               
                                    if ($this->cars->update_car2($new_idx,$this->input->post('make')
                                            ,$this->input->post('model'),$this->input->post('year'),
                                            $this->input->post('fuel_type'),$this->input->post('transmission'),
                                            $this->input->post('mileage'), $this->input->post('desc'), $fnames,$auser->username,$this->input->post('activated'))){
                                        //redirect("admin/car");                                                
                                        $data['show_message'] = "Successfully updated!";
                                    }
                                    else{
                                        $data['show_errors'] = "File upload error occured";
                                    }
                                }
                                else{
                                    $data['show_errors'] = "Error occured";
                                }
                                
                                
				/*$qry = array_merge(	
					$qry,
					array(
				//		'id'		=> $new_idx,						
                                             //   'uid'  => $this->input->post('uid'),
                                                'activated' => $this->input->post('activated'),
                                                'registration'	=> $this->input->post('registration'),
                                                'price'		=> $this->input->post('price'),
                                                'make'		=> $this->input->post('make'),
                                                'model'		=> $this->input->post('model'),
                                                'year'	=> $this->input->post('year'),
                                                'fuel_type'	=> $this->input->post('fuel_type'),
                                                'transmission'	=> $this->input->post('transmission'),
                                                'mileage'	=> $this->input->post('mileage'),
                                                'postcode'	=> $this->input->post('postcode'),
                                               /* 'bodytype'	=> $this->input->post('bodytype'),
                                                'colour'	=> $this->input->post('colour'),
                                                'country'	=> $this->input->post('country'),
                                                'currency'	=> $this->input->post('currency'),
                                                'website'	=> $this->input->post('website'),
                                                'desc'		=> $this->input->post('desc'),
                                                'engine_size'	=> $this->input->post('engine_size'),                                                
                                                'gearbox'	=> $this->input->post('gearbox'),                                                
                                                'mileageunits'	=> $this->input->post('mileageunits'),                                                
                                                'num_doors'	=> $this->input->post('num_doors'),
                                                'num_owners'	=> $this->input->post('num_owners'),
                                                'car_options'	=> $this->input->post('car_options'),                                                                                                
                                                'purchase_type'	=> $this->input->post('purchase_type'),
                                                'region'	=> $this->input->post('region'),                                                
                                                'roadtax'	=> $this->input->post('roadtax'),
                                                'sales_type'	=> $this->input->post('sales_type'),
                                                'tested_until'	=> $this->input->post('tested_until'),
                                                'trade_price'	=> $this->input->post('trade_price'),
                                                'vin_number'	=> $this->input->post('vin_number'),
                                                'warranty'	=> $this->input->post('warranty'),
                                                
                                                'modified'	=> date('Y-m-d H:i:s'),						
					)
				);*/
				
			}			
		}//end run	
			
		return $data;	
		
    } //end function
    
    private function _proc_post_del($idx) {    	
            $strSql = "DELETE FROM cars WHERE id='$idx' ";
            $this->db->query($strSql);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


//Watch list completed