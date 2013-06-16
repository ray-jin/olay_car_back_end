<?php
$f_search = array(	//text field			
		'name'	=> 'f_search',			
	);
?>
<form name="frmSearch"  method="post" action="<?php  echo site_url("admin/user") ?>" >
    <article class="module width_full">
            <header>
                    <h3> Search </h3>
            </header>
            <div class="module_content">
                User Name :  <input type="text" style="width : 150px;" name="s_username" value="<?php echo $s_username; ?>" />  &nbsp;
                <input type="submit" name="fsubmit" value="Search" class="alt_btn">
            </div>
                    
    </article>
</form>

<article class="module width_full">
    <header>
        <h3 class="tabs_involved"> User List </h3>
        <div class="submit_link">
           <input type="submit" value="Add" class="alt_btn" onclick="return post_add()" />
        </div>
    </header>

    <div class="tab_container">
        <table class="tablesorter" cellspacing="0">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th width="100">Name</th>
                    <th width="100">Email</th>                    
                    <th width="100">Banned</th>
                    <th width="100">Profile</th>
                  <!--  <th width="100">Verified_PT</th>
                    <th width="100">Donated_PT</th> -->
                    <th width="80">Actions</th>
                </tr>
             </thead>
             <tbody>
            <?php
            $i = 0;
            foreach($user_list as $row) {	
                $banned="No";
                if ($row['banned']=='1')
                    $banned="Yes";
                $user_profile=$this->manage_m->get_specific_data($row['user_profile_id'], "user_profiles");
                $profile_txt=$user_profile ? "Yes" : "<div style='color:red'> No</div>";
                
            ?>
                <tr style="height: 25px;">				
                    <td><?php echo $i + 1;//$row['id'];?></td>
                    <td><?php echo $row['username'];?></td>
                    <td><?php echo $row['email'];?></td>
                    <td><?php echo $banned;?></td>
                    <td><?php echo $profile_txt;?></td>
                  <!--  <td><?php echo $row['verified_pt'];?></td> -->
                  <!--  <td><?php echo $row['donated_pt'];?></td> -->
                    <td>
                        <input type="image" title="Edit" src="<?php echo IMG_DIR; ?>/icn_edit.png" onclick="goedit(<?php echo $row['id'];?>)">
                        <?php if ($row['level']!=1) : ?>
                        <input type="image" title="Trash" src="<?php echo IMG_DIR; ?>/icn_trash.png" onclick="confirm_del(<?php echo $row['id'];?>)">
                        <?php endif; ?>
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