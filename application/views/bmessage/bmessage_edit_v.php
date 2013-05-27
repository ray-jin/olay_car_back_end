<article class="module width_full">
<header>
	<h3>
	Edit ( <?php echo $post['id']; ?> ) </h3>
</header>
<?php
	
        $message_id = array(	//id of message id
		'name'	=> 'id',
		'id'	=> 'id',
		'value' => set_value('id'),
		'title'	=> 'Message ID',
	);
         
        $sender_id = array(	//id of user id
		'name'	=> 'sender_id',
		'id'	=> 'sender_id',
		'value' => set_value('sender_id'),
		'title'	=> 'Sender ID',
	);
        
        $receiver_id = array(	//id of user id
		'name'	=> 'receiver_id',
		'id'	=> 'receiver_id',
		'value' => set_value('receiver_id'),
		'title'	=> 'Receiver ID',
	);        
        $message = array(	//id of user id
		'name'	=> 'message',
		'id'	=> 'message',
		'value' => set_value('message'),
		'title'	=> 'Message',
	);
	
	
	echo form_open_multipart($this->uri->uri_string());
	
	if(!empty($show_message)) {
		echo "<h4 class='alert_success'>".$show_message."</h4>";
	}else{
		$this->form_validation->set_error_delimiters('<h4 class="alert_error">', '</h4>');
		echo form_error($sender_id['name']);
		echo form_error($receiver_id['name']);
                echo form_error($message['name']);
        
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
    <input type="hidden" name="<?php echo $message_id['name']; ?>" value="<?php echo $post['id'] ?>"/>
    <fieldset >
            <label>User ID (Readonly) </label>
            <input type="text" name="<?php echo $sender_id['name']; ?>" value="<?php echo $post['sender_id'] ?>" readonly="readonly"/>
    </fieldset>
    <fieldset >
            <label>Car ID (Readonly) </label>
            <input type="text" name="<?php echo $receiver_id['name']; ?>" value="<?php echo $post['receiver_id'] ?>" readonly="readonly"/>		
    </fieldset>
    
    <fieldset >
            <label>Message</label>
            <textarea name="<?php echo $message['name']; ?>" style="height: 50px;"><?php echo $post['message'] ?></textarea>
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
        window.location.href="<?php echo site_url('backend/bmessage'); ?>";
    }
</script>

