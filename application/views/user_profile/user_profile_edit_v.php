<article class="module width_full">
<header>
	<h3>
	Edit Profile ( <?php echo $post['username']; ?> ) </h3>
</header>
<?php
        
        $user_profile=$this->manage_m->get_specific_data($post['user_profile_id'], "user_profiles");
        
        // No user file -> create new record in user profile page
        if (!$user_profile){
            $qry =                     
                array(
                        'user_id'  => $post['id'],
                        'ufuname' => "",
                        'image_loc' => "",
                        'current_car' => "",
                        'about_me' => "",
                        'loc'	=> "",
                );
            if(!$this->db->insert("user_profiles", $qry)){
                echo "Error in creating user profile";
                return;
            }
            $new_profile_id=$this->db->insert_id();
            
            $user_profile=$this->manage_m->get_specific_data($new_profile_id, "user_profiles");
            
            //update user table;
            $qry =                     
                array(
                        'user_profile_id'  => $user_profile['id'],
                );
            $this->db->where("id",$post['id']);
            if(!$this->db->update("users", $qry)){
                echo "Error in creating user profile";
                return;
            }
            
            
        }
        
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
	
	$floc = array(	//input field
		'name'	=> 'floc',
		'id'	=> 'floc',
		'value' => set_value('floc'),
		'title'	=> 'Location',
	);
    
	
	echo form_open_multipart($this->uri->uri_string());
	
	if(!empty($show_message)) {
		echo "<h4 class='alert_success'>".$show_message."</h4>";
	}else{
		$this->form_validation->set_error_delimiters('<h4 class="alert_error">', '</h4>');
		echo form_error('loc');
		echo form_error('ufuname');
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
    <input type="hidden" name="img_loc" value="<?php echo $user_profile['image_loc']; ?>"/>
    <fieldset>  
            <label style="width:100%;">Username : <?php echo $post['username'] ?></label>
            <img src="<?php echo HOST.UPLOAD_PATH.$user_profile['image_loc'];?>"
                 alt="No Image" height="80" width="80" style="padding-left: 10px;">
            &nbsp;&nbsp; <input type="file" name="profile_img" />
    </fieldset>
    <fieldset>
            <label style="width:100%;">Email : <?php echo $post['email'] ?></label>
    </fieldset>
    <fieldset >
		<label>Full name</label>
		<input type="text" name="ufuname" value="<?php echo $user_profile['ufuname']?>"/>
    </fieldset>
    <fieldset >
		<label>Current Car</label>
		<input type="text" name="current_car" value="<?php echo $user_profile['current_car']?>"/>
    </fieldset>
    <fieldset >
		<label>About me</label>
                <textarea type="text" name="about_me" rows="10"><?php echo $user_profile['about_me']?></textarea>
    </fieldset>
    <fieldset >
		<label>Postcode</label>
                <input type="text" name="loc" value="<?php echo $user_profile['loc']?>" maxlength="4" />
    </fieldset>
   
</div>
<footer>
	<div class="submit_link">
            <input type="submit" value="Update" class="alt_btn"> 
            <input type="button" value="Cancel" class="alt_btn" onclick="javascript:goUser_ProfilesList();"> 
	</div>
</footer>
<?php echo form_close(); ?>

</article>
<script>
    function goUser_ProfilesList(){
        window.location.href="<?php echo site_url('admin/user_profile'); ?>";
    }
</script>

