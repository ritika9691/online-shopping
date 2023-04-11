<?php

require_once __DIR__."/vendor/autoload.php";
use Razorpay\Api\Api;

$api_key="rzp_test_3NohmE8ev7CQoD";
$api_secret="R87mGDmcSxoYBldpvRJsjFMa";
$api = new Api($api_key, $api_secret);
$order = $api->order->create(array(  
'receipt' => '123',  
'amount' => 100,  
'payment_capture' => 1,  
'currency' => 'INR'  ));
?>

<form action="success.php" method="POST"> 
	<script    
		src="https://checkout.razorpay.com/v1/checkout.js"    
		data-key=<?php echo $api_key; ?> // Enter the Key ID generated from the Dashboard    
		data-amount="50000" // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise    
		data-currency="INR"    
		 
		data-buttontext="Pay with Razorpay"    
		data-name="Acme Corp"    
		data-description="Test transaction"    
		data-image="https://example.com/your_logo.jpg"    
		data-prefill.name="Gaurav Kumar"    
		data-prefill.email="gaurav.kumar@example.com"    
		data-prefill.contact="9999999999"    
		data-theme.color="#F37254">
	</script><input type="hidden" custom="Hidden Element" name="hidden">
</form>