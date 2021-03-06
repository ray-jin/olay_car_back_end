<article class="module width_full">
<header>
	<h3>
	Edit ( <?php echo $post['id']; ?> ) </h3>
</header>
<?php
	
        $offer_id = array(	//id of offer id
		'name'	=> 'id',
		'id'	=> 'id',
		'value' => set_value('id'),
		'title'	=> 'offer ID',
	);
         
        $user_id = array(//id of user id
		'name'	=> 'uid',
		'id'	=> 'uid',
		'value' => set_value('uid'),
		'title'	=> 'User ID',
	);
        
        $car_id = array(	//id of user id
		'name'	=> 'cid',
		'id'	=> 'cid',
		'value' => set_value('cid'),
		'title'	=> 'Car ID',
	);
        $price = array(	//id of user id
		'name'	=> 'price',
		'id'	=> 'price',
		'value' => set_value('price'),
		'title'	=> 'Price',
	);
        $message = array(	//id of user id
		'name'	=> 'message',
		'id'	=> 'message',
		'value' => set_value('message'),
		'title'	=> 'M',
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
    <input type="hidden" name="<?php echo $offer_id['name']; ?>" value="<?php echo $post['id'] ?>"/>
    <fieldset >
            <label>User ID (Readonly)</label>
            <input type="text" name="<?php echo $user_id['name']; ?>" value="<?php echo $post['uid'] ?>" readonly="readonly"/>
    </fieldset>
    <fieldset >
            <label>Car ID (readonly)</label>
            <input type="text" name="<?php echo $car_id['name']; ?>" value="<?php echo $post['cid'] ?>" readonly="readonly"/>		
    </fieldset>
    <fieldset >
            <label>Price</label>
            <input type="text" name="<?php echo $price['name']; ?>" value="<?php echo $post['price'] ?>" />
    </fieldset>
    <fieldset >
            <label>Message</label>
            <textarea name="<?php echo $message['name']; ?>" style="height: 50px;"><?php echo $post['message'] ?></textarea>
    </fieldset>
    
</div>
<footer>
	<div class="submit_link">            
          
            <input type="submit" value="Update" class="alt_btn"> <input type="button" value="Cancel" class="alt_btn" onclick="javascript:goOfferList();"> 
           
	</div>
</footer>
<?php echo form_close(); ?>

</article>
<script>
    function goOfferList(){
        window.location.href="<?php echo site_url('admin/offer'); ?>";
    }
</script>

