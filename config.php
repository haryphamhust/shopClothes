<?php
define('BASEURL',$_SERVER['DOCUMENT_ROOT'].'/fuckIDE/');
define('CART_COOKIE','SBwi72UCklwiqzz2');
define('CART_COOKIE_EXPIRE',time()+(86400 *30));
//define('TAXRATE',0.087);

define('CURRENCY','usd');
define('CHECKOUTMODE','TEST');//change TEST to LIVE when u ready to go LIVE

if(CHECKOUTMODE =='TEST'){
  define('STRIPE_PRIVATE','sk_test_HLoliUdgzlGdsoEmWgzUylHz');
  define('STRIPE_PUBLIC','pk_test_Foyzprz7nKzvY13SxdswEya7');
}

if(CHECKOUTMODE =='LIVE'){
  define('STRIPE_PRIVATE','sk_live_B0I8w4sts6x2uijgDY62U85A');
  define('STRIPE_PUBLIC','pk_live_YvfzbyCgTF1ooJYHVaGHgAmE');
}
 ?>
