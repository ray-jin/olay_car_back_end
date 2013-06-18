<?php
class Models extends CI_Model 
{
     private $tbl_name= 'models';

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
            
        function list_models($start,$count,$make_name)
        {            
            $strSql = "SELECT count(*) as cnt FROM models m ";            
            
            if ($make_name!="")
                 $strSql.=" inner join makes mk on (m.`make_id`=mk.`id` and LOWER(mk.`name`) LIKE LOWER('%$make_name%')) ";
              $strSql.=" ORDER BY m.name DESC ";
                                      
            $query = $this->db->query($strSql);		
            $row = $query->row_array();
            $return_arr['total'] = $row['cnt'];
            
            $strSql = "SELECT m.* FROM models m ";            
            
            if ($make_name!="")
                 $strSql.=" inner join makes mk on (m.`make_id`=mk.`id` and LOWER(mk.`name`) LIKE LOWER('%$make_name%')) ";
              $strSql.=" ORDER BY m.name DESC LIMIT $start, $count";
            
            $query = $this->db->query($strSql);
            $return_arr['rows'] = $query->result_array();

            return $return_arr;

       }
	
       function list_all_models_by_maker_id($make_id)
        {   
            $strSql = "SELECT m.* FROM models m "; 
            
            if ($make_id!="-1")
                $strSql.=" where m.make_id=$make_id ";
                 
              $strSql.=" ORDER BY m.name ";
            
            $query = $this->db->query($strSql);
            $return_arr['rows'] = $query->result_array();

            return $return_arr;

       }
	
	
}