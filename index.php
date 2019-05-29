<?php
require_once "core/init.php";
include "includes/head.php";
include "includes/navigation.php";
include "includes/headerfull.php";
include "includes/leftbar.php";

$sql = "select * from products where featured = 1";
$featured= $db->query($sql);


?>
<style>
.bigHr {
    background-color: dimgrey !important;
    color: dimgrey !important;
    border: solid 0.5px dimgrey !important;
    height: 0.5px !important;
    width: 200px !important;
}

</style>
  <!-- Top nav bar-->

      <!-- Header logotext-->

        <!-- left side bar-->

          <!-- main content -->
          <div class ="col-md-8">
              <div class="row">
                <br>
                  <h2 class="text-center">Feature Products</h2>
                <hr  class="bigHr">
                  <br>
              <?php while($product = mysqli_fetch_assoc($featured)) :  ?>
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

        function add_to_cart(){
          jQuery('#modal_errors').html(" ");
          var size = jQuery('#size').val();
          var quantity = jQuery('#quantity').val();
          var available = jQuery('#available').val();
          var error = ' ';
          var data = jQuery('#add_product_form').serialize() ;

          if(size == ''|| quantity == ' '|| quantity == 0 ) {
            error += '<p class="text-danger text-center">You must choose a size and quantity.</p>';
            jQuery('#modal_errors').html(error);
            return;
          }else if( quantity > available){
            error += '<p class="text-danger text-center">There are  only '+available+' available.</p>';
            jQuery('#modal_errors').html(error);
            return;
          }else{
            jQuery.ajax({
              url: '/fuckIDE/admin/parsers/add_cart.php',
              method: 'post',
              data: data,
              success: function(){
                  location.reload();

              },
              error: function(){
                alert('something went wrong');
              }
            });
          }
        }

  
      </script>
</body>
</html>
