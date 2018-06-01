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
<title>User List</title>

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
        <h4 class="page-title">User List</h4>
        
        <div class="clearfix"></div>
    </div>
</div>
</div>
<!-- end row -->
<div class="row">
<div class="col-sm-12">
    <div class="card-box table-responsive">
        <h4 class="m-t-0 header-title"><b>User List</b></h4>
        <table id="datatable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>User Name</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Profile Pic</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Description</th>
                <th>Passport</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
        <tbody>
        <?php 
        if(count($user_list) > 0)
        {
            foreach($user_list as $user_list_value)
            { ?>
            <tr id="cataRow<?php echo $user_list_value->id; ?>">

                <td><?php echo $user_list_value->firstName.' '.$user_list_value->lastName; ?></td>
                <td><?php echo $user_list_value->userName; ?></td>
                <td><?php echo $user_list_value->emailAddress; ?></td>
                <td><?php switch ($user_list_value->userType) 
                            {
                                case 'A':
                                echo 'Admin';
                                break;
                                case 'S':
                                echo "Stuff";
                                break;
                                case 'C':
                                echo "Customer";
                                break;
                                default:
                                echo "Undefined";
                            } ?></td>

                <td><img src="<?php echo base_url().UPLOAD_PATH_URL."profile_picture/".$user_list_value->profile_picture; ?>" height="50px" width="50px"></td> 
                <td><?php echo $user_list_value->phone; ?></td>
                <td><?php echo $user_list_value->address; ?></td>
                <td><?php echo $user_list_value->description; ?></td>

<!--                 <td style="text-align: center;">
                    <button type="button" data-id="<?php echo $all_city_value->id; ?>" city-name="<?php echo $all_city_value->city_name;?>" class="btn btn-success btn-rounded w-sm waves-effect waves-light m-b-5 generateBarcode">Generate Barcode</button>
                    <div id="barcode_image_<?php echo $all_city_value->id; ?>">
                    <a href="<?=base_url('category/download')?>/CI<?php echo $all_city_value->id.".jpg"; ?>">
                    <img src="<?php echo base_url().UPLOAD_PATH_URL."barcode/CI".$all_city_value->id.".jpg";?>" height="80px" width="80px">
                    </a>
                    </div>
                </td> -->

                <td style="text-align: center;">
                <?php 
                if ($user_list_value->passport != NULL) 
                { ?>
                    <a target="blank" class="btn btn-success btn-rounded" href="<?php echo base_url().UPLOAD_PATH_URL."passport/".$user_list_value->passport; ?>">VIEW</a>&nbsp;
                    <br>
                    <?php 
                    if($user_list_value->passport_varification_status == 'Y')
                    { ?>
                        <button type="button" id="status<?php echo $user_list_value->id; ?>" data-id="<?php echo $user_list_value->id; ?>" data-value="N" class="btn btn-success btn-rounded w-sm waves-effect waves-light m-b-5 passport_varification">Varified</button>
                    <?php 
                    } 
                    else 
                    { ?>
                        <button type="button" id="status<?php echo $user_list_value->id; ?>" data-id="<?php echo $user_list_value->id; ?>" data-value="Y" class="btn btn-danger btn-rounded w-sm waves-effect waves-light m-b-5 passport_varification">Not Varified</button>
                    <?php 
                    } ?>
          <?php } ?>
                </td>

                <td>
                <p id="status_<?php echo $user_list_value->id;?>"></p>
                <select class="form-control" onchange="changeUserStatus(<?php echo $user_list_value->id;?>,this.value);">
                <option value="Y" <?php if($user_list_value->status=="Y") echo "selected";?>>Verified</option>
                <option value="N" <?php if($user_list_value->status=="N") echo "selected";?>>Blocked</option>
                <option value="E" <?php if($user_list_value->status=="E") echo "selected";?>>Email Verification Pending</option>
                </select>
                    
                </td>

                <td class="actions">
<!--                 <a href="<?php echo base_url('city-edit/'.$user_list_value->id);?>" class="on-default edit-row"><i class="fa fa-pencil"></i>
                </a> -->
                <a href="javascript:void(0)" onclick="removeUser(<?php echo $user_list_value->id; ?>)" class="on-default remove-row" ><i class="fa fa-trash-o" style="color:red"></i></a>
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

function changeUserStatus(user_id, status)
{
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