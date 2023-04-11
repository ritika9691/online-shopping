<?php
session_start();
require_once __DIR__."/pay/vendor/autoload.php";
use Razorpay\Api\Api;
include "db.php";
if (isset($_SESSION["uid"])) 
{

        	$f_name = $_POST["firstname"];
        	$email = $_POST['email'];
        	$address = $_POST['address'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip= $_POST['zip'];
            $user_id=$_SESSION["uid"];
            $total_count=$_POST['total_count'];
            $prod_total = $_POST['total_price'];
                $api_key="rzp_test_3NohmE8ev7CQoD";
                $api_secret="R87mGDmcSxoYBldpvRJsjFMa";
                $api = new Api($api_key, $api_secret);
                $order = $api->order->create(array(  
                'receipt' => '123',  
                'amount' => 100,  
                'payment_capture' => 1,  
                'currency' => 'INR'  ));
?>
            <?php
                $_SESSION["pay_status"]="proceeded";
$pay_status=$_SESSION["pay_status"];
if(isset($pay_status))
{
            $sql0="SELECT order_id from `orders_info`";
            $runquery=mysqli_query($con,$sql0);
            if (mysqli_num_rows($runquery) == 0)
            {
                echo( mysqli_error($con));
                $order_id=1;
            }
            else if (mysqli_num_rows($runquery) > 0) 
            {
                $sql2="SELECT MAX(order_id) AS max_val from `orders_info`";
                $runquery1=mysqli_query($con,$sql2);
                $row = mysqli_fetch_array($runquery1);
                $order_id= $row["max_val"];
                $order_id=$order_id+1;
                echo( mysqli_error($con));
            }

    $sql = "INSERT INTO `orders_info` 
    (`order_id`,`user_id`,`f_name`, `email`,`address`, 
    `city`, `state`, `zip`,`prod_count`,`total_amt`) 
    VALUES ($order_id, '$user_id','$f_name','$email', 
    '$address', '$city', '$state', '$zip','$total_count','$prod_total')";


    if(mysqli_query($con,$sql))
    {
        $i=1;
        $prod_id_=0;
        $prod_price_=0;
        $prod_qty_=0;
        while($i<=$total_count)
        {
            $str=(string)$i;
            $prod_id_+$str = $_POST['prod_id_'.$i];
            $prod_id=$prod_id_+$str;        
            $prod_price_+$str = $_POST['prod_price_'.$i];
            $prod_price=$prod_price_+$str;
            $prod_qty_+$str = $_POST['prod_qty_'.$i];
            $prod_qty=$prod_qty_+$str;
            $sub_total=(int)$prod_price*(int)$prod_qty;
            $i++;
        }
            $sql1="INSERT INTO `order_products` 
            (`order_pro_id`,`order_id`,`product_id`,`qty`,`amt`) 
            VALUES (NULL, '$order_id','$prod_id','$prod_qty','$sub_total')";
            if(mysqli_query($con,$sql1))
            {
                ?>
                <form action="payment_success.php" method="POST"> 
                    <script    
                        src="https://checkout.razorpay.com/v1/checkout.js"    
                        data-key=<?php echo $api_key; ?> // Enter the Key ID generated from the Dashboard  
                        data-amount=<?php echo $prod_total*100; ?> // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise    
                        data-currency="INR"       
                        data-name=<?php echo $f_name; ?>    
                        data-prefill.email=<?php echo $email; ?>     
                        data-theme.color="#cef051">
                    </script><input type="hidden" custom="Hidden Element" name="hidden">
                      <input type="hidden" name="pay_status" value= <?php $pay_status="done";
                      echo $pay_status; ?> />
                     <input type="hidden" name="user_id" value= <?php echo $user_id; ?> />
                     <input type="hidden" name="prod_total" value= <?php echo $prod_total; ?> />

               </form>
                <?php
            }
            else
                {
                    echo(mysqli_error($con));
                }

    }
            else
            {
                echo(mysqli_error($con));
            }
         
}
else
    {

        echo(mysqli_error($con));
        
    }
}

else
    {
        echo"<script>window.location.href='index.php'</script>";
    }
?>
