<?php
require_once 'core/init.php';

\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

$token = $_POST['stripeToken'];
//Get the rest of the post Data
$full_name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$state = sanitize($_POST['state']);
$zip_code = sanitize($_POST['zip_code']);
$country = sanitize($_POST['country']);
$tax = sanitize($_POST['tax']);
$sub_total = sanitize($_POST['sub_total']);
$grand_total = sanitize($_POST['grand_total']);
$cart_id = sanitize($_POST['cart_id']);
$description = sanitize($_POST['description']);
$charge_amount = number_format($grand_total,2) * 100;
$metadata = array(
  "cart_id" => $cart_id,
  "tax" => $tax,
  "sub_total" => $sub_total,
);

//Create the charge on Stripe's servers - this will charge the user's Card
try{
  $charge = \Stripe\Charge::create(array(
    "amount" => $charge_amount,
    "currency" => CURRENCY,
    "source" => $token,
    "description" => $description,
    "receipt_email" => $email,
    "metadata" =>$metadata)
  );
  //adjust inventory
  $itemQ = $db->query("SELECT * from cart where id ='$cart_id'");
  $iresults = mysqli_fetch_assoc($itemQ);
  $items = json_decode($iresults['items'],true);
  foreach ($items as $item) {
    $newSizes = array();
    $item_id = $item['id'];
    $productQ = $db->query("SELECT sizes from products where id = '$item_id'");
    $product = mysqli_fetch_assoc($productQ);
    $sizes = sizesToArray($product['sizes']);
    foreach ($sizes as $size) {
      if($size['size'] == $item['size']){
        $q = $size['quantity'] - $item['quantity'];
        $newSizes[] = array('size' => $size['size'], 'quantity' => $q);
      }else{
        $newSizes[] = array('size' =>$size['size'],'quantity'=>$size['quantity']);
    }
  }
  $sizeString = sizesToString($newSizes);
  $db->query("UPDATE products set sizes ='$sizeString' where id = '$item_id'");
}
  //update cart
  $db->query("update cart set  paid = 1 where id='$cart_id'");
  $db->query("insert into transactions
        (charge_id,cart_id,full_name,email,street,street2,city,state,
        zip_code,country,sub_total,tax,grand_total,description,txn_type)
   values
      ('$charge->id','$cart_id','$full_name','$email','$street','$street2','$city','$state',
      '$zip_code','$country','$sub_total','$tax','$grand_total','$description','$charge->object')");

      $domain = ($_SERVER['HTTP_HOST'] !='localhost')? '.'.$_SERVER['HTTP_HOST']: false;
      setcookie(CART_COOKIE,'',1,"/",$domain,false);
      include 'includes/head.php';
      include 'includes/navigation.php';

      include 'includes/headerfull.php';
?>
<h1 class= "text-center text-success"> Thank You</h1>
<p>Your card has been successfully charged <?=money($grand_total);?> .You have been emailed a reciped.
Please check your spam folder if it is not in your inbox</p>
<p> Your reciept number is: <b><?=$cart_id;?></b></p>
<p> Your order will be shipped to the address below: </p>
<address>
  <?=$full_name;?><br>
  <?=$street;?><br>
  <?=(($street2 !='')?$street2.'<br>':'');?>
  <?=$city.','.$state.''.$zip_code;?><br>
</address>
<?php
      include 'includes/footer.php';
}catch(\Stripe\Error\Card $e){

  echo $e;
}














 ?>
