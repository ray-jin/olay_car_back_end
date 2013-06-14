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
                    <th width="30">No.</th>
                    <th width="30">Car ID</th>
                    <th width="100">User Name</th>
                    <th width="100">Image</th>
                    <th width="80">Reg</th>
                    <th width="100">Activated</th>                    
                    <th width="100">Make</th>
                    <th width="100">Transmission</th>
                    <th width="80">Bodytype</th>
                    <th width="80">Colour</th>
                    <th width="80">Country</th>
                    <th width="80">Currency</th>
                    <th width="80">Website</th>
                    <th width="80">Desc</th>
                    <th width="80">Engine_Size</th>
                    <th width="80">Fuel_type</th>
                    <th width="80">Gearbox</th>
                    <th width="80">Mileage</th>
                    <th width="80">Mileageunits</th>
                    <th width="80">Model</th>
                    <th width="80">Number of Doors</th>
                    <th width="80">Car Options</th>
                    <th width="80">PostCode</th>
                    <th width="80">Price</th>
                    <th width="80">Purchase Type</th>
                    <th width="80">Region</th>                    
                    <th width="80">Roadtax</th>
                    <th width="80">Sales Type</th>
                    <th width="80">Tested Until</th>
                    <th width="80">Trade Price</th>
                    <th width="80">Vin Number</th>
                    <th width="80">Warranty</th>
                    <th width="80">Year</th>
                    <th width="80">Created</th>
                    <th width="80">Modified</th>
                    <th width="80">Action</th>
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
                        <?php if ($row['car_img_url_1']): ?>
                            <img src="<?php echo HOST.UPLOAD_PATH.$row['car_img_url_1'];?>" alt="No Image" height="80" width="80">
                        <?php else: ?>
                            <div style='color:red'> No Image </div>
                        <?php endif ?>
                    </td>
                    <td><?php echo $row['registration'];?></td>
                    <td><?php echo $row['activated'];?></td>
                    <td><?php echo $row['make'];?></td>
                    <td><?php echo $row['transmission'];?></td>
                    <td><?php echo $row['bodytype'];?></td>                  
                    <td><?php echo $row['colour'];?></td>
                    <td><?php echo $row['country'];?></td>
                    <td><?php echo $row['currency'];?></td>
                    <td><?php echo $row['website'];?></td>
                    <td><?php echo $row['desc'];?></td>
                    <td><?php echo $row['engine_size'];?></td>
                    <td><?php echo $row['fuel_type'];?></td>
                    <td><?php echo $row['gearbox'];?></td>
                    <td><?php echo $row['mileage'];?></td>
                    <td><?php echo $row['mileageunits'];?></td>
                    <td><?php echo $row['model'];?></td>
                    <td><?php echo $row['num_doors'];?></td>                      
                    <td><?php echo $row['car_options'];?></td>
                    <td><?php echo $row['postcode'];?></td>
                    <td><?php echo $row['price'];?></td>
                    <td><?php echo $row['purchase_type'];?></td>
                    <td><?php echo $row['region'];?></td>
                    <td><?php echo $row['roadtax'];?></td>
                    <td><?php echo $row['sales_type'];?></td>
                    <td><?php echo $row['tested_until'];?></td>
                    <td><?php echo $row['trade_price'];?></td>
                    <td><?php echo $row['vin_number'];?></td>
                    <td><?php echo $row['warranty'];?></td>
                    <td><?php echo $row['year'];?></td>
                    <td><?php echo $row['created'];?></td>
                    <td><?php echo $row['modified'];?></td>
                    
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