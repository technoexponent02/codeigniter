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
<title>Hourly Rates List</title>

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
        <h4 class="page-title">Hourly Rates</h4>
        
        <div class="clearfix"></div>
    </div>
</div>
</div>
<!-- end row -->
<div class="row">
<div class="col-sm-12">
    <div class="card-box table-responsive">
        <h4 class="m-t-0 header-title"><b>All</b></h4>
        <table id="datatable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Number of Hours</th>
                <th>Rate per Hour</th>
                <th>Action</th>
            </tr>
            </thead>
        <tbody>
        <?php 
        if(!empty($hourly_rates))
        {
            foreach($hourly_rates as $rate)
            { ?>
            <tr id="cataRow<?php echo $rate->id; ?>">
                
                <td><?php echo $rate->no_of_hr; ?></td>
                <td><?php echo round($rate->rate_per_hour); ?></td>

                <td class="actions">
                <a href="javascript:void(0)" onclick="remove(<?php echo $rate->id; ?>)" class="on-default remove-row" >
                    <i class="fa fa-trash-o" style="color:red"></i>
                </a>
                </td>
            </tr>
        <?php } 
        } ?>
            </tbody>
        </table>
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
<script src="<?php echo DEFAULT_ASSETS_URL;?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo DEFAULT_ASSETS_URL;?>plugins/datatables/dataTables.bootstrap.js"></script>

<script type="text/javascript">
$(document).ready(function () {
$('#datatable').dataTable();                
});
</script>

<script type="text/javascript">

function remove(id) {
    if(confirm('Are you sure you want to delete ?')) {
        $.ajax({
            type:"POST",
            url: "<?php echo base_url('hourly-management/delete'); ?>",
            data:{id:id},
            dataType: 'json',
            success:function(response) {
                if (response.status == 'success')
                $('#cataRow'+id).remove();
            }
        });
    }
}
</script>

</body>
</html>