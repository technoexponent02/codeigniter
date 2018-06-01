<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul>
                <li class="menu-title">Navigation</li>

                <li class="has_sub">
                    <a href="<?php echo base_url('dashboard'); ?>" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span> Dashboard </span> </a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-format-list-bulleted"></i> <span> User </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo base_url('user-list'); ?>">User List</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="mdi mdi-format-list-bulleted"></i> <span> Hourly Rate </span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo base_url('hourly-management/create'); ?>">Add</a></li>
                        <li><a href="<?php echo base_url('hourly-management'); ?>">List</a></li>
                    </ul>
                </li>
				<li class="has_sub">
                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-view-dashboard"></i>
                <span> Manage Email Template </span> </a>
                <ul class="list-unstyled">
                    <li><a href="<?php echo base_url('addEmail');?>">Add Email</a></li>
                    <li><a href="<?php echo base_url('listEmail');?>">Manage Email</a></li>
                </ul>
                </li>
				
				<li class="has_sub">
                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-view-dashboard"></i>
                <span> Withdraw Section </span> </a>
                <ul class="list-unstyled">
                    <li><a href="<?php echo base_url('listWithdraw');?>">Manage Withdraw Section</a></li>
                </ul>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

        <div class="help-box">
            <h5 class="text-muted m-t-0">For Help ?</h5>
            <p class=""><span class="text-custom">Email:</span> <br/> support@support.com</p>
            <p class="m-b-0"><span class="text-custom">Call:</span> <br/> (+123) 123 456 789</p>
        </div>

    </div>
    <!-- Sidebar -left -->

</div>