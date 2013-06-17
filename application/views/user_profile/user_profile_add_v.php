<article class="module width_full">
<header>
	<h3 class="tabs_involved"> Add Profile</h3>       
        
</header>
<?php
	$fconfirm = array(	//input field
		'name'	=> 'fconfirm',
		'id'	=> 'fconfirm',
		'value' => set_value('fconfirm'),
		'title'	=> 'Confirm',
	);
	$fpassword = array(	//input field
		'name'	=> 'fpassword',
		'id'	=> 'fpassword',
		'value' => set_value('fpassword'),
		'title'	=> 'Password',
	);
	$femail = array(	//input field
		'name'	=> 'femail',
		'id'	=> 'femail',
		'value' => set_value('femail'),
		'title'	=> 'Email',		
	);
	$fusername = array(	//input field
		'name'	=> 'fusername',
		'id'	=> 'fusername',
		'value' => set_value('fusername'),
		'title'	=> 'UserName',
	);
	$fphone = array(	//input field
		'name'	=> 'fphone',
		'id'	=> 'fphone',
		'value' => set_value('fphone'),
		'title'	=> 'Phone',
	);
	$fcountry = array(	//input field
		'name'	=> 'floc',
		'id'	=> 'floc',
		'value' => set_value('floc'),
		'title'	=> 'floc',
	);
	$ftype = array(	//input field
		'name'	=> 'ftype',
		'id'	=> 'ftype',
		'value' => set_value('ftype'),
		'title'	=> 'Type',
	);
	
	
	echo form_open_multipart($this->uri->uri_string());
	
	if(!empty($show_message)) {
		echo "<h4 class='alert_success'>".$show_message."</h4>";
	}else{
		$this->form_validation->set_error_delimiters('<h4 class="alert_error">', '</h4>');                
                $this->form_validation->set_rules('loc', 'postcode', 'trim');
                
		echo form_error("user_id");
                echo form_error("loc");
		
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
		<label>User Id</label>
		<input type="text" name="user_id" value=""/>
    </fieldset>
    <fieldset >
		<label>Full name</label>
		<input type="text" name="ufuname" value=""/>
    </fieldset>
    <fieldset >
		<label>Current Car</label>
		<input type="text" name="current_car" value=""/>
    </fieldset>
    <fieldset >
		<label>About me</label>
                <textarea type="text" name="about_me" rows="10"></textarea>
    </fieldset>
    <fieldset >
		<label>Postcode</label>
                <input type="text" name="loc" value="" maxlength="4" />
    </fieldset>
	
</div>

<footer>
	<div class="submit_link">
	<input type="submit" value="Add" class="alt_btn">
	</div>
</footer>

<?php echo form_close(); ?>

</article>


