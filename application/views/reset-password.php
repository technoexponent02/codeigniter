<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<title>Forgot password</title>
<link href="<?php echo DEFAULT_ASSETS_URL;?>css/fontend-style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div class="mainarea">
    	<div class="wrapper">
        	<div class="logo"><!-- <img src="<?php //echo DEFAULT_ASSETS_URL;?>images/font-logo.png"  alt=""/> --></div>
            
            <div class="formarea">
        <?php
        if($this->session->userdata('reset_pass_error'))
            { ?>

            <?php echo $this->session->userdata('reset_pass_error'); ?>

        <?php 
            }
            $this->session->unset_userdata('reset_pass_error'); ?>


            	<form method="POST" action="<?php echo base_url('webservice/resetPassProcess');?>">
            	<h4>Reset Password</h4>
            	<section class="formrow">
                    <input type="hidden" name="user_info" value="<?php echo $user_info;?>">
                    <div class="fld"><input name="userPassword" type="password" class="txtfld" placeholder="Enter New Password"></div>
                    <div class="fld"><input name="rePassword" type="password" class="txtfld" placeholder="Re-enter New Password"></div>
                </section>
                <section class="formrow"><button type="submit" class="button full">RESET</button></section>
                </form>
            </div>


        </div>
    </div>
</body>
</html>
