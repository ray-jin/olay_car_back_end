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
class Cars extends CI_Model
{
	private $table_name			= 'cars';			// cars
	
        private $watch_car_table_name = 'watch_cars';
        private $comment_car_table_name = "comment_cars";
        private $message_car_table_name = "message_cars";
        private $offer_car_table_name = "offer_cars";
        private $post_codes_table_name="postcodes";

	function __construct()
	{
            parent::__construct();

            $ci =& get_instance();
            $this->table_name			= $ci->config->item('db_table_prefix', 'tank_auth').$this->table_name;            
            $this->watch_car_table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->watch_car_table_name;
            $this->comment_car_table_name   = $ci->config->item('db_table_prefix', 'tank_auth').$this->comment_car_table_name;
            $this->message_car_table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->message_car_table_name;
            $this->offer_car_table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->offer_car_table_name;
            $this->post_codes_table_name= $ci->config->item('db_table_prefix', 'tank_auth').$this->post_codes_table_name;
            
            $this->load->helper('url');
            
	}

         function &get_object_list( $start=0, $count=1000, $search_option='') {

		$strSql = "SELECT COUNT(*) AS cnt FROM $this->table_name";
		$query = $this->db->query($strSql);		
		$row = $query->row_array();
		$return_arr['total'] = $row['cnt'];
                $strSql = "SELECT * FROM $this->table_name WHERE 1=1 ORDER BY id DESC LIMIT $start, $count";
                

                $query = $this->db->query($strSql);
		$return_arr['rows'] = $query->result_array();
		
		return $return_arr;
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
	 * Get car record by Id
	 *
	 * @param	int
	 * @return	object
	 */
	function get_car_by_id($car_id)
	{
		$this->db->where('id', $car_id);
		$query = $this->db->get($this->table_name);

                if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
        
        function &get_specific_data($idx) {
            
            $strSql = "SELECT * FROM cars WHERE id='$idx'";
            
            $query = $this->db->query($strSql);
            $row = $query->row_array();
            return $row;
	}
        
        /**
	 * Get car record by Id
	 *
	 * @param	int
	 * @return	object
	 */
	function get_post_code_obj($p_code)
	{            
            $this->db->where('Pcode', $p_code);
            $query = $this->db->get($this->post_codes_table_name);
            
            if ($query->num_rows() >= 1) return $query->row();
            return NULL;
	}
        
        /**
	 * Get comment record by Id
	 *
	 * @param	int
	 * @return	object
	 */
	function get_comment_by_id($comment_id)
	{
		$this->db->where('id', $comment_id);
		$query = $this->db->get($this->comment_car_table_name);

                if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
        
         /**
	 * Get comment record by Id
	 *
	 * @param	int
	 * @return	object
	 */
	function get_offer_by_id($offer_id)
	{
		$this->db->where('id', $offer_id);
		$query = $this->db->get($this->offer_car_table_name);

                if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
        
        /**
	 * Get message record by Id
	 *
	 * @param	int
	 * @return	object
	 */
	function get_message_by_id($message_id,$tbl_name)
	{
		$this->db->where('id', $message_id);
		$query = $this->db->get($tbl_name);

                if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	
	/**
	 * Create new car record
	 *
	 * @param	array
         * @img_loc_array array of image location
	 * @return	car_id
	 */
	function create_car($uid,$registration,$price,$img_loc_array,$username,$cid,$postcode="")
	{		
            $this->db->set('uid', $uid);
            $this->db->set('activated', 1); //need to consider
            $this->db->set('registration', $registration);
            $this ->db->set('created',date('Y-m-d H:i:s'));
            $this->db->set('price', $price);
            $this->db->set('postcode', $postcode);
            $i=1;    
            foreach ($img_loc_array as $value) {              
              $this->db->set('file_url_'.$i, $username."/cars/".$cid."/".$value);
              $i++;              
            }
             
            if ($this->db->insert($this->table_name)) {
                   $car_id = $this->db->insert_id();                   
                   return $car_id;
            }            
            return -1;
	}
        
       
        
        /**
	 * List postcodes
	 *
	 * @param	array
	 * @return	array
	 */
	 public function list_postcodes()
	{
            
            $query = $this->db->get("postcodes");
            
            $list=array(); $i=0;
            foreach ($query->result() as $row)
            {
                
                //'user'	=>	'Users',	
                $list[$i] = array( 'id' => $row->Postcode_ID,
                        'pcode' => $row->Pcode,
                    );                
                $i++;
            }
            
            return $list;
                         
	}
        
       
        /**
	 * Update car listing details once the car listing is created
         * @make : manufactuer
         * @model : model
         * @year : year
          * @f_type : fuel_type
          * @trans : transmission
          * @m_age : mileage
          * @desc : description 
	 */
        
	function update_car2($cid, $make, $model, $year, $f_type, $trans, $m_age,$desc,$img_loc_array,$username,
                $activated,$postcode,$registration,$price)
	{
		$this->db->set('make', $make);
		$this->db->set('model', $model);
                $this->db->set('year', $year);
		$this->db->set('fuel_type', $f_type);
		$this->db->set('transmission', $trans);
                $this->db->set('mileage', $m_age);
                $this->db->set('desc', $desc);
                $this->db->set('postcode', $postcode);
                $this->db->set('modified',date('Y-m-d H:i:s'));
                $this->db->set('registration', $registration);
                $this->db->set('price', $price);
                
                if (isset($activated))
                    $this->db->set('activated',$activated);
                $car=$this->get_specific_data($cid);
                
                $i=0;
                foreach ($img_loc_array as $value) {
                    if ($value!="") {
                        if ($car['file_url_'.$i]!="")
                        {
                            $tmp=UPLOAD_PATH."/".$car['file_url_'.$i];
                            if (file_exists($tmp))
                                unlink($tmp); //delete physical image file
                        }
                        
                        $this->db->set('file_url_'.$i, $username."/cars/".$cid."/".$value);
                    }
                    
                    $i++;              
                }
                 
		$this->db->where('id', $cid);		

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}
        
        /**
	 * Set the num_visitors field, usually increased by 1
         * @id : car_id         
	 */
	function inc_num_visitors($id,$new_num_visitors)
	{
		$this->db->set('num_visitors', $new_num_visitors);                
		$this->db->where('id', $id);		

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}
        
       
        /**
	 * Delete car record
	 *
	 * @param	int
	 * @return	void
	 */
	function delete_car($car_id)
	{
            $car=$this->get_specific_data($car_id);
            
            $this->load->library('tank_auth');
            $user=$this->users->get_user_by_id($car['uid']);
           //$path=UPLOAD_PATH."/".$user->username."/cars/".$car_id."/";
            
            
            $this->load->helper("file"); // load the helper
           // delete_files($path, true); // delete all files/folders
          //  rmdir($path);
            
            $this->db->where('id', $car_id);
            $this->db->delete($this->table_name);
	}
        
        /**
	 * Create new item into watch_car table
	 *
	 * @param	array
	 * @return	array
	 */
	 function add_watch_car($cid,$uid)
	{
             
            $this->db->set('cid', $cid);
            $this->db->set('uid', $uid);
            
            if ($this->db->insert($this->watch_car_table_name)) {
                   $watch_id = $this->db->insert_id();
                   return $watch_id;
            }
            
            return -1;
	}
        
        /**
	 * get list of cars watched by user
	 *
	 * @param	array
	 * @return	array
	 */
	 function list_watch_car($uid)
	{
            $this->db->where('uid', $uid);
            $this->db->where('cid !=', 'NULL');
            $query = $this->db->get($this->watch_car_table_name);
            
            $list=array(); $i=0;
            foreach ($query->result() as $row)
            {
                $id=$row->cid;
                $acar=$this->get_car_by_id($id);
                if ($acar==null){
                    $list[$i] = array( 'cid' => "$row->cid",
                        'live' => "0",
                    );
                }
                else{
                    //'user'	=>	'Users',	
                    $list[$i] = array( 'cid' => $acar->id,
                    'make' => $acar->make,
                        'model' => $acar->model,
                    'year' => $acar->year,
                    'fuel_type' => $acar->fuel_type,
                    'transmission' => $acar->transmission,
                    'mileage' => $acar->mileage,
                    'price' => $acar->price,    
                    'postcode' => $acar->postcode,
                    'desc' => $acar->desc,
                    'live' => "1",    );
                }
                
                $i++;
                
            }
            
            return $list;
           
	}
        
        /**
	 * Remove watch car record
	 *
	 * @param	int
         * @param       int
	 * @return	void
	 */
	function remove_watch_car($cid,$uid)
	{
            $this->db->where('cid', $cid);
            $this->db->where('uid', $uid);

            $this->db->delete($this->watch_car_table_name);
	}
        
        /**
	 * Remove watch car record by id
	 *
	 * @param	int 
	 * @return	void
	 */
	function remove_watch_car_by_id($wc_id)
	{
            $this->db->where('id', $wc_id);            

            $this->db->delete($this->watch_car_table_name);
	}
        
         /**
	 * get list of cars watched by user
	 *	 
         * @number : number of results
         * @offset : start index
         * @showall : 1 -> by any user, 0 -> by this user only, default is 1
	 */
	 function list_latest_cars($uid,$number,$offset,$showall=1)
	{
            if ($showall==0) //only for specific user
                $this->db->where('uid', $uid);
            
            $this->db->where('uid !=', 'NULL');
            $this->db->order_by("created", "desc"); 
            $query = $this->db->get($this->table_name,$number,$offset);         
            
            $list=array(); $i=0;
            foreach ($query->result() as $row)
            {
                //'user'	=>	'Users',	
                $list[$i] = array( 'id' => $row->id,
                    'uid' => $row->uid,
                    'make' => $row->make,
                    'model' => $row->model,
                    'registration' => $row->registration,
                    'year' => $row->year,
                    'fuel_type' => $row->fuel_type,
                    'transmission' => $row->transmission,
                    'mileage' => $row->mileage,
                    'price' => $row->price,
                    'postcode' => $row->postcode,                    
                    'desc' => $row->desc,
                    );
                  $file_list=$this->list_car_files($row->id);
                  $list[$i]['files']=$file_list;
                $i++;
                
            }
            
            return $list;
           
	}
        
         /**
	 * get list of popular cars based on num_visitors field of cars table
	 * @number : number of results
         * @offset : start index
         */
	 function list_popular_cars($number, $offset)
	{            
            
            $this->db->where('uid !=', 'NULL');
            $this->db->order_by("num_visitors", "desc"); 
            $query = $this->db->get($this->table_name,$number,$offset);
            
            $list=array(); $i=0;
            foreach ($query->result() as $row)
            {                
                $list[$i] = array( 'id' => $row->id,
                    'uid' => $row->uid,
                    'make' => $row->make,
                    'model' => $row->model,
                    'registration' => $row->registration,
                    'year' => $row->year,
                    'fuel_type' => $row->fuel_type,
                    'transmission' => $row->transmission,
                    'mileage' => $row->mileage,
                    'price' => $row->price,
                    'postcode' => $row->postcode,                    
                    'desc' => $row->desc,                    
                    );            
                            
                    $file_list=$this->list_car_files($row->id);
                    $list[$i]['files']=$file_list;
               
                $i++;
            }
            
            
            return $list;
           
	}
        
         /**
	 * get list of cars watched by user
	 *
	 * @uid : user id
         * @number : number of results
         * @make : car make
         * @model : model
         * @price : price
         * @loc : current postal code
	 */
	function a_search_list($make,$model,$s_price,$e_price,$pcode_obj,$radius,$number,$offset,$is_admin)
	{
            //get postal code from post code tables
            $list=array();
            
            $strSql = "select id, uid, make, model, activated, registration, year, fuel_type, transmission, mileage,"
                ." price, postcode,  created ";
             if ($pcode_obj!=null){
                 $strSql.= " ,calc_distance($pcode_obj->Grid_N,$pcode_obj->Grid_E,`postcode`) dist ";
             }
             $strSql.=" from cars where 1=1 ";
            
             if (($make) && ($make!="")){             
                 $strSql.=" and make like '%$make%'";
             }
             
             if (($model) && ($model!="")){             
                 $strSql.=" and model like '%$model%'";
             }
             
             if (($s_price) && ($s_price!="")){             
                 $strSql.=" and price >= $s_price ";
             }
             
             if (($e_price) && ($e_price!="")){             
                 $strSql.=" and price <= $e_price ";
             }
             
             if ($is_admin==false)
                $strSql.=" and activated=1 ";               
               
            
            if ($pcode_obj!=null){
                $order_sql=" calc_distance($pcode_obj->Grid_N,$pcode_obj->Grid_E,`postcode`) ";
                $strSql.=" and $order_sql BETWEEN 0 AND $radius ";
                $strSql.=" order by dist ";
            }
            else{
                $strSql.=" order by created desc ";   
            }
            $strSql.=" limit $offset, $number";
            $query=$this->db->query($strSql);
            $list = $query->result_array();
            $i=0;
            
            
            foreach ($list as $row)
            {                
                $file_list=$this->list_car_files($row['id']);
                $list[$i]['files']=$file_list;
               
                $i++;
            }
        
            return $list;           
	}
        
        /**
	 * Create new item into comment_car table
	 *
	 * @cid :car id
         * @uid : user_id
         * @comment : comment text
	 * @return	array
	 */
	 function add_comment_car($cid,$uid,$comment)
	{
             
            $this->db->set('cid', $cid);
            $this->db->set('uid', $uid);
            $this->db->set('comment', $comment);
            
            
             if ($this->db->insert($this->comment_car_table_name)) {
                   $comm_id = $this->db->insert_id();                          
                   return $comm_id;
            }
            
            return "-1"; //false
            
	}
        
         /**
	 * get list of comments watched by users for a specific car
	 *
	 * 
	 * @return	array
	 */
	 function list_comment_car($cid,$number,$offset)
	{
            $this->db->where('cid', $cid);
            $this->db->where('cid !=', 'NULL');
            $query = $this->db->get($this->comment_car_table_name,$number,$offset);
            
            $list=array(); $i=0;
            foreach ($query->result() as $row)
            {
                //'user'	=>	'Users',	
                $list[$i] = array( 'comm_id' => $row->id,
                        'comment' => $row->comment,                
                );
                
                $i++;                
            }            
            return $list;           
	}
        
        /**
	 * Remove comment car record by id
	 *
	 * @param	int 
	 * @return	void
	 */
	function remove_comment_car_by_id($comm_id)
	{
            $this->db->where('id', $comm_id);            

            $this->db->delete($this->comment_car_table_name);
	}
        
         /**
	 * Create new item into message_car table
	 *
	 * @s_id :sender id
         * @r_id : receiver_id
         * @message : message text
	 * @return	array
	 */
	 function add_message_car($s_id,$r_id,$message,$tbl_name)
	{
             
            $this->db->set('sender_id', $s_id);
            $this->db->set('receiver_id', $r_id);
            $this->db->set('message', $message);
            $this->db->set('created', date('Y-m-d H:i:s'));
            
            
            if ($this->db->insert($tbl_name)) {
                   $msg_id = $this->db->insert_id();                                      
                   return $msg_id;
            }
            
            return "-1";
            
	}
        
        /**
	 * get list of messages watched by users for a specific car
         * @s_id  : sender user id
         * @r_id  : receiver user id
         * @number : number of messages
         * @offset : offset of message
	 * @return	array
	 */
	 function list_message_car($s_id,$r_id,$number,$offset,$tbl_name)
	{
            if ($s_id!="-1")
                $this->db->where('sender_id', $s_id);
            if ($r_id!="-1")
                $this->db->where('receiver_id', $r_id);
            
            $query = $this->db->get($tbl_name,$number,$offset);
            
            $list=array(); $i=0;
            foreach ($query->result() as $row)
            {
                //'user'	=>	'Users',	
                $list[$i] = array( 'msg_id' => $row->id,
                        'msg' => $row->message,
                    'c_date' => $row->created,
                );
                
                $i++;                
            }            
            return $list;           
	}
        
        /**
	 * get list of messages watched by users for a specific car
         * @s_id  : sender user id
         * @r_id  : receiver user id
         * @number : number of messages
         * @offset : offset of message
	 * @return	array
	 */
	 function list_car_files($cid)
	{            
            $this->db->where('cid', $cid);
            $this->db->order_by('id', "asc");
            $query = $this->db->get("file_loc");
            
            $list=array(); $i=0;
            foreach ($query->result() as $row)
            {
                
                if ($row->type==1){  //type=1 : image
                    $full_url=HOST.UPLOAD_PATH.$row->url;
                }
                else{
                    $full_url=$row->url;
                }
                $list[$i] = array( 'type' => $row->type,
                        'url' => $full_url,
                );
                
                $i++;                
            }            
            return $list;           
	}
        
         /**
	 * Remove message car record by id
	 *
	 * @param	int 
	 * @return	void
	 */
	function remove_message_car_by_id($msg_id,$tbl_name)
	{
            $this->db->where('id', $msg_id);

            $this->db->delete($tbl_name);
	}
        
         /**
	 * Create new item into offer_cars table
	 *
	 * @s_id :sender id
         * @r_id : receiver_id
         * @price : price
         * @message : message text
	 * @return	array
	 */
	 function add_offer_car($uid,$cid,$price,$message)
	{
             
            $this->db->set('uid', $uid);
            $this->db->set('cid', $cid);
            $this->db->set('price', $price);
            $this->db->set('message', $message);
            $this->db->set('created', date('Y-m-d H:i:s'));
            
            
            if ($this->db->insert($this->offer_car_table_name)) {
                   $msg_id = $this->db->insert_id();                                      
                   return $msg_id;
            }
            
            return "-1";
            
	}
        
        /**
	 * get list of messages watched by users for a specific car
         * @cid  : car id
         * @number : number of messages
         * @offset : offset of message
	 * @return	array
	 */
	 function list_offer_car($uid,$cid,$number,$offset)
	{
            if ($uid!=-1)
                $this->db->where('uid', $uid);
            if ($cid!=-1)
                $this->db->where('cid', $cid);
                        
            $query = $this->db->get($this->offer_car_table_name,$number,$offset);
            
            $list=array(); $i=0;
            foreach ($query->result() as $row)
            {
                
                $list[$i] = array( 'offer_id' => $row->id,                        
                        'cid' => $row->cid,
                        'msg' => $row->message,
                    'c_date' => $row->created,
                    'price' => $row->price,
                    'o_id' => $row->uid,
                );
                
                $i++;                
            }            
            return $list;           
	}
        
        /**
	 * Remove offer car record by id
	 *
	 * @param	int 
	 * @return	void
	 */
	function remove_offer_car_by_id($offer_id)
	{
            $this->db->where('id', $offer_id);

            $this->db->delete($this->offer_car_table_name);
	}

        /***
         * 
         * Get distance between 2 postal codes
         * 
         */
        function calc_postcode_distance($pcodeA,$pcodeB)
        {

            // PCODE A
            //$this->db->where('Pcode', $pcodeA);
            $this->db->like('Pcode', $pcodeA); 
                        
            $query = $this->db->get($this->post_codes_table_name,1,0);
            
             if (sizeof ($query->result()) >0){
                $row=$query->result();
                $gridn[0]=$row[0]->Grid_N;//;$row[Grid_N];
                $gride[0]=$row[0]->Grid_E;//$row[Grid_E];
            }
            else
                return -1;

            // PCODE B 
            //$this->db->where('Pcode', $pcodeB);
            $this->db->like('Pcode', $pcodeB); 
                        
            $query = $this->db->get($this->post_codes_table_name,1,0);
            //$result=$query->result;            
            if (sizeof ($query->result()) >0){
                $row=$query->result();
                $gridn[1]=$row[0]->Grid_N;
                $gride[1]=$row[0]->Grid_E;
            }
            else{
                return -1;
            }
          
            // TAKE GRID REFS FROM EACH OTHER.

            $distance_n=$gridn[0]-$gridn[1];
            $distance_e=$gride[0]-$gride[1];

            // CALCULATE THE DISTANCE BETWEEN THE TWO POINTS

            $hypot=sqrt(($distance_n*$distance_n)+($distance_e*$distance_e));

            $kms=round($hypot/1000,2);            

            return $kms;

        }
        
}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */