<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	//$login_label = 'Email or login';
        $login_label = 'Email';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
$level = array(
        'name' =>'Level',
        'id'   => 'password',
        'size' => 30,
);
?>
<?php echo form_open($this->uri->uri_string()); ?>
<div class="loginDiv">
    <?php 
//       $this->validation->error_string; 
    
        if (isset($errors[$login['name']]) || isset($errors[$password['name']]) || isset($errors['level'])) {
            
            
    ?>
    <table class="errorDiv" style="width:100%;">
        <tr>
            <td width="22px">
                <img src="<?php echo base_url()?>include/images/nocheck.png" width="20px"/>
            </td>
            <td>
            <?php echo form_error($level['name']); ?><?php echo isset($errors['level'])?$errors['level']:''; ?>
            <?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?>
            <?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?>
            </td>
        </tr>
    </table>
    <?php
        }
    ?>
    <div class="main_tabbar login_title">Login</div>
    <div style="border: 1px solid #e9e9e9; width:96%; padding: 2%;">
        <table>
                <tr>
                        <td><?php echo form_label($login_label, $login['id']); ?></td>
                        <td><?php echo form_input($login); ?></td>
                        <td style="color: red;"></td>
                </tr>
                
                <tr>
                        <td><?php echo form_label('Password', $password['id']); ?></td>
                        <td><?php echo form_password($password); ?></td>
                        <td style="color: red;"></td>
                </tr>

                <?php if ($show_captcha) {
                        
                        if ($use_recaptcha) { ?>
                <tr>
                        <td colspan="2">
                                <div id="recaptcha_image"></div>
                        </td>
                        <td>
                                <a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
                                <div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
                                <div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
                        </td>
                </tr>
                <tr>
                        <td>
                                <div class="recaptcha_only_if_image">Enter the words above</div>
                                <div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
                        </td>
                        <td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
                        <td style="color: red;"><?php echo form_error('recaptcha_response_field'); ?></td>
                        <?php echo $recaptcha_html; ?>
                </tr>
                <?php } else { ?>
                <tr>
                        <td colspan="3">
                                <p>Enter the code exactly as it appears:</p>
                                <?php echo $captcha_html; ?>
                        </td>
                </tr>
                <tr>
                        <td><?php echo form_label('Confirmation Code', $captcha['id']); ?></td>
                        <td><?php echo form_input($captcha); ?></td>
                        <td style="color: red;"><?php echo form_error($captcha['name']); ?></td>
                </tr>
                <?php }
                } ?>
                <tr>
                    <td>
                        <?php echo form_submit('submit', 'Login'); ?>
                    </td>
                    <td colspan="1">
                            <?php echo anchor('/auth/forgot_password/', 'Forgot password'); ?>
                    </td>
                </tr>

        </table>
       
    </div>
</div>
<?php echo form_close(); ?>