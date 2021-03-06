<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Users extends CI_Model
{
	private $table_name			= 'users';			// user accounts
	private $profile_table_name	= 'user_profiles';	// user profiles

	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
		$this->table_name			= $ci->config->item('db_table_prefix', 'tank_auth').$this->table_name;
		$this->profile_table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->profile_table_name;
	}

	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function get_user_by_id($user_id, $activated=1)
	{
		$this->db->where('id', $user_id);
                if ($activated)
                    $this->db->where('activated', $activated ? 1 : 0);

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
        //*****Search*************
        function &get_object_list( $banned=-1, $start=0, $count=1000, $s_username) {

		$strSql = "SELECT COUNT(*) AS cnt FROM users WHERE 1=1 and lower(username) like lower('%$s_username%') ";
		$query = $this->db->query($strSql);		
		$row = $query->row_array();
		$return_arr['total'] = $row['cnt'];
		
                $strSql = "SELECT * FROM users WHERE 1=1 and lower(username) like lower('%$s_username%') ORDER BY id  LIMIT $start, $count";
		$query = $this->db->query($strSql);
		$return_arr['rows'] = $query->result_array();
		
		return $return_arr;
	}
        
           //*****Search*************
        function &get_user_profile_list( $banned=-1, $start=0, $count=1000, $s_fullname) {

		$strSql = "SELECT COUNT(*) AS cnt FROM users WHERE 1=1 and username like '%$s_fullname%' ";
		$query = $this->db->query($strSql);		
		$row = $query->row_array();
		$return_arr['total'] = $row['cnt'];
		                
                $strSql="SELECT u.* FROM users u, user_profiles up WHERE u.`user_profile_id`=up.`id` AND ".
                    "LOWER(up.`ufuname`) LIKE LOWER('%$s_fullname%') ORDER BY u.id LIMIT $start, $count";
		$query = $this->db->query($strSql);
		$return_arr['rows'] = $query->result_array();
		
		return $return_arr;
	}

	/**
	 * Get user record by login (username or email)
	 *
	 * @param	string
	 * @return	object
	 */ 
	function get_user_by_login($login)
	{
		$this->db->where('LOWER(username)=', strtolower($login));
		$this->db->or_where('LOWER(email)=', strtolower($login));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by username
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_username($username)
	{
            
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
        
        /**
	 * Get user record by face book name
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_fname($fname)
	{
            
		$this->db->where('LOWER(fname)=', strtolower($fname));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by email
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_email($email)
	{
            
            $this->db->where('LOWER(email)=', strtolower($email));

            $query = $this->db->get($this->table_name);
            if ($query->num_rows() == 1) return $query->row();
            return NULL;
        }

	/**
	 * Check if username available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Check if email available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(email)=', strtolower($email));
		$this->db->or_where('LOWER(new_email)=', strtolower($email));

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}
        
        /**
	 * Check if FaceBook ID available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_fname_available($fname)
	{
            if ($fname=="")
                return true;
            $this->db->select('1', FALSE);
            $this->db->where('LOWER(fname)=', strtolower($fname));
            
            $query = $this->db->get($this->table_name);
            return $query->num_rows() == 0;
	}

	/**
	 * Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function create_user($data, $activated = TRUE)
	{
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = $activated ? 1 : 0;

		if ($this->db->insert($this->table_name, $data)) {
			$user_id = $this->db->insert_id();
			if ($activated)	$this->create_profile($user_id,"","","","","");
			return array('user_id' => $user_id);
		}
		return NULL;
	}

	/**
	 * Activate user if activation key is valid.
	 * Can be called for not activated users only.
	 *
	 * @param	int
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
/*	function activate_user($user_id, $activation_key, $activate_by_email)
	{
		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		if ($activate_by_email) {
			$this->db->where('new_email_key', $activation_key);
		} else {
			$this->db->where('new_password_key', $activation_key);
		}
		$this->db->where('activated', 0);
		$query = $this->db->get($this->table_name);

		if ($query->num_rows() == 1) {

			$this->db->set('activated', 1);
			$this->db->set('new_email_key', NULL);
			$this->db->where('id', $user_id);
			$this->db->update($this->table_name);

			$this->create_profile($user_id);
			return TRUE;
		}
		return FALSE;
	}*/

	/**
	 * Purge table of non-activated users
	 *
	 * @param	int
	 * @return	void
	 */
	function purge_na($expire_period = 172800)
	{
		$this->db->where('activated', 0);
		$this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
		$this->db->delete($this->table_name);
	}

	/**
	 * Delete user record
	 *
	 * @param	int
	 * @return	bool
	 */
	function delete_user($user_id)
	{
            
           $this->delete_profile($user_id); 
            if ($this->db->affected_rows() > 0) {
                     $this->db->where('id', $user_id);
                    $this->db->delete($this->table_name);
                    return TRUE;
            }
            
            return FALSE;
	}

	/**
	 * Set new password key for user.
	 * This key can be used for authentication when resetting user's password.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function set_password_key($user_id, $new_pass_key)
	{
		$this->db->set('new_password_key', $new_pass_key);
		$this->db->set('new_password_requested', date('Y-m-d H:i:s'));
		$this->db->where('id', $user_id);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	function can_reset_password($user_id, $new_pass_key, $expire_period = 900)
	{
		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		$this->db->where('new_password_key', $new_pass_key);
		$this->db->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expire_period);

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 1;
	}

	/**
	 * Change user password if password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	bool
	 */
	function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900)
	{
		$this->db->set('password', $new_pass);
		$this->db->set('new_password_key', NULL);
		$this->db->set('new_password_requested', NULL);
		$this->db->where('id', $user_id);
		$this->db->where('new_password_key', $new_pass_key);
		$this->db->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expire_period);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Change user password
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function change_password($user_id, $new_pass)
	{
		$this->db->set('password', $new_pass);
		$this->db->where('id', $user_id);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Set new email for user (may be activated or not).
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function set_new_email($user_id, $new_email, $new_email_key, $activated)
	{
		$this->db->set($activated ? 'new_email' : 'email', $new_email);
		$this->db->set('new_email_key', $new_email_key);
		$this->db->where('id', $user_id);
		$this->db->where('activated', $activated ? 1 : 0);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Activate new email (replace old email with new one) if activation key is valid.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function activate_new_email($user_id, $new_email_key)
	{
		$this->db->set('email', 'new_email', FALSE);
		$this->db->set('new_email', NULL);
		$this->db->set('new_email_key', NULL);
		$this->db->where('id', $user_id);
		$this->db->where('new_email_key', $new_email_key);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_login_info($user_id, $record_ip, $record_time)
	{
		$this->db->set('new_password_key', NULL);
		$this->db->set('new_password_requested', NULL);

		if ($record_ip)		$this->db->set('last_ip', $this->input->ip_address());
		if ($record_time)	$this->db->set('last_login', date('Y-m-d H:i:s'));

		$this->db->where('id', $user_id);
		$this->db->update($this->table_name);
	}
        
        /**
	 * Update user facebook info
	 *
	 * @param	int
	 * @param	string	 
	 * @return	void
	 */
	function update_fb_info($user_id, $fb)
	{
		$this->db->set('fname', $fb);
				
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name);
	}
	
     
        /**
	 * Update user facebook info
	 *
	 * @param	string
	 * @param	string	 
         * @param	string	 
         * @param	string	 
         * @param	string
         * @param	img_loc	 
	 * @return	void
	 */
        
	function update_user_profile($uid,$ufuname,$c_car,$a_me,$loc,$img_loc,$username,$img_del=true)
	{
            if (!$this->get_profile_by_userid($uid)){
                $profile_id=$this->create_profile ($uid,$ufuname,$img_loc,$c_car,$a_me,$loc); // create profile                                    
                if ($profile_id>0){
                    $this->db->set('user_profile_id', $profile_id);  // update user with profile id
                    $this->db->where('id', $uid);
                    $this->db->update($this->table_name);
                    return true;
                }
                else{
                    return false;
                }
                
                
            }
             
            $aprofile=$this->get_profile_by_userid($uid);
            
            $pfid=$aprofile->id; //get profileid;
            
            $this->db->set('user_id', $uid);
            $this->db->set('ufuname', $ufuname);
            
            //check whether delete the image file or not
            
            
            if ($img_loc!=""){
                if ($img_del==true && $aprofile->image_loc){ 
                 $tmp=UPLOAD_PATH."/".$aprofile->image_loc;
                    if (file_exists($tmp))
                        unlink($tmp); //delete physical image file

                }
                $this->db->set('image_loc', $img_loc);
            }
                
            $this->db->set('current_car', $c_car);
            $this->db->set('about_me', $a_me);
            $this->db->set('loc', $loc);

            $this->db->where('id', $pfid);
            $this->db->update($this->profile_table_name);
            return true;
	}
        /**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_session_info($user_id, $session_id,$d_token)
	{
		$this->db->set('session_id', $session_id);
                $this->db->set('device_token', $d_token);
		
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name);
	}
        
       
        
	/**
	 * Ban user
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function ban_user($user_id, $reason = NULL)
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name, array(
			'banned'		=> 1,
			'ban_reason'	=> $reason,
		));
	}

	/**
	 * Unban user
	 *
	 * @param	int
	 * @return	void
	 */
	function unban_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name, array(
			'banned'		=> 0,
			'ban_reason'	=> NULL,
		));
	}

	/**
	 * Create an empty profile for a new user
	 *
	 * @param	int
	 * @return	bool
	 */
	 public function create_profile($user_id,$ufuname,$img_loc,$c_car,$a_me,$loc)
	{
            $this->db->set('user_id', $user_id);
            $this->db->set('ufuname', $ufuname);
            $this->db->set('image_loc', $img_loc);
            $this->db->set('current_car', $c_car);
            $this->db->set('about_me', $a_me);
            $this->db->set('loc', $loc);
            $this->db->set('user_id', $user_id);
            
            if ($this->db->insert($this->profile_table_name)) {
                    $profile_id = $this->db->insert_id();
                    return $profile_id;
            }
                
            return 0;
	}

        /**
	 * Find profile by userid
	 *
	 * @param	int
	 * @return	bool
	 */
	 public function get_profile_by_userid($uid)
	{		
                $this->db->where('user_id', $uid);	
		$query = $this->db->get($this->profile_table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
                
	}
	/**
	 * Delete user profile
	 *
	 * @param	int
	 * @return	void
	 */
	private function delete_profile($user_id)
	{   
            $user=$this->get_user_by_id($user_id);
            
            $user_profile=$this->get_profile_by_userid($user_id);
            
            $this->load->helper("file"); // load the helper
            delete_files(UPLOAD_PATH."/".$user->username, true);
            rmdir(UPLOAD_PATH."/".$user->username);
            $this->db->where('user_id', $user_id);
            $this->db->delete($this->profile_table_name);
	}
        
        
}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */