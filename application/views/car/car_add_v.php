<article class="module width_full">
<header>
	<h3 class="tabs_involved"> Add Car</h3>       
        
</header>
<?php
      

	echo form_open_multipart($this->uri->uri_string());
	
	if(!empty($show_message)) {
		echo "<h4 class='alert_success'>".$show_message."</h4>";
	}else{
		$this->form_validation->set_error_delimiters('<h4 class="alert_error">', '</h4>');
		
                echo form_error("registration");
                echo form_error("price");
                
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
    <table style="">
        
        <tr>
            <td> Registration : </td>
            <td>   <input type="text"  name="registration" /> &nbsp; </td>
            <td> Price : </td>
            <td>   <input type="text" size="15" name="price" /> &nbsp; </td>
        </tr>
    </table>
    <table style="">
        <tr>
            <td >Main Image : </td>
            <td> <input type="file" name="userfile[]" accept="image" ></td>
        </tr>
        <tr>
            <td> Image 1</td>
            <td> <input type="file" name="userfile[]" accept="image"></td>
            <td> Image 2</td>
            <td> <input type="file" name="userfile[]" accept="image" ></td>
        </tr>
        <tr>
            <td> Image 3</td>
            <td> <input type="file" name="userfile[]" ></td>
            <td> Image 4</td>
            <td> <input type="file" name="userfile[]" ></td>
        </tr>
    </table>

</div>

<footer>
	<div class="submit_link">
	<input type="submit" value="Add" class="alt_btn">
	</div>
</footer>

<?php echo form_close(); ?>

</article>


