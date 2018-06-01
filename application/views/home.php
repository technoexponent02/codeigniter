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
        <div class="wrapper mainWrapper">
            <div class="homeBody">
                <div class="homeBodyTxtCont">
                    <div class="homeBodyTxt">

                        <div class="login_logo h_logo"><img src="<?php echo FRONTEND_ASSETS_URL;?>images/logo2.png"></div>
                        <p>Welcome to our Circle!</p>
                    </div>
                </div>
                <div class="homeRgtform h_field mCustomScrollbar">
                    <div class="formFld">
                        <span class="fldTl">Location</span>
                        <label class="calandarFld">
                            <input placeholder="Location" type="text" class="textField date2"/>
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </label>
                    </div>

                    <div class="formFld">
                        <span class="fldTl">Date</span>
                        <label class="calandarFld">
                            <input placeholder="Tuesday    -   09/May/2017" type="text" class="textField date2"/>
                            <i class="fa fa-calendar"></i>
                        </label>
                    </div>
                    <div class="formFld">
                        <span class="fldTl">Time</span>
                        <select class="textField">
                            <option>0:00 AM</option>
                        </select>
                    </div>
                    <div class="formFld">
                        <span class="fldTl">Hours</span>
                        <select class="textField">
                            <option>Please select</option>
                        </select>
                    </div>
                    <div class="formFld">
                        <span class="fldTl">Children</span>
                        <select class="textField childrenNo">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="spacer"></div>
                    <div class="childreninfoCont">
                        <div class="childreninfoRow">
                            <div class="formFld colm2 nmFld">
                                <span class="fldTl">Name</span>
                                <input placeholder="Name" value="" type="text" class="textField"/>
                            </div>
                            <div class="formFld colm2 ageFld">
                                <span class="fldTl">Age</span>
                                <select class="textField">
                                    <option value="0">Age</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                            </div>
                            <div class="formFld colm2 gndrFld">
                                <span class="fldTl">Gender</span>
                                <div class="half">
                                    <div class="rdio">
                                        <p>
                                            <input id="test2" name="radio-group" value="red" checked="" type="radio">
                                            <label for="test2">M</label>
                                        </p>
                                    </div>
                                    <div class="rdio">
                                        <p>
                                            <input id="test1" name="radio-group" value="green" type="radio">
                                            <label for="test1">F</label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- <div class="formFld">
                        <span class="fldTl">Hours of Care Needed</span>
                        <select class="textField">
                            <option>1 Hour @ 20.00 p/hr</option>
                        </select>
                    </div> -->
                    <div class="formFld">
                        <span class="fldTl" style="padding-top:6px;"> Additional information</span>
                        <textarea class="h_textarea" placeholder="Please provide any additional information..."></textarea>
                    </div>
                    <div class="formFld align-center" style="margin-top:4px;">
                        <span class="prcTxt">Total:</span> &pound; 20.00
                    </div>
                    <div class="buttonset align-center">
                        <input type="submit" value="Book Now" class="button lg"/>
                    </div>
                </div>
                <div class="spacer"></div>
            </div>
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