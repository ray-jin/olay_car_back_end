<?php
$f_search = array(	//text field			
		'name'	=> 'f_search',			
	);
?>
<form name="frmSearch"  method="post" action="<?php  echo site_url("admin/make") ?>" >
    <article class="module width_full">
            <header>
                    <h3> Search </h3>
            </header>
            <div class="module_content">
                Make Name :  <input type="text" style="width : 150px;" name="name" value="<?php echo $name; ?>" />  &nbsp;
                <input type="submit" name="fsubmit" value="Search" class="alt_btn">
            </div>
                    
    </article>
</form>
<article class="module width_full">
    <header>
        <h3 class="tabs_involved"> Make List </h3>
        <div class="submit_link">
           <input type="submit" value="Add" class="alt_btn" onclick="return post_add()" />
        </div>
    </header>

    <div class="tab_container">
        <table class="tablesorter" cellspacing="0">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th width="30">Make ID</th>
                    <th width="100">Make Name</th>
                    <th width="100">Description</th>
                    <th width="80">Actions</th>
                </tr>
             </thead>
             <tbody>
            <?php
            $i = 1;
            foreach($make_list as $row) {
                 //$user=$this->manage_m->get_specific_data($row['uid'], "users");
            ?>
                <tr>				
                    <td><?php echo $i+$start_no; //$row['id'];?></td>
                    <td><?php echo $row['id'];?></td>                    
                    <td><?php echo $row['name'];?></td>
                     <td><?php echo $row['desc'];?></td>
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