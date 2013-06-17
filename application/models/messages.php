<?php
class Messages extends CI_Model 
{
     private $tbl_name= 'message_cars';

     function &get_object_list( $start=0, $count=1000, $search_option='') {

		$strSql = "SELECT COUNT(*) AS cnt FROM $this->tbl_name";
		$query = $this->db->query($strSql);		
		$row = $query->row_array();
		$return_arr['total'] = $row['cnt'];
                $strSql = "SELECT * FROM $this->tbl_name WHERE 1=1 ORDER BY id DESC LIMIT $start, $count";
                

                $query = $this->db->query($strSql);
		$return_arr['rows'] = $query->result_array();
		
		return $return_arr;
	}
	
	function &get_specific_data($idx,$tbl_name) {
            
            $strSql = "SELECT * FROM $tbl_name WHERE id='$idx'";
            
            $query = $this->db->query($strSql);
            $row = $query->row_array();
            return $row;
	}
	
	function get_next_insert_idx($tbl_name) {

		$next_increment = 0;
		$strSql = "SHOW TABLE STATUS WHERE Name='$this->tbl_name'";
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
	
        /**
        * get list of cars watched by user
        *	 
        * @number : number of results
        * @offset : start index
        * @showall : 1 -> by any user, 0 -> by this user only, default is 1
        */
    
    //&get_object_list( $start=0, $count=1000, $search_option='') 
            
        function list_messages($start,$count,$sender_name,$receiver_name,$tbl_name,$showall=1)
       {
            
            $strSql = "SELECT count(*) as cnt  FROM $tbl_name m ";
             if ($sender_name!="")
                 $strSql.=" inner join users up on (m.`sender_id`=up.`id` and LOWER(up.`username`) LIKE LOWER('%$sender_name%')) ";
             if ($receiver_name!="")
                 $strSql.=" inner join users up1 ON (m.`receiver_id`=up1.`id` AND LOWER(up1.`username`) LIKE LOWER('%$receiver_name%')) ";   
            $strSql.=" ORDER BY m.id DESC ";    
            
            $query = $this->db->query($strSql);		
            $row = $query->row_array();
            $return_arr['total'] = $row['cnt'];           
            
            $strSql = "SELECT m.*   FROM $tbl_name m ";
             if ($sender_name!="")
                 $strSql.=" inner join users up on (m.`sender_id`=up.`id` and LOWER(up.`username`) LIKE LOWER('%$sender_name%')) ";
             if ($receiver_name!="")
                 $strSql.=" inner join users up1 ON (m.`receiver_id`=up1.`id` AND LOWER(up1.`username`) LIKE LOWER('%$receiver_name%')) ";   
            $strSql.=" ORDER BY m.id DESC LIMIT $start, $count";    
            
            $query = $this->db->query($strSql);
            $return_arr['rows'] = $query->result_array();

            return $return_arr;

       }

	
}