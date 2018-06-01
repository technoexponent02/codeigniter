<!--Start Header-->
<header id="header">
    <div class="wrapper">
        <button class="rightmenulink"><i class="fa fa-bars"></i></button>
        <ul class="navigation">
            <li>
                <a href="<?php echo base_url();?>" class="sl">Book</a>
            </li>
            <li>
                <a href="javascript:void(0);">Our babysitters</a>
            </li>
            <li>
                <a href="javascript:void(0);">Our care</a>
            </li>
            <li>
                <a href="javascript:void(0);">Our circle</a>
            </li>
            <li>
                <a href="javascript:void(0);">Join us</a>
            </li>
            <li>
                <a href="javascript:void(0);">Contact us</a>
            </li>
            <?php if($this->session->userdata('usrid') == '') {?>
            <li>
                <a href="<?php echo base_url('sign-in');?>" class="loginNav">Sign In</a>
            </li>
            <?php } ?>
        </ul>
    </div>
</header>
<!--End Header-->