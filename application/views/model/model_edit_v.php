<article class="module width_full">
<header>
	<h3>
	Edit Model ( <?php echo $post['id']; ?> ) </h3>
</header>
<?php
	
	echo form_open_multipart($this->uri->uri_string());
	
	if(!empty($show_message)) {
		echo "<h4 class='alert_success'>".$show_message."</h4>";
	}else{
		$this->form_validation->set_error_delimiters('<h4 class="alert_error">', '</h4>');
		echo form_error('name');		
                echo form_error('make_id');
                
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
            <label>Model ID (Readonly)</label>
            <input type="text" name="id" value="<?php echo $post['id'] ?>" readonly="readonly"/>
    </fieldset>
     <fieldset >
            <label>Make ID </label>
            <input type="text" name="make_id" value="<?php echo $post['make_id'] ?>" />
    </fieldset>  
    <fieldset >
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $post['name'] ?>" />
    </fieldset>  
    <fieldset >
            <label>Description</label>
            <textarea name="desc" style="height: 100px;"><?php echo $post['desc'] ?></textarea>
    </fieldset>
    
</div>
<footer>
	<div class="submit_link">            
          
            <input type="submit" value="Update" class="alt_btn"> <input type="button" value="Cancel" class="alt_btn" onclick="javascript:goModelList();"> 
           
	</div>
</footer>
<?php echo form_close(); ?>

</article>
<script>
    function goModelList(){
        window.location.href="<?php echo site_url('admin/model'); ?>";
    }
</script>

