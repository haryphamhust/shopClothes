<?php
require_once "core/init.php";
include "includes/head.php";
include "includes/navigation.php";

include "includes/headerfull.php";
include "includes/leftbar.php";



$sql = "select * from products";
$cat_id = (($_POST['cat'] != '')?sanitize($_POST['cat']):'');
if($cat_id==''){
  $sql .= ' where deleted = 0';
}else{
  $sql .= " where categores = '$cat_id' and deleted = 0";
}
$price_sort = (($_POST['price_sort'] !='')?sanitize($_POST['price_sort']):'');
$min_price = (($_POST['min_price'] !='')?sanitize($_POST['min_price']):'');
$max_price = (($_POST['max_price'] !='')?sanitize($_POST['max_price']):'');
$brand = (($_POST['brand'] !='')?sanitize($_POST['brand']):'');
if($min_price != ''){
  $sql .=" and price >= '$min_price'";
}
if($max_price != ''){
  $sql .=" and price <= '$max_price'";
}
if($brand != ''){
  $sql .=" and brand ='$brand'";
}
if($price_sort == 'low'){
  $sql .=" order by price";
}
if($price_sort =='high'){
  $sql .=" order by price DESC";
}
$productQ= $db->query($sql);
$category = get_category($cat_id);


?>
          <!-- main content -->
          <div class ="col-md-8">
              <div class="row">
                <?php if($cat_id !=''): ?>
                  <h2 class="text-center"><?=$category['parent'].' '. $category['child'];?></h2>
                <?php else:?>
                  <h2 class="text-center"> hary kane</h2>
                <?php endif; ?>
              <?php while($product = mysqli_fetch_assoc($productQ)) :  ?>
                <!-- mysqli_fetch_assoc return an array, store to $product-->

                <div class="col-md-3">
                      <h4 style="text-align:center">   <?php echo $product['title'];   ?> </h4>


                      <img src="images/products/<?php echo$product['image'];?>" alt="<?php echo $product['title'];   ?>"

                      height="220px" width="210px"      >

                      <p class="list-price text-danger" style="text-align:center">List Price <s> $<?php echo $product['list_price'];   ?></s></p>
                      <p class="price" style="text-align:center">Our Price: $<?php echo $product['price'];   ?></p>

                       <button type="button" class="btn btn-sm btn-success " style="margin-top:10px; margin-left:60px;" onclick="detailsmodal(<?=$product['id'];?>)">  Details</button>
                       <hr>

                </div>
                <?php endwhile; ?>
              </div>


          </div>

          <!-- right side bar-->




  <!-- footer-->


  <!-- Detail Modal -->

  <?php
  include "includes/rightbar.php";
  include "includes/footer.php";

?>
<script>
  jQuery(window).scroll(function(){
    var vscroll = jQuery(this).scrollTop();
    jQuery('#logotext').css({
    "transform": "translate(0px,"+vscroll/2+"px)"
  });

  var vscroll = jQuery(this).scrollTop();
  jQuery('#back-flower').css({
  "transform": "translate("+vscroll/5+"px, -"+vscroll/12+"px)"
});

var vscroll = jQuery(this).scrollTop();
jQuery('#fore-flower').css({
"transform": "translate(0px,-"+vscroll/2+"px)"
});

  });



  function detailsmodal(id){

    // alert(id);
    var data = {"id" : id};
    jQuery.ajax({
     url:'/fuckIDE/includes/detailsmodal.php',

      type : "POST",
      data: data,
      success : function(data){
// remove data modal when close ones;
        if(jQuery('#details-modal').length){
          jQuery('#details-modal').remove();
        }


        jQuery('body').append(data);
        jQuery('#details-modal').modal('toggle');





      },
      error: function(){
        alert("Something went wrong");
      }


    });
  }









</script>
</body>
</html>
