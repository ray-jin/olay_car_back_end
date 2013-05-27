<article class="module width_full">
<header>
	<h3 class="tabs_involved"> Add </h3>       
        
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
	//	echo form_error($receiver_id['name']);
          //      echo form_error($message['name']);
                
		if (isset($show_errors)) {?>
                    <h4 class="alert_error">
                        <?php if (is_array($show_errors)) {?>
                        <?php foreach ($show_errors as $error) :?>
                                <label><?php echo $error?></label>
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
	<fieldset >
		<label>User ID</label>
		<input type="text" name="<?php echo $uid['name']; ?>" />		
	</fieldset>
	<fieldset >
		<label>Activated</label>
		<input type="text" name="<?php echo $activated['name']; ?>" />
	</fieldset>
        <fieldset >
		<label>Make</label>
		<input type="text" name="<?php echo $make['name']; ?>" />
	</fieldset>
        <fieldset >
		<label>Transmission</label>
		<input type="text" name="<?php echo $transmission['name']; ?>" />
	</fieldset>
        <fieldset >
		<label>Body type</label>
		<input type="text" name="<?php echo $bodytype['name']; ?>" />
	</fieldset>
        <fieldset >
		<label>Colour</label>
		<input type="text" name="<?php echo $colour['name']; ?>" />
	</fieldset>
        <fieldset >
		<label>Country</label>
		<input type="text" name="<?php echo $country['name']; ?>" />
	</fieldset>
        <fieldset >
		<label>Currency</label>
		<input type="text" name="<?php echo $currency['name']; ?>" />
	</fieldset>
        <fieldset >
		<label>Website</label>
		<input type="text" name="<?php echo $website['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Description</label>
                <textarea name="<?php echo $desc['name']; ?>"></textarea>
	</fieldset>
        <fieldset>
		<label>Engine Size</label>                
                <input type="text" name="<?php echo $engine_size['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Fuel Type</label>
                <input type="text" name="<?php echo $fuel_type['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Gear Box</label>                
                <input type="text" name="<?php echo $gearbox['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Mileage</label>                
                <input type="text" name="<?php echo $mileage['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Mileage Units</label>                
                <input type="text" name="<?php echo $mileageunits['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Model</label>
                <input type="text" name="<?php echo $model['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Number of Doors</label>                
                <input type="text" name="<?php echo $num_doors['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Number of Owners</label>                
                <input type="text" name="<?php echo $num_owners['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Car options</label>                
                <input type="text" name="<?php echo $car_options['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Postal Code</label>
                <input type="text" name="<?php echo $postcode['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Price</label>                
                <input type="text" name="<?php echo $price['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Purchase Type</label>
                <input type="text" name="<?php echo $purchase_type['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Region</label>                
                <input type="text" name="<?php echo $region['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Registrations</label>                                
                <input type="text" name="<?php echo $registration['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Roadtax</label>
                
                <input type="text" name="<?php echo $roadtax['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Sales Type</label>
                
                <input type="text" name="<?php echo $sales_type['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Tested Until</label>                
                <input type="text" name="<?php echo $tested_until['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Trade Price</label>                
                <input type="text" name="<?php echo $trade_price['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Vin Number</label>                
                <input type="text" name="<?php echo $vin_number['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Warranty</label>                
                <input type="text" name="<?php echo $warranty['name']; ?>" />
	</fieldset>
        <fieldset>
		<label>Year</label>                
                <input type="text" name="<?php echo $year['name']; ?>" />
	</fieldset>
        
        
</div>

<footer>
	<div class="submit_link">
	<input type="submit" value="Add" class="alt_btn">
	</div>
</footer>

<?php echo form_close(); ?>

</article>


