<?php
require_once '../core/init.php';
if(!is_logged_in()){
  login_error_redirect();
}

include 'includes/headerAD.php';
include 'includes/navigationAD.php';

$sql = "select * from brand ORDER BY brand";
$results = $db->query($sql);

$errors = array();
// if add form is submited

//Edit Brands --- ngay  href đã truyền vào paramater 'edit'
if(isset($_GET['edit']) && !empty($_GET['edit'])){
$edit_id = (int) $_GET['edit'];
$sql2 ="select * from brand where id ='$edit_id'";
$edit_result = $db->query($sql2);
$eBrand = mysqli_fetch_assoc($edit_result);


}
//delete Brands
// ngay href đã truyền vào  paramater 'delete'
if(isset($_GET['delete']) && !empty($_GET['delete'])){
  $delete_id =(int)$_GET['delete'];
  $delete_id - sanitize($delete_id);

$sql ="Delete from brand where id ='$delete_id'";
$db->query($sql);
header('Location:brands.php'); // reload page
}

if(isset($_POST['add_submit'])){
  $brand = sanitize($_POST['brand']);

  //check if brand is blank
  if($_POST['brand']==''){
    $errors[] .='You must enter a brand';
  }
  //check if brand exists in Database
$sql = "select * from brand where brand ='$brand'";
if(isset($_GET['edit'])){
  $sql = "select * from brand where brand = '$brand' and id != '$edit_id'";
}
$result = $db->query($sql);
$count = mysqli_num_rows($result);

if($count > 0){
  $errors[] .= $brand.' That brand already exits. Please choose another brand name...';
}

  if(!empty($errors)){
    echo display_errors($errors);

  }
  else{
    //add brand to Database
    $sql = "insert into brand(brand) values('$brand')";
    if(isset($_GET['edit'])){
      $sql = "update  brand set brand = '$brand' where id = '$edit_id'";
    }
    $db->query($sql);
  //reload the page
    header('Location: brands.php');

  }

}


 ?>

 <h2 class="text-center">Brands</h2>
 <!-- Brand Form-->
<div class="text-center">
<form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="post">
  <div class="form-group">
    <?php
    $brand_value ='';
    if(isset($_GET['edit'])){
      $brand_value = $eBrand['brand'];
    }else{
      if(isset($_POST['brand'])){
        $brand_value = sanitize($_POST['brand']);
      }
    }

    ?>
    <label for="brand"> <?=((isset($_GET['edit']))?'Edit':'Add'); ?> Brand:</lable>
      <input type ="text" name="brand" id="brand" class="form-control" value ="<?= $brand_value; ?>">
      <?php  if(isset($_GET['edit'])):   ?>
        <a href="brands.php" class ="btn btn-default">Cancel</a>

      <?php endif ?>
      <input type ="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add'); ?> Brand" class="btn btn-lg btn success">

  </div>
</form>

<hr>

</div>

 <table class ="table table-bordered table-striped table-auto">
   <thead>
     <th></th><th>Brand</th><th></th>
   </thead>
   <tbody>
     <?php while($brand = mysqli_fetch_assoc($results)): ?>
     <tr>
         <td><a href="brands.php?edit=<?=$brand['id'];?>" class="btn btn-xs btn-default"> <span class="glyphicon glyphicon-pencil"> </a></span></td>
         <td><?=$brand['brand']?></td>
         <td><a href="brands.php?delete=<?=$brand['id'];?>" class="btn btn-xs btn-default"> <span class="glyphicon glyphicon-remove-sign"> </a></span></td>
     </tr>
   <?php endwhile;?>
   </tbody>
 </table>
 <?php include 'includes/footerAD.php' ?>
