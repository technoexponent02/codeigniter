<script>
	var basepath = '<?php echo base_url();?>';
</script>


<script src="<?php echo FRONTEND_ASSETS_URL;?>js/jquery.min.js" type="text/javascript"></script>
<!-- <script src="assets/date-time-picker/jquery.datetimepicker.js"></script> -->
<script type="text/javascript">
    $(document).ready(function () {
        var childFields = '<div class="formFld colm2 nmFld"><span class="fldTl">Name</span><input placeholder="Name" value="" type="text" class="textField"/></div><div class="formFld colm2 ageFld"><span class="fldTl">Age</span><select class="textField"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></div><div class="formFld colm2 gndrFld"><span class="fldTl">Gender</span><div class="half"><div class="rdio"><p><input id="test2" name="radio-group" value="red" checked="" type="radio"><label for="test2">M</label></p></div><div class="rdio"><p><input id="test1" name="radio-group" value="green" type="radio"><label for="test1">F</label></p></div></div></div>';
        $(".childrenNo").change(function(){
            var thisValu = $(this).val();
            var totalChild = '';
            for (var i=0; i < parseInt(thisValu); i++)
            {
                totalChild += childFields;
            }
            $(".childreninfoRow").html(totalChild);
            formGap();
        });
    });
    function formGap(){
        var outerH = $(".mainBody").innerHeight() - 74;
        var innerH = $(".homeRgtform").innerHeight();
        var topgap = (outerH - innerH) /2;
        $(".homeBody").parent(".mainWrapper").css({"padding-top":topgap});
    }
</script>
<script src="<?php echo FRONTEND_ASSETS_URL;?>scroll/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php echo FRONTEND_ASSETS_URL;?>js/custom.js" type="text/javascript"></script>
