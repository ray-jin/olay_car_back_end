<?php
class Saved_searches extends CI_Model 
{
     private $tbl_name= 'saved_searches';

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
	
	function &get_specific_data($idx) {
            
            $strSql = "SELECT * FROM $this->tbl_name WHERE id='$idx'";
            
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
	
     //&get_object_list( $start=0, $count=1000, $search_option='') 
            
        function list_saved_searches($start,$count,$userid)
        {            
            if ($userid == -1)
                $userid="";
            
            $strSql = "SELECT count(*) as cnt FROM saved_searches s ";
            if ($userid!=""){
                $strSql .= " where s.uid=$userid ";
            }                     
            
            $strSql.=" ORDER BY s.id DESC ";
                        
            $query = $this->db->query($strSql);		
            $row = $query->row_array();
            $return_arr['total'] = $row['cnt'];
            
            $strSql = "SELECT *  FROM saved_searches s ";
            if ($userid!=""){
                $strSql .= " where s.uid=$userid ";
            }
            
            $strSql .= " ORDER BY s.id desc limit $start, $count ";
            
            $query = $this->db->query($strSql);
            $return_arr['rows'] = $query->result_array();

            return $return_arr;

       }

       public function add_saved_searches($uid,$query){            
            
            $this->db->set('uid', $uid);
            $this->db->set('search_string', $query);
                        
            if ($this->db->insert("saved_searches")) {
                   $new_id = $this->db->insert_id();
                   return $new_id;
            }            
            return "-1"; //false
       }
       
       public function remove_saved_searches($id){            
            $this->db->where('id', $id);
            $this->db->delete("saved_searches");            
       }
	
	
}