	<form class="form-minimal box padding-medium" action="https://www.paypal.com/cgi-bin/webscr" method="post"  name="<?php echo $paypal_confiq['form_name'];?>" id="<?php echo $paypal_confiq['form_name'];?>">
		<input type="hidden" name="cmd" value="_xclick" readonly>
		<input type="hidden" name="business" value="<?php echo $paypal_confiq['business_email'];?>" readonly>
		<input type="hidden" name="return" value="<?php echo $paypal_confiq['return_url'];?>" readonly>
		<input type="hidden" name="undefined_quantity" value="0" readonly>
		<input type="hidden" name="item_name" value="<?php echo $paypal_confiq['item_name'];?>" readonly>
		<input type="hidden" name="item_number" value="" readonly>
		<input type="hidden" name="notify_url" value="<?php echo $paypal_confiq['notify_url'];?>" readonly>
		<input type="hidden" name="currency_code" value="<?php echo $paypal_confiq['currency_code'];?>" readonly>
		<input type="hidden" name="amount" value="<?php echo $paypal_confiq['amount'];?>" readonly>
		<input type="hidden" name="no_shipping" value="1" readonly>
		<input type="hidden" name="custom" value="0" readonly>
		<input type="hidden" name="cancel_return" value="<?php echo $paypal_confiq['cancel_url'];?>" readonly>
	</form>
	<script type="text/javascript">
		document.<?php echo $paypal_confiq['form_name'];?>.submit();
	</script>