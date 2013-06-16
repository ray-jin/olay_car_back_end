<article class="module width_full">
<header>
	<h3>
	Edit ( <?php echo $post['id']; ?> ) </h3>
</header>
<?php
	
        $car_id = array(	//id of car id
		'name'	=> 'id',
		'id'	=> 'id',
		'value' => set_value('id'),
		'title'	=> 'Car ID',
	);
         
        $uid = array(	//id of user id
		'name'	=> 'uid',
		'id'	=> 'uid',
		'value' => set_value('uid'),
		'title'	=> 'User ID',
	);
        
        $activated = array(	//activated
		'name'	=> 'activated',
		'id'	=> 'activated',
		'value' => set_value('activated'),
		'title'	=> 'Activated',
	);
        
      
	
	
	echo form_open_multipart($this->uri->uri_string());
	
	if(!empty($show_message)) {
		echo "<h4 class='alert_success'>".$show_message."</h4>";
	}else{
		$this->form_validation->set_error_delimiters('<h4 class="alert_error">', '</h4>');
		echo form_error('price');
                echo form_error('registration');
                echo form_error('year');
                echo form_error('mileage');
              
        
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
        $auser= $this->users->get_user_by_id(ADMIN_USER_ID, true);
?>

<div class="module_content">
    <input type="hidden" name="<?php echo $car_id['name']; ?>" value="<?php echo $post['id'] ?>"/>
    <fieldset>  
            <label style="width:100%;">Username : <?php echo $auser->username; ?></label>
            <img src="<?php echo HOST.UPLOAD_PATH.$post['file_url_1'];?>"
                 alt="No Image" height="80" width="80" style="padding-left: 10px;">
            &nbsp;&nbsp; <input type="file" name="file_url_1" accept="image/*" />
    </fieldset>
    <table>
        <tr>
            <td>
                File 2: 
                    <?php if ($post['file_url_2']!=""):?>
                        <a href="<?php echo HOST.UPLOAD_PATH.$post['file_url_2'] ?>"> Click here </a>
                    <?php else: ?>
                        <span style='color:red'> No File</span>
                    <?php endif;?>
            </td>
           <td>
                File 3: 
                    <?php if ($post['file_url_3']!=""):?>
                        <a href="<?php echo HOST.UPLOAD_PATH.$post['file_url_3'] ?>"> Click here </a>
                    <?php else: ?>
                        <span style='color:red'> No File</span>
                    <?php endif;?>
            </td>
            <td>
                File 4: 
                    <?php if ($post['file_url_4']!=""):?>
                        <a href="<?php echo HOST.UPLOAD_PATH.$post['file_url_4'] ?>"> Click here </a>
                    <?php else: ?>
                        <span style='color:red'> No File</span>
                    <?php endif;?>
            </td>
            <td>
                File 5: 
                    <?php if ($post['file_url_5']!=""):?>
                        <a href="<?php echo HOST.UPLOAD_PATH.$post['file_url_5'] ?>"> Click here </a>
                    <?php else: ?>
                        <span style='color:red'> No File</span>
                    <?php endif;?>
            </td>
        </tr>
        <tr>
            <td>
                <input type="file" name="file_url_2" accept="image/*,video/*" />
            </td>
            <td>
                <input type="file" name="file_url_3" accept="image/*,video/*" />
            </td>
            <td>
                <input type="file" name="file_url_4" accept="image/*,video/*" />
            </td>
            <td>
                <input type="file" name="file_url_5" accept="image/*,video/*" />
            </td>
        </tr>
    </table>
     <table style="width: 100%;">
         <tr style="height:10px;">
             <td>&nbsp;</td>
         </tr>
         <tr>
             <td colspan="2"> Activated :<input type="checkbox" style="vertical-align: middle;" name="activated" value="1" 
                     <?php if($post['activated']=="1") echo " checked='checked'";?>/>
                 &nbsp;&nbsp;Uploaded:  
                 <?php
                 
                    $date = date_create($post['created']);
                    $datetime = $date->format('Y-m-d');
                    echo $datetime;
                 ?>
             </td>
         </tr>
         <tr style="height:10px;">
             <td>&nbsp;</td>
         </tr>
        <tr>
            <td> Registration : </td>
            <td>   <input type="text" style="width:150px;" name="registration" value="<?php echo $post['registration'];?>"/> &nbsp; </td>
            <td> Price : </td>
            <td>   <input type="text" style="width:150px; text-align: right;" size="15"  name="price" value="<?php echo $post['price'];?>"  /> &nbsp; </td>
            
            <td> Make : </td>
            <td>   <input type="text" style="width:150px;" size="15" name="make" value="<?php echo $post['make'];?>" /> &nbsp; </td>
            <td> Model : </td>
            <td>   <input type="text" style="width:150px;" size="15" name="model" value="<?php echo $post['model'];?>" /> &nbsp; </td>
        </tr>
        <tr>
            <td> Year : </td>
            <td>   <input type="text" style="width:150px; text-align: right;" size="15" maxlength="4" name="year" value="<?php echo $post['year'];?>" /> &nbsp; </td>
            <td> Fuel Type : </td>
            <td>   <input type="text"  name="fuel_type" value="<?php echo $post['fuel_type'];?>"/> &nbsp; </td>
            <td> Transmission : </td>
            <td>   <input type="text" size="15" style="width:150px;"  name="transmission" value="<?php echo $post['transmission'];?>"  /> &nbsp; </td>
            <td> Mileage : </td>
            <td>   <input type="text" size="15" name="mileage" style="width:150px; text-align: right;" value="<?php echo $post['mileage'];?>" /> &nbsp; </td>
      <!--      <td> Postcode : </td>
            <td>   <input type="text" size="15" maxlength="4" name="postcode" value="<?php echo $post['postcode'];?>"  /> &nbsp; </td> -->
        </tr> 
        <tr>
            <td colspan="8">
                Description
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <textarea name="desc" id="desc" rows="10" style="width:98%;" ><?php echo $post['desc'];?></textarea>
            </td>
        </tr>
    </table>
   
  
  <!--
    <fieldset >
            <label>Body type</label>
            <input type="text" name="<?php echo $bodytype['name']; ?>" value="<?php echo $post['bodytype']?>"/>
    </fieldset>
    <fieldset >
            <label>Colour</label>
            <input type="text" name="<?php echo $colour['name']; ?>" value="<?php echo $post['colour']?>" />
    </fieldset>
    <fieldset >
            <label>Country</label>
            <input type="text" name="<?php echo $country['name']; ?>" value="<?php echo $post['country']?>"/>
    </fieldset>
    <fieldset >
            <label>Currency</label>
            <input type="text" name="<?php echo $currency['name']; ?>" value="<?php echo $post['currency']?>" />
    </fieldset>
    <fieldset >
            <label>Website</label>
            <input type="text" name="<?php echo $website['name']; ?>" value="<?php echo $post['website']?>"/>
    </fieldset>
    <fieldset>
            <label>Description</label>
            <textarea name="<?php echo $desc['name']; ?>"><?php echo $post['uid']?></textarea>
    </fieldset>
    <fieldset>
            <label>Engine Size</label>                
            <input type="text" name="<?php echo $engine_size['name']; ?>" value="<?php echo $post['engine_size']?>"/>
    </fieldset>
   
    <fieldset>
            <label>Gear Box</label>                
            <input type="text" name="<?php echo $gearbox['name']; ?>" value="<?php echo $post['gearbox']?>"/>
    </fieldset>
    <fieldset>
            <label>Mileage</label>                
            <input type="text" name="<?php echo $mileage['name']; ?>" value="<?php echo $post['mileage']?>"/>
    </fieldset>
    <fieldset>
            <label>Mileage Units</label>                
            <input type="text" name="<?php echo $mileageunits['name']; ?>" value="<?php echo $post['mileageunits']?>"/>
    </fieldset>
    
    <fieldset>
            <label>Number of Doors</label>                
            <input type="text" name="<?php echo $num_doors['name']; ?>" value="<?php echo $post['num_doors']?>"/>
    </fieldset>
    <fieldset>
            <label>Number of Owners</label>                
            <input type="text" name="<?php echo $num_owners['name']; ?>" value="<?php echo $post['num_owners']?>"/>
    </fieldset>
    <fieldset>
            <label>Car options</label>                
            <input type="text" name="<?php echo $car_options['name']; ?>" value="<?php echo $post['car_options']?>"/>
    </fieldset>
   
    <fieldset>
            <label>Purchase Type</label>
            <input type="text" name="<?php echo $purchase_type['name']; ?>" value="<?php echo $post['purchase_type']?>"/>
    </fieldset>
    <fieldset>
            <label>Region</label>                
            <input type="text" name="<?php echo $region['name']; ?>" value="<?php echo $post['region']?>"/>
    </fieldset>
    
    <fieldset>
            <label>Roadtax</label>

            <input type="text" name="<?php echo $roadtax['name']; ?>" value="<?php echo $post['roadtax']?>"/>
    </fieldset>
    <fieldset>
            <label>Sales Type</label>

            <input type="text" name="<?php echo $sales_type['name']; ?>" value="<?php echo $post['sales_type']?>"/>
    </fieldset>
    <fieldset>
            <label>Tested Until</label>                
            <input type="text" name="<?php echo $tested_until['name']; ?>" value="<?php echo $post['tested_until']?>"/>
    </fieldset>
    <fieldset>
            <label>Vin Number</label>                
            <input type="text" name="<?php echo $vin_number['name']; ?>" value="<?php echo $post['vin_number']?>"/>
    </fieldset>
    <fieldset>
            <label>Warranty</label>                
            <input type="text" name="<?php echo $warranty['name']; ?>" value="<?php echo $post['warranty']?>"/>
    </fieldset>
   
    <fieldset>
            <label>Created</label>                
            <input type="text" name="<?php echo $created['name']; ?>" value="<?php echo $post['created']?>" readonly="readonly"/>
    </fieldset>	
    -->
</div>
<footer>
	<div class="submit_link">            
          
            <input type="submit" value="Update" class="alt_btn"> <input type="button" value="Cancel" class="alt_btn" onclick="javascript:goCarList();"> 
           
	</div>
</footer>
<?php echo form_close(); ?>

</article>
<script>
    function goCarList(){
        window.location.href="<?php echo site_url('admin/car'); ?>";
    }
</script>

