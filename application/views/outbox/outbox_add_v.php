<article class="module width_full">
<header>
	<h3 class="tabs_involved"> Add </h3>       
        
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
        
        $receiver_id = array(	//id of car id
		'name'	=> 'receiver_id',
		'id'	=> 'receiver_id',
		'value' => set_value('receiver_id'),
		'title'	=> 'Receiver ID',
	);
        
       
        
        $message= array(	//Message
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
		echo form_error($sender_id['name']);
		echo form_error($receiver_id['name']);
                echo form_error($message['name']);
                
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
		<label>Sender ID</label>
		<input type="text" name="<?php echo $sender_id['name']; ?>" />		
	</fieldset>
	<fieldset >
		<label>Receiver ID</label>
		<input type="text" name="<?php echo $receiver_id['name']; ?>" />
	</fieldset>
        <fieldset >
		<label>Message</label>
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


