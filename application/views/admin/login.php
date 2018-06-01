<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo DEFAULT_ASSETS_URL;?>images/favicon.ico">
        <!-- App title -->
        <title>BABYSITS</title>

        <!-- App css -->
        <link href="<?php echo DEFAULT_ASSETS_URL;?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ASSETS_URL;?>css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ASSETS_URL;?>css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ASSETS_URL;?>css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ASSETS_URL;?>css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ASSETS_URL;?>css/menu.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ASSETS_URL;?>css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/modernizr.min.js"></script>

    </head>


    <body class="bg-transparent">

        <!-- HOME -->
        <section>
            <div class="container-alt">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="wrapper-page">

                            <div class="m-t-40 account-pages">
                                <div class="text-center account-logo-box">
                                    <h2 class="text-uppercase">
                                        <a href="javascript:void(0)" class="text-success">
                                            <span><img src="<?php echo DEFAULT_ASSETS_URL;?>images/logo.png" alt="" height="36"></span>
                                        </a>
                                    </h2>
                                    <!--<h4 class="text-uppercase font-bold m-b-0">Sign In</h4>-->
                                </div>
                                <div class="account-content">
                                    <form method="post" class="form-horizontal" action="<?php echo base_url('user/loginProcess');?>">

                                        <?php if($this->session->flashdata('login_error')){ ?>
                                        <div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            <strong>Oh snap!</strong><?php echo $this->session->flashdata('login_error');?>
                                        </div>
                                        <?php } ?>


                                        <div class="form-group ">
                                            <div class="col-xs-12">
                                                <input class="form-control" type="text" name="userName" required="" placeholder="Username" value="<?php echo $this->session->userdata('userName');?>">
                                                <?php $this->session->unset_userdata('userName');?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <input class="form-control" type="password" name="userPassword" required="" placeholder="Password">
                                            </div>
                                        </div>

                                        <!-- <div class="form-group ">
                                            <div class="col-xs-12">
                                                <div class="checkbox checkbox-success">
                                                    <input id="checkbox-signup" type="checkbox" checked>
                                                    <label for="checkbox-signup">
                                                        Remember me
                                                    </label>
                                                </div>

                                            </div>
                                        </div> -->

                                        <div class="form-group text-center m-t-30">
                                            <div class="col-sm-12">
                                                <a href="javascript:void(0)" class="text-muted"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                                            </div>
                                        </div>

                                        <div class="form-group account-btn text-center m-t-10">
                                            <div class="col-xs-12">
                                                <button class="btn w-md btn-bordered btn-danger waves-effect waves-light" type="submit">Log In</button>
                                            </div>
                                        </div>

                                    </form>

                                    <div class="clearfix"></div>

                                </div>
                            </div>
                            <!-- end card-box-->


                            <div class="row m-t-50">
                                <div class="col-sm-12 text-center">
                                    <p class="text-muted">Don't have an account? <a href="javascript:void(0)" class="text-primary m-l-5"><b>Sign Up</b></a></p>
                                </div>
                            </div>

                        </div>
                        <!-- end wrapper -->

                    </div>
                </div>
            </div>
          </section>
          <!-- END HOME -->

        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/jquery.min.js"></script>
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/bootstrap.min.js"></script>
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/detect.js"></script>
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/fastclick.js"></script>
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/jquery.blockUI.js"></script>
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/waves.js"></script>
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/jquery.slimscroll.js"></script>
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/jquery.scrollTo.min.js"></script>

        <!-- App js -->
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/jquery.core.js"></script>
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/jquery.app.js"></script>

    </body>
</html>