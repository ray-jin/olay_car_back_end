<article class="module width_full">
<header>
	<h3 class="tabs_involved"> Add (Model)</h3>
        
</header>
<?php
        
	echo form_open_multipart($this->uri->uri_string());
	
	if(!empty($show_message)) {
		echo "<h4 class='alert_success'>".$show_message."</h4>";
	}else{
		$this->form_validation->set_error_delimiters('<h4 class="alert_error">', '</h4>');
		echo form_error('make_id');
                echo form_error('name');
                
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
		<label>make id</label>
		<input type="text" name="make_id" />		
	</fieldset> 
	<fieldset >
		<label>Model Name</label>
		<input type="text" name="name" />		
	</fieldset>        
        <fieldset >
		<label>Description (Optional)</label>
                <textarea name="desc" style="height: 100px;"></textarea>
	</fieldset>	
</div>

<footer>
	<div class="submit_link">
	<input type="submit" value="Add" class="alt_btn">
	</div>
</footer>

<?php echo form_close(); ?>

</article>


