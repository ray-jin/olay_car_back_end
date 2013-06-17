<?php
$f_search = array(	//text field			
		'name'	=> 'f_search',			
	);
//$make,$model,$s_price,$e_price,$p_code,$radius,$number,$offset
    $this->form_validation->set_rules('s_price', 'Start Price', 'trim|numeric');
    $this->form_validation->set_rules('e_price', 'End Price', 'trim|numeric');
    //$this->form_validation->set_rules('registration', 'Registration', 'trim|required');
    $this->form_validation->set_rules('make', 'Make', 'trim');
    $this->form_validation->set_rules('model', 'Model', 'trim');
    $this->form_validation->set_rules('radius', 'Radius', 'trim|integer');
    $this->form_validation->set_rules('number', 'Number', 'trim|integer');
    $this->form_validation->set_rules('offset', 'Offset', 'trim|integer');
    $this->form_validation->set_rules('p_code', 'Postcode', 'trim|min_length[2]|max_length[4]');
    
    if (count($_POST)==0)
        $validate=true;
    else
        $validate=$this->form_validation->run();

    if(empty($show_message)) {
	
		$this->form_validation->set_error_delimiters('<h4 class="alert_error">', '</h4>');
		echo form_error('s_price');
                echo form_error('e_price');
                echo form_error('make');
                echo form_error('model');
                echo form_error('radius');
                echo form_error('number');
                echo form_error('offset');
                echo form_error('p_code');
		if (isset($show_errors)) {?>
                    <h4 class="alert_error">
                        <?php if (is_array($show_errors)) {?>
                        <?php foreach ($show_errors as $error) :?>
                                <label><?=$error?></label>
                        <?php endforeach;?>
                        <?php } else {?>
                                <label><?=$show_errors?></label>
                        <?php } ?>
                    </h4>
<?php 
                
		}
                
	}
        
    $make = isset($_REQUEST['make']) ? trim($_REQUEST['make']) : "" ;
    $model = isset($_REQUEST['model']) ? trim($_REQUEST['model']) : "" ;
    $s_price = isset($_REQUEST['s_price']) ? trim($_REQUEST['s_price']) : "" ;
    $e_price = isset($_REQUEST['e_price']) ? trim($_REQUEST['e_price']) : "" ;
    $p_code = isset($_REQUEST['p_code']) ? trim($_REQUEST['p_code']) : "" ;
    $radius = isset($_REQUEST['radius']) ? trim($_REQUEST['radius']) : "9999999" ;
    $number = isset($_REQUEST['number']) ? trim($_REQUEST['number']) : 1000 ;
    $offset = isset($_REQUEST['offset']) ? trim($_REQUEST['offset']) : 0 ;
?>

<form name="frmSearch"  method="post" action="<?php  echo site_url("admin/car") ?>" >
    <article class="module width_full">
            <header>
                    <h3> Search </h3>
            </header>
            <div class="module_content">
                <table style="vertical-align: middle;">
                    <tr>
                        <td>Make:</td>
                        <td>
                            <input type="text" style="width : 150px;" name="make" value="<?php echo $make; ?>" />  &nbsp;
                        </td>
                        <td>Model:</td>
                        <td>
                            <input type="text" style="width : 150px;" name="model" value="<?php  echo $model; ?>" />  &nbsp;
                        </td>
                        <td>Start Price:</td>
                        <td>
                            <input type="text" style="width : 150px;" name="s_price" value="<?php echo $s_price;  ?>" />  &nbsp;
                        </td>
                        <td>End Price:</td>
                        <td>
                            <input type="text" style="width : 150px;" name="e_price" value="<?php echo $e_price; ?>" />  &nbsp;
                        </td>                        
                    </tr>
                    <tr>
                        <td>Postcode:</td>
                        <td>
                            <input type="text" style="width : 150px;" name="p_code" value="<?php echo $p_code;  ?>" />  &nbsp;
                        </td>
                        <td>Radius:</td>
                        <td>
                            <input type="text" style="width : 150px;" name="radius" value="<?php echo $radius; ?>" />  &nbsp;
                        </td>
                        <td>Number:</td>
                        <td>
                            <input type="text" style="width : 150px;" name="number" value="<?php echo $number; ?>" />  &nbsp;
                        </td>
                        <td>Offset:</td>
                        <td>
                            <input type="text" style="width : 150px;" name="offset" value="<?php  echo $offset; ?>" />  &nbsp;
                        </td>                        
                    </tr>
                </table>
                
                <input type="submit" name="fsubmit" value="Search" class="alt_btn">
            </div>
                    
    </article>
