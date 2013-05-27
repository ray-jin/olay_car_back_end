<article class="module width_full">
<header>
	<h3>
	Edit ( <?php echo $post['username']; ?> ) </h3>
</header>
<?php
	$fusername = array(	//input field
		'name'	=> 'fusername',
		'id'	=> 'fusername',
		'value' => set_value('fusername'),
		'title'	=> 'UserName',
	);
        $fid = array(	//id of user table
		'name'	=> 'fid',
		'id'	=> 'fid',
		'value' => set_value('fid'),
		'title'	=> 'fid',
	);
	$fphone = array(	//input field
		'name'	=> 'fphone',
		'id'	=> 'fphone',
		'value' => set_value('fphone'),
		'title'	=> 'Phone',
	);
	$floc = array(	//input field
		'name'	=> 'floc',
		'id'	=> 'floc',
		'value' => set_value('floc'),
		'title'	=> 'Location',
	);
        $fbanned = array(	//checkbox field
		'name'	=> 'fbanned',
		'id'	=> 'fbanned',
		'value' => set_value('fbanned'),
		'title'	=> 'Ban this user',
	);
	$ftype = array(	//input field
		'name'	=> 'ftype',
		'id'	=> 'ftype',
		'value' => set_value('ftype'),
		'title'	=> 'Type',
	);
	$fverified = array(	//input field
		'name'	=> 'fverified',
		'id'	=> 'fverified',
		'value' => set_value('fverified'),
		'title'	=> 'Verified Pt',
	);
	$fdonated = array(	//input field
		'name'	=> 'fdonated',
		'id'	=> 'fdonated',
		'value' => set_value('fdonated'),
		'title'	=> 'Donated Pt',
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
	
	echo form_open_multipart($this->uri->uri_string());
	
	if(!empty($show_message)) {
		echo "<h4 class='alert_success'>".$show_message."</h4>";
	}else{
		$this->form_validation->set_error_delimiters('<h4 class="alert_error">', '</h4>');
		echo form_error($femail['name']);
		echo form_error($fpassword['name']);
                echo form_error($fusername['name']);
                
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
    <input type="hidden" name="<?php echo $fid['name']; ?>" value="<?php echo $post['id'] ?>"/>
    <fieldset >
            <label>Username</label>
            <input type="text" name="<?php echo $fusername['name']; ?>" value="<?php echo $post['username'] ?>" readonly="1"/>
    </fieldset>
    <fieldset >
		<label>Email</label>
		<input type="text" name="<?php echo $femail['name']; ?>" value="<?php echo $post['email'] ?>"/>		
	</fieldset>
    <fieldset >
		<label>Password</label>
		<input type="password" name="<?php echo $fpassword['name']; ?>" value="<?php echo $post['password'] ?>"/>		
	</fieldset>
    <fieldset >
		<label>Phone</label>
		<input type="text" name="<?php echo $fphone['name']; ?>" value="<?php echo $post['phone'] ?>"/>		
	</fieldset>
    <fieldset >
		<label>Location</label>
		<input type="text" name="<?php echo $floc['name']; ?>" value="<?php echo $post['loc'] ?>"/>		
	</fieldset>
  <!--  <fieldset >
		<label>Type</label>
		<input type="text" name="<?php echo $ftype['name']; ?>" value="<?php echo $post['type'] ?>"/>		
	</fieldset>
    <fieldset >
		<label>Verified Pt</label>
		<input type="text" name="<?php echo $fverified['name']; ?>" value="<?php echo $post['verified_pt'] ?>"/>		
	</fieldset>
    <fieldset >
		<label>Donated Pt</label>
		<input type="text" name="<?php echo $fdonated['name']; ?>" value="<?php echo $post['donated_pt'] ?>"/>		
	</fieldset>-->
	
</div>
<footer>
	<div class="submit_link">            
            banned<input type="checkbox" name="<?php echo $fbanned['name']; ?>" class="banuser" <?php if ($post['banned']=='1') echo "checked";?>>
            <input type="submit" value="Update" class="alt_btn"> <input type="button" value="Cancel" class="alt_btn" onclick="javascript:window.history.back();"> 
	</div>
</footer>
<?php echo form_close(); ?>

</article>


