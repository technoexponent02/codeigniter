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
        <h4 class="page-title">Hourly Rate</h4>
        
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
                        <form class="form-horizontal" role="form" method="post">
                            <?php if($this->session->flashdata('validation_error') || $this->session->flashdata('already_exist_error')) { ?>
                                <div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        <strong>Oh snap!</strong><br/>
                                        <?php echo ($this->session->flashdata('validation_error'));?><br/>
                                        <?php echo ($this->session->flashdata('already_exist_error'));?>
                                    </div>
                            <?php } ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Number of Hours</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="no_of_hr" placeholder="Enter..."
                                         value="<?php echo $this->session->userdata('no_of_hr');?>" required="required">
                                    <?php $this->session->unset_userdata('no_of_hr');?>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Rate per Hour (£)</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="rate_per_hour" placeholder="Enter..."
                                    value="<?php echo $this->session->userdata('rate_per_hour');?>" required="required">
                                    <?php $this->session->unset_userdata('rate_per_hour');?>
                                </div>
                            </div>
                             <button type="submit" class="btn btn-purple waves-effect waves-light">Submit</button>
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
<script src="<?php echo DEFAULT_ASSETS_URL;?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo DEFAULT_ASSETS_URL;?>plugins/datatables/dataTables.bootstrap.js"></script>

<script type="text/javascript">
$(document).ready(function () {
$('#datatable').dataTable();                
});
</script>

<script type="text/javascript">

$(document).on('click','.passport_varification',function(){ 
    var user_id = $(this).attr('data-id');
    var status = $(this).attr('data-value');
       $.ajax({
        type:"POST",
        url: "<?php echo base_url('user/passportVarificationStatus'); ?>",
        data:{user_id:user_id, status:status},
        success:function(data){
            if(status=='Y'){
                $('#status'+user_id).removeClass('btn-danger');
                $('#status'+user_id).addClass('btn-success');
                $('#status'+user_id).attr('data-value', 'N');
                $('#status'+user_id).html('Varified');
            } else {
                $('#status'+user_id).removeClass('btn-success');
                $('#status'+user_id).addClass('btn-danger');
                $('#status'+user_id).attr('data-value', 'Y');
                $('#status'+user_id).html('Not Varified');
            }
        }
    });
});

function removeUser(user_id)
{
    if(confirm('Are you sure you want to delete This User ?'))
    {
        $.ajax({
            type:"POST",
            url: "<?php echo base_url('user/removeUser'); ?>",
            data:{user_id:user_id},
            success:function(data){
                $('#cataRow'+user_id).hide('fast');
            }
        });
    }
}
</script>

<script type="text/javascript">
function changeUserStatus(user_id, status) {
    $.ajax({
            type:"POST",
            url: "<?php echo base_url('user/changeUserStatus'); ?>",
            data:{user_id:user_id, status:status},
            success:function(data){
                $('#status_'+user_id).show('fast');
                $('#status_'+user_id).text('Updated');
                $('#status_'+user_id).hide(3000);
            }
        });
}
</script>
</body>
</html>