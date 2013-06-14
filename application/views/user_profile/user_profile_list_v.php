<?php
$f_search = array(	//text field			
		'name'	=> 'f_search',			
	);
?>

<article class="module width_full">
    <header>
        <h3 class="tabs_involved"> User Profile List </h3>
    </header>

    <div class="tab_container">
        <table class="tablesorter" cellspacing="0">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th width="100">Name</th>
                    <th width="100">Email</th>
                    <th width="100">Fullname</th>
                    <th width="100">Image</th>
                    <th width="100">Current Car</th>
                    <th width="100">About me</th>
                    <th width="100">Postcode</th>
                    <th width="80">Actions</th>
                </tr>
             </thead>
             <tbody>
            <?php
            $i = 0;
            foreach($user_list as $row) {
                $user_profile=$this->manage_m->get_specific_data($row['user_profile_id'], "user_profiles");
                if (!$user_profile){                                    
                    $user_profile=array( 
                        "ufuname" => "<div style='color:red'> No Profile </div>",
                        "image_loc" => "",
                        "current_car" => "",
                        "about_me" => "",
                        "loc" => "",
                    );
                }
            ?>
                <tr style="height: 25px;">				
                    <td><?php echo $i + 1;//$row['id'];?></td>
                    <td><?php echo $row['username'];?></td>
                    <td><?php echo $row['email'];?></td>
                    <td><?php echo $user_profile['ufuname'];?></td>
                    <td>
                        <?php if ($row['user_profile_id']!=0): ?>
                        <img src="<?php echo HOST.UPLOAD_PATH.$user_profile['image_loc'];?>" alt="No Image" height="80" width="80">
                        <?php else: ?>
                        <?php echo $user_profile['image_loc'];?>
                        <?php endif ?>
                    </td>
                    <td><?php echo $user_profile['current_car'];?></td>
                    <td><?php echo $user_profile['about_me'];?></td>
                    <td><?php echo $user_profile['loc'];?></td>
                    <td>
                        <input type="image" title="Edit" src="<?php echo IMG_DIR; ?>/icn_edit.png" onclick="goedit(<?php echo $row['id'];?>)">                        
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
                    window.location.href = "<?php echo site_url("$post_key"."/".$post_key."_add"); ?>";
                    return false;
            }
            function confirm_del(pid) {
                    if(!confirm('Are you sure to delete?')) {
                            return;
                    }
                    window.location.href = "<?php echo site_url("$post_key"."/".$post_key."_del"); ?>/" + pid;
            }
            function goedit(userid) {
                    window.location.href = "<?php echo site_url("admin/"."$post_key"."/".$post_key."_edit"); ?>/" + userid;
            }
    </script>

    <footer>
        <?php echo $pagenation; ?>
    </footer>
</article>