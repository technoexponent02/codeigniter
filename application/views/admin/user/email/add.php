<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
<meta name="author" content="Technoexponent">
<!-- App favicon -->
<link rel="shortcut icon" href="<?php echo DEFAULT_ASSETS_URL;?>images/favicon.ico">
<!-- App title -->
<title>Create New Hourly Rate</title>

<!-- DataTables -->
<link href="<?php echo DEFAULT_ASSETS_URL;?>plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
<?php echo $header_scripts;?>
</head>
<body class="fixed-left zoom90">
<!-- Begin page -->
<div id="wrapper">
<!-- Top Bar Start -->
<?php echo $header;?>
<!-- Top Bar End -->
<!-- ========== Left Sidebar Start ========== -->
<?php echo $left_sidebar;?>
<!-- Left Sidebar End -->
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
<!-- Start content -->
<div class="content">
<div class="container">
<div class="row">
<div class="col-xs-12">
	<div class="page-title-box">
        <h4 class="page-title">ADD E-Mail</h4>
        
        <div class="clearfix"></div>
    </div>
</div>
</div>
<!-- end row -->

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title"><b>Create Form</b></h4>
                <div class="row">
                    <div class="col-md-10">
                        <form name="settings_form" action="<?php echo base_url('admin/dashboard/addEmail');?>" method="post" enctype="multipart/form-data">
                           <?php if(validation_errors()){?>
						<div class="alert alert-danger"><?php echo validation_errors(); ?></div>
					<?php	}
						else if($this->session->userdata('cat_msg')){ ?>
						<div class="alert alert-success"><?php echo ($this->session->userdata('cat_msg')); ?></div>
					<?php } ?>
                            <div class="form-group">
                                <label for="admin_email" class="col-sm-2 control-label">Email Type</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="type" name="type" value="<?php echo set_value('type'); ?>" />
                                </div>
                            </div>
                             <div class="form-group">
                               <label for="admin_email" class="col-sm-2 control-label">Email Title</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="emailTitle" name="emailTitle" value="<?php echo set_value('emailTitle'); ?>" />
                                </div>
                            </div>
							<div class="form-group">
                               <label for="admin_email" class="col-sm-2 control-label">Email Description</label>
                                <div class="col-md-10">
                                   <textarea class="ckeditor form-control" id="description" name="description"><?php echo set_value('description'); ?></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info pull-right">Save</button>
                        </form>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>
    </div>
</div> <!-- container -->
</div> <!-- content -->
<?php echo $footer; ?>
</div>
<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->
<!-- Right Sidebar -->
<?php echo $right_sidebar; ?>
<!-- /Right-bar -->
</div>
<!-- END wrapper -->
<?php echo $footer_scripts; ?>

</body>
</html>