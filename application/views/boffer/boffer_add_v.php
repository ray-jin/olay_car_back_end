<article class="module width_full">
<header>
	<h3 class="tabs_involved"> Add </h3>       
        
</header>
<?php
	 $offer_id = array(	//id of offer id
		'name'	=> 'id',
		'id'	=> 'id',
		'value' => set_value('id'),
		'title'	=> 'offer ID',
	);
         
        $user_id = array(	//id of user id
		'name'	=> 'uid',
		'id'	=> 'uid',
		'value' => set_value('uid'),
		'title'	=> 'User ID',
	);
        
        $car_id = array(	//id of car id
		'name'	=> 'cid',
		'id'	=> 'cid',
		'value' => set_value('cid'),
		'title'	=> 'Car ID',
	);
        
        $price = array(	//id of price id
		'name'	=> 'price',
		'id'	=> 'price',
		'value' => set_value('price'),
		'title'	=> 'Price',
	);
        
        $message= array(	//id of user id
		'name'	=> 'message',
		'id'	=> 'message',
		'value' => set_value('message'),
		'title'	=> 'Message',
	);
        
        $created = array(	//id of car id
		'name'	=> 'created',
		'id'	=> 'created',
		'value' => set_value('created'),
		'title'	=> 'Created',
	);
	
	echo form_open_multipart($this->uri->uri_string());
	
	if(!empty($show_message)) {
		echo "<h4 class='alert_success'>".$show_message."</h4>";
	}else{
		$this->form_validation->set_error_delimiters('<h4 class="alert_error">', '</h4>');
		echo form_error($user_id['name']);
		echo form_error($car_id['name']);
                echo form_error($price['name']);
                
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
		<input type="text" name="<?php echo $user_id['name']; ?>" />		
	</fieldset>
	<fieldset >
		<label>Car ID</label>
		<input type="text" name="<?php echo $car_id['name']; ?>" />
	</fieldset>
        <fieldset >
		<label>Price</label>
		<input type="text" name="<?php echo $price['name']; ?>" />
	</fieldset>
        <fieldset >
		<label>Message (Optional)</label>
		
                <textarea name="<?php echo $message['name']; ?>" style="height: 50px;"></textarea>
	</fieldset>	
</div>

<footer>
	<div class="submit_link">
	<input type="submit" value="Add" class="alt_btn">
	</div>
</footer>

<?php echo form_close(); ?>

</article>


