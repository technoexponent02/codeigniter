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
        <h4 class="page-title">Manage Withdraw Section</h4>
        
        <div class="clearfix"></div>
    </div>
</div>
</div>
<!-- end row -->

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
			<?php
                if(isset($error))
                {
                ?>
                  <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $error;?>
                  </div>
                <?php
                  
                }
                ?>
                        <?php

                if($this->session->userdata('cat_msg'))
                {
                ?>
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->userdata('cat_msg');?>
                  </div>
                <?php
                  
                } 
                ?>
                <h4 class="m-t-0 header-title"><b>Manage Withdraw Section</b></h4>
                <div class="row">
                    <div class="col-md-10">
                        <table id="list_table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                            <th>Serial No</th>
                            <th>Name</th>
							<th>Total Amount</th>
                            <th>Withdraw Amount</th>
							<th>Payment Status</th>
                            <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($categories))
                                    { $i =0;
                                foreach($categories as $category)
                                {
                                    ?>

                                    <tr>
                            <td><?php echo ++$i; ?></td>       
                            <td ><?php echo $category->firstName." ".$category->lastName; ?></td>
							<td ><?php echo $category->amount; ?></td>
							<td ><?php echo $category->amount-$category->service_charges; ?></td>
                            <td ><?php echo $category->payment_status; ?></td>
                            <td align="center">
                                <a href="<?php echo base_url('admin/dashboard/editWithdraw/'.$category->id); ?>" class="btn btn-sm btn-warning td-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                                
                                
                            </td>
                                    </tr>
                               <?php }
                               }
                               else {
                                ?>
                                  <tr>
                                    <td colspan="2">No user Found..</td>
                                </tr>
                                <?php 
                                } ?>
                              
                           

                            </tbody>
                        </table>
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