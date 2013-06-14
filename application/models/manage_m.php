<?php
class Manage_m extends CI_Model 
{
	function &get_object_list($tbl_name, $banned=-1, $start=0, $count=1000, $search_option='') {

		$strSql = "SELECT COUNT(*) AS cnt FROM $tbl_name";
		$query = $this->db->query($strSql);		
		$row = $query->row_array();
		$return_arr['total'] = $row['cnt'];
		
		if ($tbl_name != "users")
			$strSql = "SELECT * FROM $tbl_name WHERE 1=1 ORDER BY nID DESC LIMIT $start, $count";
		else
			$strSql = "SELECT * FROM $tbl_name WHERE 1=1 ORDER BY id  LIMIT $start, $count";
		$query = $this->db->query($strSql);
		$return_arr['rows'] = $query->result_array();
		
		return $return_arr;
	}
	
	function &get_specific_data($idx, $tbl_name) {
            
                    $strSql = "SELECT * FROM $tbl_name WHERE id='$idx'";
            $query = $this->db->query($strSql);
            $row = $query->row_array();
            return $row;
	}
	
	function confrim_email( $email, $suffix='') {
            $strSql = "SELECT * FROM users WHERE email='$email'".$suffix;

            $query = $this->db->query($strSql);

            if ($query->num_rows() == 1) return FALSE;
            return TRUE;		
	}
	
	function confirm_barcode( $barcode ) {
		$strSql = "SELECT * FROM product WHERE barcode='$barcode'";
		$query = $this->db->query($strSql);
		
		if ($query->num_rows() == 1) return FALSE;
		return TRUE;
	}
	
	function get_next_insert_idx($tbl_name) {

		$next_increment = 0;
		$strSql = "SHOW TABLE STATUS WHERE Name='$tbl_name'";
		$query = $this->db->query($strSql);
		$row = $query->row_array();
		$next_increment = $row['Auto_increment'];
		
		return $next_increment;
	}
	
	function &_create_pagenation($per_page,$total,$base_url) {
    	
    	$this->load->library('pagination');
		$config['base_url'] = $base_url;
		$config['page_query_string'] = TRUE;
		$config['total_rows'] = $total;
		$config['per_page'] = $per_page;
		$config['full_tag_open'] = "<div style='padding:8px;'>";
		$config['full_tag_close'] = "</div>";
		
		$this->pagination->initialize($config); 
		$pagenation = $this->pagination->create_links();
			
		return $pagenation;
    }
	
	function get_user_info($userID)
    {
		$strSql = "SELECT username,email,password,phone,country,type FROM users WHERE id=".$userID;
		$query = $this->db->query($strSql);
		if ($query->num_rows() == 1) return $query->row();
		
		return NULL;
	}    
	
	function update_user_info($userID, $name, $password, $phone, $country)
	{	
		if ($password != "")
			$strSql = "UPDATE users SET username='$name', password='$password', phone='$phone', 
				country='$country' WHERE id=".$userID;
		else
			$strSql = "UPDATE users SET username='$name', phone='$phone', 
				country='$country' WHERE id=".$userID;
	
		$query = $this->db->query($strSql);
		return TRUE;
	}
	
	function login_user($session_id, $user_id) {
		$strSql = "UPDATE users SET session_id='$session_id' WHERE id=".$user_id;
		$this->db->query($strSql);
	}
	
	function logout($session_id) {
		$this->db->query("DELETE FROM ci_sessions WHERE session_id='$session_id'");
	}
	
