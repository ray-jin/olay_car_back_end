<?php
$f_search = array(	//text field			
		'name'	=> 'f_search',			
	);
?>
<form name="frmSearch"  method="post" action="<?php  echo site_url("admin/offer") ?>" >
    <article class="module width_full">
            <header>
                    <h3> Search </h3>
            </header>
            <div class="module_content">
                User Name :  <input type="text" style="width : 150px;" name="username" value="<?php echo $username; ?>" />  &nbsp;                
                <input type="submit" name="fsubmit" value="Search" class="alt_btn">
            </div>
                    
    </article>
</form>
<article class="module width_full">
    <header>
        <h3 class="tabs_involved"> offer List </h3>
        <div class="submit_link">
           <input type="submit" value="Add" class="alt_btn" onclick="return post_add()" />
        </div>
    </header>

    <div class="tab_container">
        <table class="tablesorter" cellspacing="0">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th width="30">Offer ID</th>
                    <th width="100">User Name (by)</th>
                    <th width="100">Car ID</th>
                    <th width="100">Price</th>
                    <th width="100">Message</th>
                    <th width="100">Created</th>
                    <th width="80">Actions</th>
                </tr>
             </thead>
             <tbody>
            <?php
            $i = 1;
            foreach($offer_list as $row) {
                 $user=$this->manage_m->get_specific_data($row['uid'], "users");
            ?>
                <tr>				
                    <td><?php echo $i+$start_no; //$row['id'];?></td>
                    <td><?php echo $row['id'];?></td>
                    <td><?php echo sizeof($user) ? $user['username'] : "<div style='color:red'> No Available User </div>";?></td>
                    <td><?php echo $row['cid'];?></td>
                    <td><?php echo $row['price'];?></td>
                    <td><?php echo $row['message'];?></td>
                    <td><?php echo $row['created'];?></td>                  
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