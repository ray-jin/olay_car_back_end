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
        
        
        $make= array(	//car
		'name'	=> 'make',
		'id'	=> 'make',
		'value' => set_value('make'),
		'title'	=> 'make',
	);
        
        $transmission = array(	
		'name'	=> 'transmission',
		'id'	=> 'transmission',
		'value' => set_value('transmission'),
		'title'	=> 'Transmission',
	);
        
        $bodytype = array(	
		'name'	=> 'bodytype',
		'id'	=> 'bodytype',
		'value' => set_value('bodytype'),
		'title'	=> 'bodytype',
	);
        
        $colour = array(	
		'name'	=> 'colour',
		'id'	=> 'colour',
		'value' => set_value('colour'),
		'title'	=> 'colour',
	);
        
        $country = array(	
		'name'	=> 'country',
		'id'	=> 'country',
		'value' => set_value('country'),
		'title'	=> 'country',
	);
        $currency = array(	
		'name'	=> 'currency',
		'id'	=> 'currency',
		'value' => set_value('currency'),
		'title'	=> 'currency',
	);
        $website= array(	
		'name'	=> 'website',
		'id'	=> 'website',
		'value' => set_value('website'),
		'title'	=> 'website',
	);
        
        $desc = array(	
		'name'	=> 'desc',
		'id'	=> 'desc',
		'value' => set_value('desc'),
		'title'	=> 'desc',
	);
        
        $engine_size = array(	
		'name'	=> 'engine_size',
		'id'	=> 'engine_size',
		'value' => set_value('engine_size'),
		'title'	=> 'engine_size',
	);
        
        $fuel_type = array(	
		'name'	=> 'fuel_type',
		'id'	=> 'fuel_type',
		'value' => set_value('fuel_type'),
		'title'	=> 'fuel_type',
	);
        
        $gearbox = array(	
		'name'	=> 'gearbox',
		'id'	=> 'gearbox',
		'value' => set_value('gearbox'),
		'title'	=> 'gearbox',
	);
        
        $mileage = array(	
		'name'	=> 'mileage',
		'id'	=> 'mileage',
		'value' => set_value('mileage'),
		'title'	=> 'mileage',
	);
        
        $mileageunits = array(	
		'name'	=> 'mileageunits',
		'id'	=> 'mileageunits',
		'value' => set_value('mileageunits'),
		'title'	=> 'mileageunits',
	);
        
        $model = array(	
		'name'	=> 'model',
		'id'	=> 'model',
		'value' => set_value('model'),
		'title'	=> 'model',
	);
        
        $num_doors= array(	
		'name'	=> 'num_doors',
		'id'	=> 'num_doors',
		'value' => set_value('num_doors'),
		'title'	=> 'Number of Doors',
	);
        
        $num_owners = array(	
		'name'	=> 'num_owners',
		'id'	=> 'num_owners',
		'value' => set_value('num_owners'),
		'title'	=> 'num_owners',
	);
        
        $car_options= array(	
		'name'	=> 'car_options',
		'id'	=> 'car_options',
		'value' => set_value('car_options'),
		'title'	=> 'car_options',
	);
        
        $postcode= array(	
		'name'	=> 'postcode',
		'id'	=> 'postcode',
		'value' => set_value('postcode'),
		'title'	=> 'postcode',
	);
        
        $price= array(	
		'name'	=> 'price',
		'id'	=> 'price',
		'value' => set_value('price'),
		'title'	=> 'price',
	);
        
        $purchase_type= array(	
		'name'	=> 'purchase_type',
		'id'	=> 'purchase_type',
		'value' => set_value('purchase_type'),
		'title'	=> 'purchase_type',
	);
        
        $region= array(	
		'name'	=> 'region',
		'id'	=> 'region',
		'value' => set_value('region'),
		'title'	=> 'region',
	);
        
        $registration= array(	
		'name'	=> 'registration',
		'id'	=> 'registration',
		'value' => set_value('registration'),
		'title'	=> 'registration',
	);
        
        $roadtax= array(	
		'name'	=> 'roadtax',
		'id'	=> 'roadtax',
		'value' => set_value('roadtax'),
		'title'	=> 'roadtax',
	);
        
        $sales_type= array(	
		'name'	=> 'sales_type',
		'id'	=> 'sales_type',
		'value' => set_value('sales_type'),
		'title'	=> 'sales_type',
	);
        
        $tested_until= array(	
		'name'	=> 'tested_until',
		'id'	=> 'tested_until',
		'value' => set_value('tested_until'),
		'title'	=> 'tested_until',
	);
        
        $trade_price= array(	
		'name'	=> 'trade_price',
		'id'	=> 'trade_price',
		'value' => set_value('trade_price'),
		'title'	=> 'trade_price',
	);
        
        $vin_number= array(	
		'name'	=> 'vin_number',
		'id'	=> 'vin_number',
		'value' => set_value('vin_number'),
		'title'	=> 'vin_number',
	);
        
        $warranty= array(	
		'name'	=> 'warranty',
		'id'	=> 'warranty',
		'value' => set_value('warranty'),
		'title'	=> 'warranty',
	);
        
        $year= array(	
		'name'	=> 'year',
		'id'	=> 'year',
		'value' => set_value('year'),
		'title'	=> 'year',
	);
        
        $created= array(	
		'name'	=> 'created',
		'id'	=> 'created',
		'value' => set_value('created'),
		'title'	=> 'created',
	);
        
        $modified= array(	
		'name'	=> 'modified',
		'id'	=> 'modified',
		'value' => set_value('modified'),
		'title'	=> 'modified',
	);
	
	
	echo form_open_multipart($this->uri->uri_string());
	
	if(!empty($show_message)) {
		echo "<h4 class='alert_success'>".$show_message."</h4>";
	}else{
		$this->form_validation->set_error_delimiters('<h4 class="alert_error">', '</h4>');
		echo form_error($uid['name']);
		//echo form_error($receiver_id['name']);
                //echo form_error($message['name']);
        
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
?>

<div class="module_content">
    <input type="hidden" name="<?php echo $car_id['name']; ?>" value="<?php echo $post['id'] ?>"/>
   <fieldset >
            <label>User ID</label>
            <input type="text" name="<?php echo $uid['name']; ?>" value="<?php echo $post['uid']?>" readonly="readonly"/>
    </fieldset>
    <fieldset >
            <label>Activated</label>
            <input type="text" name="<?php echo $activated['name']; ?>" value="<?php echo $post['activated']?>" />
    </fieldset>
    <fieldset >
            <label>Make</label>
            <input type="text" name="<?php echo $make['name']; ?>" value="<?php echo $post['make']?>"/>
    </fieldset>
    <fieldset >
            <label>Transmission</label>
            <input type="text" name="<?php echo $transmission['name']; ?>" value="<?php echo $post['transmission']?>"/>
    </fieldset>
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
            <label>Fuel Type</label>
            <input type="text" name="<?php echo $fuel_type['name']; ?>" value="<?php echo $post['fuel_type']?>"/>
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
            <label>Model</label>
            <input type="text" name="<?php echo $model['name']; ?>" value="<?php echo $post['model']?>"/>
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
            <label>Postal Code</label>
            <input type="text" name="<?php echo $postcode['name']; ?>" value="<?php echo $post['postcode']?>"/>
    </fieldset>
    <fieldset>
            <label>Price</label>                
            <input type="text" name="<?php echo $price['name']; ?>" value="<?php echo $post['price']?>"/>
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
            <label>Registration</label>                                
            <input type="text" name="<?php echo $registration['name']; ?>" value="<?php echo $post['registration']?>"/>
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
            <label>Trade Price</label>                
            <input type="text" name="<?php echo $trade_price['name']; ?>" value="<?php echo $post['trade_price']?>"/>
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
            <label>Year</label>                
            <input type="text" name="<?php echo $year['name']; ?>" value="<?php echo $post['year']?>"/>
    </fieldset>
    <fieldset>
            <label>Created</label>                
            <input type="text" name="<?php echo $created['name']; ?>" value="<?php echo $post['created']?>" readonly="readonly"/>
    </fieldset>	
    
</div>
<footer>
	<div class="submit_link">            
          
            <input type="submit" value="Update" class="alt_btn"> <input type="button" value="Cancel" class="alt_btn" onclick="javascript:gomessageList();"> 
           
	</div>
</footer>
<?php echo form_close(); ?>

</article>
<script>
    function gomessageList(){
        window.location.href="<?php echo site_url('backend/bcar'); ?>";
    }
</script>

