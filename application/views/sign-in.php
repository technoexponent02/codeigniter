<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $general_settings->SiteTitle;?></title>

    <?php echo $header_scripts;?>
</head>

<body>

<!--Start Loader-->
<!--<div class="mask">
    <div id="intro-loader" >Loading...</div>
</div>-->
<!--End Loader-->

<div class="mainSite">


    <!--Start Content-->

    <div class="mainBody">
        <div class="wrapper">
            <div class="login_logo"><img src="<?php echo FRONTEND_ASSETS_URL;?>images/logo.png"></div>
            <div class="whiteFormBX login_box">
                <h3>Sign In</h3>
                <form class="loginForm loginForm2" method="post" action="<?php echo base_url('user/loginProcess');?>">

                        <?php if($this->session->flashdata('login_error')){ ?>
                            <div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <strong>Oh snap!</strong><?php echo $this->session->flashdata('login_error');?>
                            </div>
                        <?php } ?>
                    <label class="formRow">
                        <input placeholder="Email Address" type="email" name="userName" required="" value="<?php echo $this->session->userdata('userName');?>">
                        <?php $this->session->unset_userdata('userName');?>
                    </label>

                    <label class="formRow">
                        <input placeholder="Password" type="password" name="userPassword" required="" value="<?php echo $this->session->userdata('userPassword');?>">
                        <?php $this->session->unset_userdata('userPassword');?>
                    </label>

                    <div class="formRow checkbox_custom">
                        <input type="checkbox" id="test2" checked="checked">
                        <label for="test2" class="rmbrMe">Remember me</label>
                    </div>

                    <span class="formRow"><span class="formBtnCntr"><button type="submit" class="create_account signin">Sign In</button></span></span>

                    <div class="loginbottom">
                        <a href="#" class="register_btn">Register</a>
                        <a href="#" class="register_btn lost_pass"><span>Forgotten Password?</span> </a>
                        <div class="spacer"></div>
                    </div>


                    <span class="formRow"><span class="formBtnCntr"><button type="submit" class="create_account signin create_account2"><img src="<?php echo FRONTEND_ASSETS_URL;?>images/back.png"> Back to 24|7 babysits</button></span></span>

                </form>

            </div>
        </div>
    </div>

    <!--End Content-->


</div>

<!--Start Javascript-->
<?php echo $footer_scripts;?>

<!--End Javascript-->
</body>
</html>