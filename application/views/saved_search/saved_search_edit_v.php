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
		echo form_error('uid');
                echo form_error('search_string');		
        
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
    
    <fieldset >
            <label>Save Search ID (Readonly)</label>
            <input type="text" name="id" value="<?php echo $post['id'] ?>" readonly="readonly"/>
    </fieldset>
    <fieldset >
            <label>User Id</label>
            <input type="text" name="uid" value="<?php echo $post['uid'] ?>" />
    </fieldset>  
    <fieldset >
            <label>Search String</label>
            <textarea name="search_string" style="height: 100px;"><?php echo $post['search_string'] ?></textarea>
    </fieldset>
    
    
</div>
<footer>
	<div class="submit_link">            
          
            <input type="submit" value="Update" class="alt_btn"> <input type="button" value="Cancel" class="alt_btn" onclick="javascript:goSaved_searchList();"> 
           
	</div>
</footer>
<?php echo form_close(); ?>

</article>
<script>
    function goSaved_searchList(){
        window.location.href="<?php echo site_url('admin/saved_search'); ?>";
    }
</script>

