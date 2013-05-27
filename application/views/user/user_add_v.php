<article class="module width_full">
<header>
	<h3 class="tabs_involved"> Add </h3>       
        
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
	/*$fverified = array(	//input field
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
	);*/
	
	echo form_open_multipart($this->uri->uri_string());
	
	if(!empty($show_message)) {
		echo "<h4 class='alert_success'>".$show_message."</h4>";
	}else{
		$this->form_validation->set_error_delimiters('<h4 class="alert_error">', '</h4>');
		echo form_error($femail['name']);
		echo form_error($fpassword['name']);
		echo form_error($fconfirm['name']);
                echo form_error($fusername['name']);
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
		<label>Username</label>
		<input type="text" name="<?php echo $fusername['name']; ?>" />		
	</fieldset>
	<fieldset >
		<label>Email</label>
		<input type="text" name="<?php echo $femail['name']; ?>" />
	</fieldset>
	<fieldset >
		<label>Password</label>
		<input type="password" name="<?php echo $fpassword['name']; ?>" />
	</fieldset>
    <fieldset >
		<label>Confirm</label>
		<input type="password" name="<?php echo $fconfirm['name']; ?>" />
	</fieldset>
    <fieldset >
		<label>Phone</label>
		<input type="text" name="<?php echo $fphone['name']; ?>" />		
	</fieldset>
    <fieldset >
		<label>Location</label>
		<input type="text" name="<?php echo $fcountry['name']; ?>" />		
	</fieldset>
   <!--   <fieldset >
		<label>Type</label>
		<input type="text" name="<?php echo $ftype['name']; ?>" />		
	</fieldset>
    <fieldset >
		<label>Verified Pt</label>
		<input type="text" name="<?php echo $fverified['name']; ?>" />		
	</fieldset>
    <fieldset >
		<label>Donated Pt</label>
		<input type="text" name="<?php echo $fdonated['name']; ?>" />		
	</fieldset>-->
	
</div>

<footer>
	<div class="submit_link">
	<input type="submit" value="Add" class="alt_btn">
	</div>
</footer>

<?php echo form_close(); ?>

</article>


