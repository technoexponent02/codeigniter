<?php
//print_r($user_details);
?>
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

<body class="homePg">

<!--Start Loader-->
<!--<div class="mask">
    <div id="intro-loader" >Loading...</div>
</div>-->
<!--End Loader-->

<div class="mainSite">

    <?php echo $header;?>

    <!--Start Content-->
    <div class="mainBody">
        <div class="wrapper">
            <button class="leftmenubtn"><i class="fa fa-bars"></i></button>
            <div class="left_panel">
                <ul>
                    <li><a href="#">Book</a></li>
                    <li class="active"><a href="#">Bookings</a></li>
                    <li><a href="#">Messages</a></li>
                    <li><a href="#">My Account</a></li>
                    <li><a href="#">My Details</a></li>
                    <li><a href="#">My Verification</a></li>
                    <li><a href="#">Change Password</a></li>
                    <li><a href="<?php echo base_url('logout');?>">Logout</a></li>
                </ul>


            </div>
            <div class="right_panel">
                <div class="main_inner">
                    <!-- <div class="heading"><h1>Calendar</h1></div> -->
                    <div class="rightColmArea">
                        <input type="text" class="inlineCalender"/>

                        <div class="bookingList">
                            <span class="tl">Upcoming Bookings</span>
                            <div class="bookLictCont mCustomScrollbar">
                                <div class="list">
                                    <div class="listL">
                                        <span class="name">Sourav Mondal</span>
                                        <span class="dtTm">Fri, 18 Aug 2017 - 10:30am</span>
                                        <span class="ch">
												<img src="assets/images/kids1.png" alt=""/>
												<span> 1</span>&nbsp;
												<img src="assets/images/kids1_female.png" alt=""/>
												<span> 2</span>
											</span>
                                    </div>
                                    <span class="hrs">5 Hours</span>
                                </div>
                                <div class="list">
                                    <div class="listL">
                                        <span class="name">Sourav Mondal</span>
                                        <span class="dtTm">Fri, 18 Aug 2017 - 10:30am</span>
                                        <span class="ch">
												<img src="assets/images/kids1.png" alt=""/>
												<span> 1</span>&nbsp;
												<img src="assets/images/kids1_female.png" alt=""/>
												<span> 2</span>
											</span>
                                    </div>
                                    <span class="hrs">5 Hours</span>
                                </div>
                                <div class="list">
                                    <div class="listL">
                                        <span class="name">Sourav Mondal</span>
                                        <span class="dtTm">Fri, 18 Aug 2017 - 10:30am</span>
                                        <span class="ch">
												<img src="assets/images/kids1.png" alt=""/>
												<span> 1</span>&nbsp;
												<img src="assets/images/kids1_female.png" alt=""/>
												<span> 2</span>
											</span>
                                    </div>
                                    <span class="hrs">5 Hours</span>
                                </div>
                                <div class="list">
                                    <div class="listL">
                                        <span class="name">Sourav Mondal</span>
                                        <span class="dtTm">Fri, 18 Aug 2017 - 10:30am</span>
                                        <span class="ch">
												<img src="assets/images/kids1.png" alt=""/>
												<span> 1</span>&nbsp;
												<img src="assets/images/kids1_female.png" alt=""/>
												<span> 2</span>
											</span>
                                    </div>
                                    <span class="hrs">5 Hours</span>
                                </div>
                                <div class="list">
                                    <div class="listL">
                                        <span class="name">Sourav Mondal</span>
                                        <span class="dtTm">Fri, 18 Aug 2017 - 10:30am</span>
                                        <span class="ch">
												<img src="assets/images/kids1.png" alt=""/>
												<span> 1</span>&nbsp;
												<img src="assets/images/kids1_female.png" alt=""/>
												<span> 2</span>
											</span>
                                    </div>
                                    <span class="hrs">5 Hours</span>
                                </div>
                                <div class="list">
                                    <div class="listL">
                                        <span class="name">Sourav Mondal</span>
                                        <span class="dtTm">Fri, 18 Aug 2017 - 10:30am</span>
                                        <span class="ch">
												<img src="assets/images/kids1.png" alt=""/>
												<span> 1</span>&nbsp;
												<img src="assets/images/kids1_female.png" alt=""/>
												<span> 2</span>
											</span>
                                    </div>
                                    <span class="hrs">5 Hours</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main_inner_cont threeColm">
                <div class="main_inner headingFixed">
                    <div class="heading"><h1>My Account</h1></div>
                    <div class="main_inner nw mCustomScrollbar">
                        <div class="profile_head">
                            <div class="profile_image" style="background-image:url(assets/images/profile.png);"></div>
                            <div class="p_head_detail">
                                <div class="d_hold">
                                    <h2><?php echo  $user_details->firstName,' ',$user_details->firstName; ?></h2>
                                    <div class="pu-rating">


                                        <div class="fk-stars-small" title="4 stars">
                                            <div class="rating" style="width:90%;"></div>
                                        </div>
                                        <span>4.5</span>
                                    </div>
                                </div>
                            </div><div class="clear"></div>
                        </div>

                        <div class="customForm my_profile_sec">
                            <div class="profile_details nw botdTl">
                                <div class="profileDetRW">
                                    <div class="colm">
                                        <div class="formField">
                                            <span class="fldTl">Email :</span>
                                            <div class="fieldBx">
                                                <p>exampel@website.com</p>
                                            </div>
                                            <div class="spacer"></div>
                                        </div>
                                        <div class="formField">
                                            <span class="fldTl">Number :</span>
                                            <div class="fieldBx">
                                                <p> +91 1234 567 890</p>
                                            </div>
                                            <div class="spacer"></div>
                                        </div>
                                        <div class="formField">
                                            <span class="fldTl">Address :</span>
                                            <div class="fieldBx">
                                                <p> E 2/3, EP &amp; GP Block,  5th Floor, Stesalit Tower, Sector 5,  Saltlake, Kolkata, West Bengal 700091, India</p>
                                            </div>
                                            <div class="spacer"></div>
                                        </div>
                                        <div class="formField">
                                            <span class="fldTl">Verification :</span>
                                            <div class="fieldBx">
                                                <p> Facebook    <i class="fa fa-check" aria-hidden="true"></i>   |  Passport</p>
                                            </div>
                                            <div class="spacer"></div>
                                        </div>
                                        <div class="formField">
                                            <span class="fldTl">Children :</span>
                                            <div class="fieldBx">
													<span class="ch">
														<img src="assets/images/kids1.png" alt="" class="mCS_img_loaded">
														<span> 1</span>&nbsp;
														<img src="assets/images/kids1_female.png" alt="" class="mCS_img_loaded">
														<span> 2</span>
													</span>
                                            </div>
                                            <div class="spacer"></div>
                                        </div>
                                    </div>
                                    <div class="colm">
                                        <div class="formField">
                                            <span class="fldTl">Bookings Pending :</span>
                                            <div class="fieldBx">
                                                <p>3</p>
                                            </div>
                                            <div class="spacer"></div>
                                        </div>
                                        <div class="formField">
                                            <span class="fldTl">Bookings Approved :</span>
                                            <div class="fieldBx">
                                                <p>4</p>
                                            </div>
                                            <div class="spacer"></div>
                                        </div>
                                        <div class="formField">
                                            <span class="fldTl">Bookings Completed :</span>
                                            <div class="fieldBx">
                                                <p>5</p>
                                            </div>
                                            <div class="spacer"></div>
                                        </div>
                                        <div class="formField">
                                            <span class="fldTl">Feedback Sent :</span>
                                            <div class="fieldBx">
                                                <p>12</p>
                                            </div>
                                            <div class="spacer"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>

        </div>
    </div>

    <!--End Content-->
    <!--Start Footer-->
    <?php echo $footer;?>
    <!--End Footer-->

</div>

<!--Start Javascript-->
<?php echo $footer_scripts;?>

<!--End Javascript-->
</body>
</html>