</form>

<article class="module width_full">
    <header>
        <h3 class="tabs_involved"> Car List </h3>
        <div class="submit_link">
           <input type="submit" value="Add" class="alt_btn" onclick="return post_add()" />
        </div>
    </header>

    <div class="tab_container">
        <table class="tablesorter" cellspacing="0">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Car ID</th>
                    <th>User Name</th>
                    <th>Image</th>
                    <th>Activated</th>                    
                    <th>Registration</th>
                    <th>Price</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Fuel Type</th>
                    <th>Transmission</th>
                    <th>Mileage</th>
                    <th>Uploaded</th>
                    <th>Postcode</th>
                    <th>Distance</th>
                    <th>Action</th>
                </tr>
             </thead>
             <tbody>
            <?php
            
            if ($validate==false){
                $car_list=array();
                
            }
            else{
                $user_id=$this->tank_auth->get_user_id();// current login id //probably admin
                $user=$this->users->get_user_by_id($user_id,1);


                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_URL, HOST."car/a_search");
             
                curl_setopt($ch, CURLOPT_POSTFIELDS, array('uid' => $user_id,
                                                            'sid' => $user->session_id,
                                                            'make' => $make,
                                                            'model' => $model,
                                                            's_price' => $s_price,
                                                            'e_price' => $e_price,
                                                            'p_code' => $p_code,
                                                            'radius' => $radius,
                                                            'number' => $number,
                                                            'offset' => $offset));
                  $json= curl_exec($ch);
                  $result=  json_decode($json);
                  
                  if ($result->status==FAIL){
                      echo "<div style='color:red'>".$result->error."</div>";
                      $car_list=array();
                  }
                  else{
                      $car_list=$result->cars;
                  }
                  
                  
            }
            $i=0;
              $start_no=1;
              
            foreach($car_list as $row) {
                $user=$this->manage_m->get_specific_data($row->uid, "users");
            ?>
                <tr>				
                     <td><?php echo $i+$start_no ; ?></td>
                    <td><?php echo $row->id;?></td>
                    <td><?php echo $user['username'];?></td>
                     <td>
                        <?php if ($row->file_url_1): ?>
                            <img src="<?php echo $row->file_url_1;?>" alt="No Image" height="80" width="80">
                        <?php else: ?>
                            <div style='color:red'> No Image </div>
                        <?php endif ?>
                    </td>                    
                    <td><?php echo $row->activated ? 'Yes' : "<div style='color:red'> No </div>";?></td>
                    <td><?php echo $row->registration;?></td>
                    <td>&pound;<?php echo $row->price;?></td>
                    <td><?php echo $row->make;?></td>
                    <td><?php echo $row->model;?></td>
                    <td><?php echo $row->year;?></td>
                    <td><?php echo $row->fuel_type;?></td>
                    <td><?php echo $row->transmission;?></td>
                    <td><?php echo $row->mileage;?></td>
                    <td>
                        <?php  $date = date_create($row->created); $datetime = $date->format('Y-m-d'); echo $datetime;?>
                    </td>
                    <td><?php echo $row->postcode;?></td>
                    <td><?php echo isset($row->dist) ? $row->dist : ""  ;?></td>
                    <td>
                        <input type="image" title="Edit" src="<?php echo IMG_DIR; ?>/icn_edit.png" onclick="goedit(<?php echo $row->id;?>)">
                        <input type="image" title="Trash" src="<?php echo IMG_DIR; ?>/icn_trash.png" onclick="confirm_del(<?php echo $row->id;?>)">
                    </td>			
                </tr>
            <?php
                $i++;
            }
            if($i==0) {
                    echo "<tr><td colspan='15' align='center'>Nothing </td></tr>";
            }
            ?>
</tbody>
        </table>
    </div>

    <script type="text/javascript">
            function post_add() {
                    window.location.href = "<?php echo site_url("admin/"."$post_key"."/".$post_key."_add"); ?>";
                    return false;
            }
            function confirm_del(pid) {
                    if(!confirm('Are you sure to delete?')) {
                            return;
                    }
                    window.location.href = "<?php echo site_url("admin/"."$post_key"."/".$post_key."_del"); ?>/" + pid;
            }
            function goedit(pid) {
                    window.location.href = "<?php echo site_url("admin/"."$post_key"."/".$post_key."_edit"); ?>/" + pid;
            }
    </script>

    <footer>
        <?php //echo $pagenation; ?>
    </footer>
</article>