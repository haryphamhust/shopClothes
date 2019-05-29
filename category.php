<?php
require_once "core/init.php";
include "includes/head.php";
include "includes/navigation.php";

include "includes/headerfull.php";
include "includes/leftbar.php";

if(isset($_GET['cat'])){
  $cat_id = sanitize($_GET['cat']);
}else{
  $cat_id ='';
}


$sql = "select * from products where categories = '$cat_id'";
$productQ= $db->query($sql);
$category = get_category($cat_id);


?>
          <!-- main content -->
          <div class ="col-md-8">
              <div class="row">
                  <h2 class="text-center"><?=$category['parent'].' '. $category['child'];?></h2>
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
