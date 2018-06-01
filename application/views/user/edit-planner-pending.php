<!-- <?php 
	// echo "<pre>";
	// print_r($existing_booking_details); exit; 

	?> -->

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!-- <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon"> -->
<title>247babysits</title>

<!--Start CSS-->
<?php echo $header_scripts;?>
 <link rel="stylesheet" type="text/css" href="assets/frontend/date-time-picker/jquery.datetimepicker.css"/> 
<!--End Responsive CSS (Don't Keep Any CSS Below This)-->

</head>
<body>
<div class="mainSite">
<?php echo $header;?>
<div class="mainBody">
<div class="wrapper">
<button class="leftmenubtn"><i class="fa fa-bars"></i></button>
<div class="left_panel">
<ul>
                    <li class="active"><a href="<?php echo base_url('customer-bookings');?>">Book</a></li>
                    <li ><a href="<?php echo base_url('customer-planner');?>">Bookings</a></li>
                    <li><a href="#">Messages</a></li>
                    <li><a href="<?php echo base_url('my-account');?>">My Account</a></li>
                    <li><a href="#">My Details</a></li>
                    <li><a href="#">My Verification</a></li>
                    <li><a href="<?php echo base_url('change-password');?>">Change Password</a></li>
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
<?php 
    if(!empty($upcoming_bookings))
    {

        foreach ($upcoming_bookings as $key => $booking)
         {
            
            $user_name= $this->userdata->grabDetails(TABLE_USER,array('id'=>$booking->approved_by_userId));
            $booking_child_details=$this->userdata->grabAllDetails(TABLE_BOOKING_CHILD_DETAILS,array('booking_id'=>$booking->id));


?>       
                <div class="list">
                <div class="listL">
                <span class="name"><?php echo !empty($user_name) ?  ($user_name->firstName.' '.$user_name->lastName) : 'Booking Request Pending'; ?></span>
                <span class="dtTm"><?php echo $booking->start_time; ?></span>
                <!-- Fri, 18 Aug 2017 - 10:30am -->
                <?php
                $female_child=0;
                $male_child=0;
                 if(!empty($booking_child_details)){
                    foreach ($booking_child_details as $key => $child_d) {
                        if($child_d->child_gender=='F'){
                        $female_child=$female_child+1; }
                        if($child_d->child_gender=='M'){
                        $male_child=$male_child+1; }
                         # code...
                     } } ?>
                <span class="ch">
                <img src="<?php echo DEFAULT_ASSETS_URL;?>images/icons/kid_male.jpg" style="max-width:40px" alt=""/>
                <span><?php echo  $male_child; ?></span>&nbsp;
                <img src="<?php echo DEFAULT_ASSETS_URL;?>images/icons/kid_female.jpg" style="max-width:40px" alt=""/>
                <span><?php echo $female_child; ?></span>
                </span>
                </div>
                <span class="hrs"><?php echo $booking->no_of_hours_needed. 'hours' ; ?></span>
                </div>
<?php   } 
    }
    else{
        echo "You have No booking.";
    } ?>
            </div>
        </div>

       
        </div>
</div>
</div>
<div class="main_inner_cont threeColm">
<div class="main_inner headingFixed">
<div class="heading"><h1>Booking</h1></div>			
<div class="main_inner nw mCustomScrollbar">



	<?php if($this->session->flashdata('register_error')){ ?>
						 <div class="error" >
                                        <div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                           <?php echo $this->session->flashdata('register_error');?>
                                        </div>
                                        </div>
                                        <?php } ?>


                                        <?php if($this->session->flashdata('register_success')){ ?>
						 <div class="success" >
                                        <div class="alert alert-icon alert-success alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                           <?php echo $this->session->flashdata('register_success');?>
                                        </div>
                                        </div>
                                        <?php } ?>

<form method="POST" name="form_data" class="customForm my_profile_sec" action="<?php echo base_url('user/pending_booking_edit/'.$existing_booking_details->id) ?>" enctype="multypart/form-data">


	
	<div class="profile_details">								
		<div class="profile_detailsDec">
			<div class="profile_detailsRW withLine strongFormtl">
				<div class="profile_detailsCol fieldFormSpace">
					<label class="formField">
						<span class="fldTl">Location:</span>
						<input placeholder="Select location from drop down" class="textField " id="address1" name="address" type="text" value="<?php echo isset($existing_booking_details->address) ? $existing_booking_details->address  : '' ;?>" >
					</label>

					<input id="lat" name="latitude" type="hidden" value=""/>
					<input id="lng" name="longitude" type="hidden" 	value=""/>									
					<div class="formField">
						<div class="fldRow">
							<div class="col">
								<?php if(!empty($existing_booking_details->start_time)){

									$date_=explode(' ', $existing_booking_details->start_time);
								} ?>
								<span class="fldTl">Date:</span>
								<input placeholder="mm/dd/yyyy" class="textField date"  type="date" name="date" value="<?php echo isset($existing_booking_details->start_time) ? $date_[0]  : '' ;?>" >
							</div>
							<div class="col">
								<span class="fldTl">Time:</span>
								<select name="time" class="input-small" >
								    <?php
								       for ($i=8; $i<=21; $i++){
								         echo "<option value= '".$i."'>" . $i.'.00' ."</option>";
								       }
								    ?>
								 </select>
													
							</div>
						</div>	
					</div>

					<label class="formField">
						<span class="fldTl">Hours:</span>
                        
						<select id="no_of_hours_needed" name="no_of_hours_needed" >
							<option value="">Please select</option>
							<?php foreach ($hour_details as $key => $hrs) { ?>
								
								 <option value="<?php echo $hrs->no_of_hr.'_'. $hrs->rate_per_hour;?>"> <?php echo $hrs->no_of_hr.' @ '.$hrs->rate_per_hour.'per hour'; ?></option>
								 <!--<input type="hidden" id="price_per_hr<?php /*echo  $key;*/?>" name="price_per_hr[]" value="<?php /*echo $hrs->rate_per_hour */?>">-->

					<?php	} ?>
							
						</select>
					</label>
					<div class="formField">
						<span class="fldTl">Additional Info:</span>
						<textarea class="textField textField_area" name="addi_info"><?php echo isset($existing_booking_details->addi_info)? $existing_booking_details->addi_info : '' ; ?></textarea>
					</div>						
				</div>
				<div class="profile_detailsCol fieldFormSpace">
					<!-- <div class="smTl">Children:</div> -->
<?php 
$no_of_child=count($child_details); ?>
					<label class="formField">
						<span class="fldTl">Number of Children:</span>
						<select id="no_of_child" name="no_of_child" >
                            <option value="">select</option>
                            <option value="1"<?php echo isset($no_of_child) &&  ($no_of_child== 1) ? 'selected' : ''; ?>>1</option>
                            <option value="2" <?php echo isset($no_of_child) &&  ($no_of_child== 2) ? 'selected' : ''; ?> >2</option>
                            <option value="3" <?php echo isset($no_of_child) &&  ($no_of_child== 3) ? 'selected' : ''; ?> >3</option>
                        </select>
					</label>
					<div class="formField" id="childDetails">

						  <?php if(!empty($child_details)) {

            foreach ($child_details as $key => $child) {
            ?>

        <div class="fldRow nw">
            <div class="col nm">
                <span class="fldTl full">Name:</span>
                <!-- <input type="text" placeholder="Name" name="child_name[]" class="textField"  value="<?php echo !empty($child->child_name) ? $child->child_name : '' ?>"> -->
                <label class="textField"><?php echo !empty($child->child_name) ? $child->child_name : '' ?></label>
            </div>
            <div class="col age">
                <span class="fldTl full">Age:</span>
                <!-- <input type="text" placeholder="Age" class="textField" name="child_age[]" value="<?php echo !empty($child->child_age) ? $child->child_age : '' ?>"> -->

                 <label class="textField"><?php echo !empty($child->child_age) ? $child->child_age : '' ?></label>
            </div>
            <div class="col gen">
                <span class="fldTl full">Gender:</span>
                <div class="rdoFldCont">
                    <!-- <label class="rdoFld">
                        <input type="radio"  value="M"<?php echo (!empty($child->child_gender) && $child->child_gender =='M' ) ? 'checked' : ''; ?> / >

                        <span class="ico"></span>
                        M
                    </label>
                    <label class="rdoFld">
                      <input type="radio"  value="F"<?php echo (!empty($child->child_gender) && $child->child_gender =='F' ) ? 'checked' : '' ;?> / >
                        

                        <span class="ico"></span>
                        F
                    </label> -->

                    <?php if(!empty($child->child_gender))
                    {
                        if($child->child_gender =='M')
                            { ?>
                            <label class="rdoFld">
                            <input type="radio"  value="M"<?php echo (!empty($child->child_gender) && $child->child_gender =='M' ) ? 'checked' : ''; ?> / >
                            <span class="ico"></span>
                        M
                            </label>
                      <?php  } else {?>

                      <label class="rdoFld">
                      <input type="radio"  value="F"<?php echo (!empty($child->child_gender) && $child->child_gender =='F' ) ? 'checked' : '' ;?> / >
                        

                        <span class="ico"></span>
                        F
                    </label>

            <?php    } 

                    } ?>
                </div>
            </div>
        </div> 
               
     <?php        }


        } ?>
        
						
					
					</div>
				</div>											
				<div class="spacer"></div>
				<div class="profile_detailsCol right" id="total_data">
					<!-- <label class="formField align-center">
						<span class="fldTl" style="display:inline-block; vertical-align:top; width:auto; font-weight:300; font-size:15px;"><strong style="font-size:13px;">Total:</strong> &pound;31.00</span>
					</label> -->
				</div>
				<div class="spacer"></div>
				<div class="buttonS align-center">
					<input value="Book Now" class="button" type="submit">
				</div>
			</div>
		</div>
		<div class="spacer"></div>
	</div>								
</form>
</div>
</div>
</div>	
<div class="clear"></div>
</div>
</div>

<?php echo $footer;?>
</div>
<!--/mainSite-->
<?php echo $footer_scripts;?>
<!--Start Javascript-->
<script src="assets/frontend/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/frontend/date-time-picker/jquery.datetimepicker.js"></script>
<script type="text/javascript">
// $(document).ready(function () {
// $('.inlineCalender').datetimepicker({
// format:'d.m.Y',
// inline:true,
// timepicker:false,
// });
// });
</script>
<script type="text/javascript"> 
$(document).ready(function() {
$(".tab a").click(function(event) {
event.preventDefault();
$(this).parent().addClass("current");
$(this).parent().siblings().removeClass("current");
var tab = $(this).attr("href");
$(".tab_view").not(tab).css("display", "none");
$(tab).fadeIn();
});
});//]]>  

</script>
<script src="assets/frontend/scroll/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="assets/frontend/js/custom.js" type="text/javascript"></script>
<!--End Javascript-->

<script type="text/javascript">
$(document).ready(function () {

	$(document).on('change',"#no_of_child",function(){
    var getValue=$(this).val() ;
    // alert(getValue);
		var i;
		var child_data='';
		for (i = 1; i <= getValue; i++)
		{
			child_data +=
			'<div class="fldRow nw">'+
					'<div class="col nm">'+
						'<span class="fldTl">Name:</span>'+
						'<input type="text" placeholder="Name" class="textField" name="child_name[]" required>'+
					'</div>'+
					'<div class="col age">'+
						'<span class="fldTl">Age:</span>'+
						'<input type="text" name="child_age[]" placeholder="Age" class="textField" required>'+
					'</div>'+
					'<div class="col gen">'+
						'<span class="fldTl">Gender:</span>'+
						'<div class="rdoFldCont" required>'+
							'<label class="rdoFld">'+
								'<input type="radio" name="child_gender_'+i+'" value="M" required />'+
								'<span class="ico"></span>'+
								'M'+
							'</label>'+
							'<label class="rdoFld">'+
								'<input type="radio" name="child_gender_'+i+'" value="F" required/>'+
								'<span class="ico"></span>'+
								'F'+
							'</label>'+
						'</div>'+
					'</div>'+
				'</div>';
			 

		}
		$("#childDetails").html(child_data);
		
  });

		
	$(document).on('change',"#no_of_hours_needed",function(){
        var getValue=$(this).val();
         // alert(getNoValue);
         var hr_price=getValue.split("_");
         
         var getNoValue=hr_price[0];
         var price_per_hr=hr_price[1];

         console.log(price_per_hr);
         var total_price=getNoValue * price_per_hr;


            var total_data_price='<label class="formField align-center">						<span class="fldTl"style="display:inline-block; vertical-align:top; width:auto; font-weight:300; font-size:15px;"><strong style="font-size:13px;">Total:</strong> &pound;'+total_price+'</span></label>';

            $("#total_data").html(total_data_price);

      });
});

</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzqk6HcPC5ixgV02bXjsGv_N0Cl0Vm2dc&libraries=places&callback=initMap" async defer></script>
<script type="text/javascript">
function initMap()
{

	var inputt = document.getElementById('address1');
	var autocomplete = new google.maps.places.Autocomplete(inputt);
	
	autocomplete.addListener('place_changed', function()
	{
		var place = autocomplete.getPlace();
		console.log(place);
		if (place.geometry.location) {
			var lat = place.geometry.location.lat();
			var lng = place.geometry.location.lng();
			//alert(lat+'~~'+lng);
			$("#lat").val(lat);
			$("#lng").val(lng);
		}
		var componentForm = {
			//street_number: 'short_name',
			//route: 'long_name',
			//administrative_area_level_1: 'short_name',
			//country: 'long_name',
			locality: 'long_name',
			administrative_area_level_1: 'long_name',
			country: 'long_name',
		};
		var arrlen= Object.keys(place.address_components).length;
		var address = '';
		// Get each component of the address from the place details
		// and fill the corresponding field on the form.
		for (var i = 0; i < Object.keys(place.address_components).length; i++) 
		{
			var addressType = place.address_components[i].types[0];
			//console.log(addressType);
			if (componentForm[addressType]) {
    		    var valu = place.address_components[i][componentForm[addressType]];
    			if(addressType=='locality')
    			{
    				$('#city').val(valu);
    			}
		    }
	    }
	    //console.log(Object.keys(place.address_components).length);
	    //console.log(place.address_components);
	    
	    
	    /********Pin the location************/
	   /* var myLatLng = {lat: lat, lng: lng};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 14,
          center: myLatLng
        });
        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Hello World!'
        });
        $("#map").css("width", "100%");
        $("#map").css("height", "300px");*/
        /********Pin the location************/
	});
}


</script>

<script src="assets/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/frontend/date-time-picker/jquery.datetimepicker.js"></script>
<script type="text/javascript">
        $(document).ready(function () {
            $('.inlineCalender').datetimepicker({
                format:'d.m.Y',
                inline:true,
                timepicker:false,
            });

             // $( ".inlineCalender" ).datetimepicker( "dialog", "10/12/2012" );

        });
</script>


</body>
</html>