	function generate_password($pw_length = 8, $use_caps = true, $use_numeric = true, $use_specials = true) {
		$caps = array();
		$numbers = array();
		$num_specials = 0;
		$reg_length = $pw_length;
		$pws = array();
		for ($ch = 97; $ch <= 122; $ch++) $chars[] = $ch; // create a-z
		if ($use_caps) for ($ca = 65; $ca <= 90; $ca++) $caps[] = $ca; // create A-Z
		if ($use_numeric) for ($nu = 48; $nu <= 57; $nu++) $numbers[] = $nu; // create 0-9
		$all = array_merge($chars, $caps, $numbers);
		if ($use_specials) {
			$reg_length =  ceil($pw_length*0.75);
			$num_specials = $pw_length - $reg_length;
			if ($num_specials > 5) $num_specials = 5;
			for ($si = 33; $si <= 47; $si++) $signs[] = $si;
			$rs_keys = array_rand($signs, $num_specials);
			foreach ($rs_keys as $rs) {
				$pws[] = chr($signs[$rs]);
			}
		}
		$rand_keys = array_rand($all, $reg_length);
		foreach ($rand_keys as $rand) {
			$pw[] = chr($all[$rand]);
		}
		$compl = array_merge($pw, $pws);
		shuffle($compl);
		return implode('', $compl);
	}
	
	function change_password($password, $email) {
		$this->db->query("UPDATE users SET password='$password' WHERE email='$email'");		
	}
	
	function productInfo($barcode) {
		$strSql = "SELECT * FROM product WHERE barcode='$barcode'";
		$query = $this->db->query($strSql);
		
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
	
	function recLocationList($sessionID) {
		$strSql = "SELECT name, latitude as lat, longitude as 'long' FROM recycle_loc";
		$query = $this->db->query($strSql);
		
		if ($query->num_rows() == 0) return NULL;
		return $query->result();
	}
	
	function getUserSettings($userID) {
		$strSql = "SELECT verified_pt, donated_pt, scan_lat, scan_long FROM users WHERE id=".$userID;
		$query = $this->db->query($strSql);
		return $query->result();
	}
	
	function updateScanLoc($userID, $lat, $long) {
		$strSql = "UPDATE users SET scan_lat='$lat', scan_long='$long' WHERE id=".$userID;
		$this->db->query($strSql);
		return TRUE;
	}
	
	function plugPoint($userID, $point) {
		$strSql = "UPDATE users SET verified_pt=verified_pt + $point WHERE id=".$userID;
		$this->db->query($strSql);
		return TRUE;
	}
	
	function foundationList($sessionID) {
		$strSql = "SELECT nID as identity, name, `desc`, `unit`, `point_cost`, `donated_user`, `goal`, donated, `loc_name`, `image_link`, `status` FROM foundation";
		$query = $this->db->query($strSql);
		
		if ($query->num_rows() == 0) return NULL;
		return $query->result();
	}
	
	function donatePoint($userID, $fID, $dCount) {
		$strSql = "SELECT point_cost FROM foundation WHERE nID=$fID";
		$query = $this->db->query($strSql);
		if ($query->num_rows() == 0) return NULL;
		$point_cost = $query->result();
		$point_cost = $point_cost[0]->point_cost;
		
		$point = $point_cost * $dCount;
		
		$strSql = "UPDATE users SET verified_pt=verified_pt-$point, donated_pt=donated_pt+$point WHERE id=".$userID;
		$this->db->query($strSql);
		
		$time = date('Y-m-d H:i:s');			
		$strSql = "INSERT INTO donation(nID, user, foundation, time, point) VALUES ('', '$userID', '$fID', '$time', '$dCount')";
		$this->db->query($strSql);
		
		$strSql = "SELECT COUNT(DISTINCT(USER)) AS donated_count FROM donation WHERE foundation = $fID";
		$query = $this->db->query($strSql);
		$donated_count = $query->result();
		$donated_count = $donated_count[0]->donated_count;

		$strSql = "UPDATE foundation SET donated_user=$donated_count, donated=donated+$dCount WHERE nID=".$fID;
		$this->db->query($strSql);
		return TRUE;
	}
	
	function donationList($userID) {
		$strSql = "SELECT foundation, time, point FROM donation WHERE user='$userID'";
		$query = $this->db->query($strSql);
	
		if ($query->num_rows() == 0) return NULL;
		return $query->result();
	}
}