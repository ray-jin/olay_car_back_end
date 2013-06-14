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
	private $car_img_table_name	= 'car_imgs';	// car images
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
            $this->car_img_table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->car_img_table_name;
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
            $this->db->like('Pcode', $p_code);
            //$this->db->like('make', $make); 
            
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
	 * Create new car record
	 *
	 * @param	array
         * @img_loc_array array of image location
	 * @return	car_id
	 */
	function create_car($uid,$registration,$price,$img_loc_array,$username)
	{		
            $this->db->set('uid', $uid);
            $this->db->set('activated', 1); //need to consider
            $this->db->set('registration', $registration);
            $this ->db->set('created',date('Y-m-d H:i:s'));
            $this->db->set('price', $price);
            $i=1;    
            foreach ($img_loc_array as $value) {
              $this->db->set('car_img_url_'.$i, $username."//cars//".$value);
              $i++;              
            }
             
            if ($this->db->insert($this->table_name)) {
                   $car_id = $this->db->insert_id();                   
                   return $car_id;
            }            
            return -1;
	}
        
        /**
	 * Create new car image_loc record
	 *
	 * @param	array
	 * @return	array
	 */
	 private function create_car_img_loc($img_loc_array,$car_id,$username)
	{
             foreach ($img_loc_array as &$value) {
                $this->db->set('car_id', $car_id);
                $this->db->set('img_loc', $username."//cars//".$value);
                $this->db->insert($this->car_img_table_name);
             }
             
            return true;
	}
        
        /**
	 * List car img url file
	 *
	 * @param	array
	 * @return	array
	 */
	 public function list_car_img_loc($car_id)
	{
            $this->db->where('car_id', $car_id);
            
            $query = $this->db->get($this->car_img_table_name);
            
            $list=array(); $i=0;
            foreach ($query->result() as $row)
            {
                $id=$row->id;
                
                //'user'	=>	'Users',	
                $list[$i] = array( 'id' => $row->id,
                        'img_loc' => $row->img_loc,
                    );                
                $i++;
            }
            
            return $list;
                         
	}
        
        /**
	 * get first car img url file
	 *
	 * @param	array
	 * @return	array
	 */
	 public function get_first_car_img_loc($car_id)
	{
            $this->db->where('car_id', $car_id);
            
            $query = $this->db->get($this->car_img_table_name);
            
            if (sizeof ($query->result()) >0){
                $row=$query->result();
                return $row[0]->img_loc;
            }
            else
                return null;
                         
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
	function update_car2($cid, $make, $model, $year, $f_type, $trans, $m_age,$desc)
	{
		$this->db->set('make', $make);
		$this->db->set('model', $model);
                $this->db->set('year', $year);
		$this->db->set('fuel_type', $f_type);
		$this->db->set('transmission', $trans);
                $this->db->set('mileage', $m_age);
                $this->db->set('desc', $desc);
                $this->db->set('modified',date('Y-m-d H:i:s'));
                
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
            
            for ($i=1; $i<=5; $i++){                
                
                $tmp=UPLOAD_PATH."/".$car['car_img_url_'.$i];
                if (file_exists($tmp))
                    unlink($tmp); //delete physical image file
                                
            }            
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
                //'user'	=>	'Users',	
                $list[$i] = array( 'cid' => $acar->id,
                    'make' => $acar->make,
                        'model' => $acar->model,
                    'year' => $acar->year,
                    'fuel_type' => $acar->fuel_type,
                    'transmission' => $acar->transmission,
                    'mileage' => $acar->mileage,
                    'desc' => $acar->desc,);
                
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
                $url="";
                if ($this->get_first_car_img_loc($row->id)!=""){
                    $url=base_url()."//". $this->config->item('upload_path')."//".$this->get_first_car_img_loc($row->id);
                }
                
                $list[$i] = array( 'id' => $row->id,
                    'make' => $row->make,
                        'model' => $row->model,
                    'year' => $row->year,
                    'fuel_type' => $row->fuel_type,
                    'transmission' => $row->transmission,
                    'mileage' => $row->mileage,
                    'desc' => $row->desc,
                    'url' =>  $url,
                   );
                
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
                //'user'	=>	'Users',	
                $url="";
                if ($this->get_first_car_img_loc($row->id)!=""){
                    $url=base_url()."//". $this->config->item('upload_path')."//".$this->get_first_car_img_loc($row->id);
                }
                
                $list[$i] = array( 'id' => $row->id,
                    'make' => $row->make,
                        'model' => $row->model,
                    'year' => $row->year,
                    'fuel_type' => $row->fuel_type,
                    'transmission' => $row->transmission,
                    'mileage' => $row->mileage,
                    'desc' => $row->desc,
                    'url' =>  $url,
                    );//$result['url'] = base_url()."//". $this->config->item('upload_path')."//".$file_loc_array[0]["img_loc"];
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
	function a_search_list($make,$model,$s_price,$e_price,$p_code,$radius,$number,$offset)
	{
            //get postal code from post code tables
            $list=array();
             
            $pcode_obj=$this->get_post_code_obj($p_code);
         
            if (!$pcode_obj)
                 return $list;
         
             if (($make) && ($make!="")){
                 $this->db->like('make', $make); 
             }
             if (($model) && ($model!="")){
                 $this->db->like('model', $model); 
             }
            
             if (($s_price) && ($s_price!="")){
                 $this->db->where('price >=', $s_price); 
             }
             
             if (($e_price) && ($e_price!="")){
                 $this->db->where('price <=', $e_price); 
             }            
            
            $this->db->order_by("modified", "desc"); 
            
            //$this->db->where('cid !=', 'NULL');
            $steps=10; // the number which is used to retrive the records at one time
            $count=0; $i=0;
            do {
                
                $query = $this->db->get($this->table_name,$steps,$offset+$steps*$count);
                $count+=1;
                 
                 $qresult=$query->result() ;
                foreach ($qresult as $row)
                {
                    $pcode_obj_second=$this->get_post_code_obj($row->postcode);
                    
                    $distance_n=$pcode_obj->Grid_N - $pcode_obj_second->Grid_N;
                    $distance_e=$pcode_obj->Grid_E - $pcode_obj_second->Grid_E;

                // CALCULATE THE DISTANCE BETWEEN THE TWO POINTS

                    $hypot=sqrt(($distance_n*$distance_n)+($distance_e*$distance_e));
                    $kms=round($hypot/1000,2);            
                    
                    if ($kms<$radius){
                        $list[$i] = array( 'cid' => $row->id,
                            'make' => $row->make,
                                'model' => $row->model,
                            'year' => $row->year,
                            'fuel_type' => $row->fuel_type,
                            'transmission' => $row->transmission,
                            'mileage' => $row->mileage,
                            'price' => $row->price,
                            'desc' => $row->desc,);

                            $i++;

                        }
                    }
            } while ($i<$number && $qresult);
            
            
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
	 function add_message_car($s_id,$r_id,$message)
	{
             
            $this->db->set('sender_id', $s_id);
            $this->db->set('receiver_id', $r_id);
            $this->db->set('message', $message);
            $this->db->set('created', date('Y-m-d H:i:s'));
            
            
            if ($this->db->insert($this->message_car_table_name)) {
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
	 function list_message_car($s_id,$r_id,$number,$offset)
	{
            if ($s_id!="-1")
                $this->db->where('sender_id', $s_id);
            if ($r_id!="-1")
                $this->db->where('receiver_id', $r_id);
            
            $query = $this->db->get($this->message_car_table_name,$number,$offset);
            
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
	 * Remove message car record by id
	 *
	 * @param	int 
	 * @return	void
	 */
	function remove_message_car_by_id($msg_id)
	{
            $this->db->where('id', $msg_id);

            $this->db->delete($this->message_car_table_name);
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
	 function list_offer_car($cid,$number,$offset)
	{
            
            $this->db->where('cid', $cid);
                        
            $query = $this->db->get($this->offer_car_table_name,$number,$offset);
            
            $list=array(); $i=0;
            foreach ($query->result() as $row)
            {
                
                $list[$i] = array( 'offer_id' => $row->id,
                        'msg' => $row->message,
                    'c_date' => $row->created,
                    'price' => $row->price,
                    's_id' => $row->uid,
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