<?php
$f_search = array(	//text field			
		'name'	=> 'f_search',			
	);
?>

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
                    <th width="100">Activated</th>
                    <th width="100">Banned</th>
                  <!--  <th width="50">Type</th>-->
                  <!--  <th width="100">Verified_PT</th>
                    <th width="100">Donated_PT</th> -->
                    <th width="80">Actions</th>
                </tr>
             </thead>
             <tbody>
            <?php
            $i = 0;
            foreach($user_list as $row) {	
                $activated="No"; $banned="No";
                
                if ($row['activated']=='1')
                    $activated="Yes";
                if ($row['banned']=='1')
                    $banned="Yes";
            ?>
                <tr>				
                    <td><?php echo $i + 1;//$row['id'];?></td>
                    <td><?php echo $row['username'];?></td>
                    <td><?php echo $row['email'];?></td>
                    <td><?php echo $activated;?></td>
                    <td><?php echo $banned;?></td>
                  <!--  <td><?php echo $row['type'];?></td>-->
                  <!--  <td><?php echo $row['verified_pt'];?></td> -->
                  <!--  <td><?php echo $row['donated_pt'];?></td> -->
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
                    window.location.href = "<?php echo site_url("$post_key"."/".$post_key."_add"); ?>";
                    return false;
            }
            function confirm_del(pid) {
                    if(!confirm('Are you sure to delete?')) {
                            return;
                    }
                    window.location.href = "<?php echo site_url("$post_key"."/".$post_key."_del"); ?>/" + pid;
            }
            function goedit(pid) {
                    window.location.href = "<?php echo site_url("$post_key"."/".$post_key."_edit"); ?>/" + pid;
            }
    </script>

    <footer>
        <?php echo $pagenation; ?>
    </footer>
</article>