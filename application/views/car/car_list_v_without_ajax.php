<?php
$f_search = array(	//text field			
		'name'	=> 'f_search',			
	);
?>

<article class="module width_full">
    <header>
        <h3 class="tabs_involved"> Car List </h3>
        <div class="submit_link">
           <input type="submit" value="Add" class="alt_btn" onclick="return post_add()" />
        </div>
    </header>

    <div class="tab_container">
        <table class="tablesorter" cellspacing="0">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Car ID</th>
                    <th>User Name</th>
                    <th>Image</th>
                    <th>Activated</th>                    
                    <th>Registration</th>
                    <th>Price</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Fuel Type</th>
                    <th>Transmission</th>
                    <th>Mileage</th>
                    <th>Uploaded</th>
                    <th>Action</th>
                </tr>
             </thead>
             <tbody>
            <?php
            $i = 1;
            foreach($car_list as $row) {
                $user=$this->manage_m->get_specific_data($row['uid'], "users");
            ?>
                <tr>				
                     <td><?php echo $i+$start_no ; ?></td>
                    <td><?php echo $row['id'];?></td>
                    <td><?php echo $user['username'];?></td>
                     <td>
                        <?php if ($row['file_url_1']): ?>
                            <img src="<?php echo HOST.UPLOAD_PATH.$row['file_url_1'];?>" alt="No Image" height="80" width="80">
                        <?php else: ?>
                            <div style='color:red'> No Image </div>
                        <?php endif ?>
                    </td>                    
                    <td><?php echo $row['activated'] ? 'Yes' : "<div style='color:red'> No </div>";?></td>
                    <td><?php echo $row['registration'];?></td>
                    <td>&pound;<?php echo $row['price'];?></td>
                    <td><?php echo $row['make'];?></td>
                    <td><?php echo $row['model'];?></td>
                    <td><?php echo $row['year'];?></td>
                    <td><?php echo $row['fuel_type'];?></td>
                    <td><?php echo $row['transmission'];?></td>
                    <td><?php echo $row['mileage'];?></td>
                    <td>
                        <?php  $date = date_create($row['created']); $datetime = $date->format('Y-m-d'); echo $datetime;?>
                    </td>
                    
                    <td>
                        <input type="image" title="Edit" src="<?php echo IMG_DIR; ?>/icn_edit.png" onclick="goedit(<?php echo $row['id'];?>)">
                        <input type="image" title="Trash" src="<?php echo IMG_DIR; ?>/icn_trash.png" onclick="confirm_del(<?php echo $row['id'];?>)">
                    </td>			
                </tr>
            <?php
                $i++;
            }
            if($i==0) {
                    echo "<tr><td colspan='7' align='center'>Nothing </td></tr>";
            }
            ?>
</tbody>
        </table>
    </div>

    <script type="text/javascript">
            function post_add() {
                    window.location.href = "<?php echo site_url("admin/"."$post_key"."/".$post_key."_add"); ?>";
                    return false;
            }
            function confirm_del(pid) {
                    if(!confirm('Are you sure to delete?')) {
                            return;
                    }
                    window.location.href = "<?php echo site_url("admin/"."$post_key"."/".$post_key."_del"); ?>/" + pid;
            }
            function goedit(pid) {
                    window.location.href = "<?php echo site_url("admin/"."$post_key"."/".$post_key."_edit"); ?>/" + pid;
            }
    </script>

    <footer>
        <?php echo $pagenation; ?>
    </footer>
</article>