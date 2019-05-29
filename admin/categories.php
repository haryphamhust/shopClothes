<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fuckIDE/core/init.php';
if(!is_logged_in()){
  login_error_redirect();
}

include 'includes/headerAD.php';
include 'includes/navigationAD.php';


$sql = "select * from categories where parent = 0";
$result = $db->query($sql);
$errors = array();
$category = '';

// Edit Category
if(isset($_GET['edit']) && !empty($_GET['edit'])){
  $edit_id = (int)$_GET['edit'];
  $edit_id = sanitize($edit_id);
  $edit_sql = "select * from categories where id = '$edit_id'";
  $edit_result = $db->query($edit_sql);
  $edit_category = mysqli_fetch_assoc($edit_result);


}


//Delete category
if(isset($_GET['delete']) && !empty($_GET['delete'])){
$delete_id = (int)$_GET['delete'];
$delete_id = sanitize($delete_id);
$sql = "select * from categories where id = '$delete_id'";
$result = $db->query($sql);
$catagory = mysqli_fetch_assoc($result);
if($category['parent']== 0){
  $sql = "delete from categories where parent ='$delete_id'";
  $db->query($sql);

}
$dsql = "delete from categories where id = '$delete_id'";
$db->query($dsql);
header("Location:categories.php");


}







//Process form

if(isset($_POST) && !empty($_POST)){
$parent = sanitize($_POST['parent']);
$category = sanitize($_POST['category']);

$sqlform = "select * from categories where category = '$category' and parent = '$parent'";
$fresult = $db->query($sqlform);
$count = mysqli_num_rows($fresult);
  //if category is blank
  if( $category == ''){
    $errors[] .='The category cannot be left blank';
  }
  //if exists in the Database
  if( $count >0){
    $errors[] .= $category. ' already exist. pleaase choose a new category'  ;
  }
  //Display errors or update Database
  if(!empty($errors)){
    //display errors
    $display = display_errors($errors); ?>
    <script>
      jQuery('document').ready(function(){
        jQuery('#errors').html('<?=$display;?>');
      });
    </script>

<?php  }else{
    //updata database

    $updatesql = "insert into categories(category,parent) values('$category','$parent')";
    $db->query($updatesql);
    header('Location: categories.php');

  }

}

$category_value = '';
if(isset($_GET['edit'])){
$category_value = $edit_category['category'];
}else{
  if(isset($_POST)){
    $category_value = $category;

  }
}

 ?>

 <h2 class="text-center"> Categories </h2><hr>
 <div class="row">
   <div class="col-md-6">
     <form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method = "post">
        <legend><?=((isset($_GET['edit']))?'Edit':'Add A'); ?> Category </legend>
          <div id="errors"></div>
       <div class="form-group">
         <label for="parent">Parent</label>
         <select class="form-control" name="parent" id="parent">
            <option value ="0"> Parent</option>
           <?php while($parent = mysqli_fetch_assoc($result)) : ?>
             <option value="<?=$parent['id'];?>"><?=$parent['category'];?></option>
           <?php endwhile;?>
         </select>
       </div>

       <div class="form-group">
          <label for="category">Category</label>
          <input type="text" class="form-control" id="category" name="category" value="<?=$category_value; ?>">
       </div>

       <div class="form-group">
         <input type="submit" value ="Add Category" class="btn btn-success">
      </div>

     </form>

   </div>

   <!-- Category table-->
   <div class="col-md-6">
      <table class="table table-bordered">
          <thead>
            <th>Category</th>
            <th> Parent</th>
            <th></th>
          </thead>
          <tbody>
              <?php
                $sql = "select * from categories where parent = 0";
                $result = $db->query($sql);
               while($parent = mysqli_fetch_assoc($result)):
                  $parent_id = $parent['id'];
                  $sql2 = "select * from categories where parent='$parent_id'";
                  $cresult = $db->query($sql2);
              ?>
              <tr class="bg-primary">
                  <td><?=$parent['category']; ?></td>
                  <td>Parent</td>
                  <td>
                      <a href="categories.php?edit=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                      <a href="categories.php?delete=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
                  </td>
              </tr>

            <?php while($child = mysqli_fetch_assoc($cresult)): ?>
              <tr class="bg-info">
                  <td><?=$child['category']; ?></td>
                  <td><?=$parent['category']?></td>
                  <td>
                      <a href="categories.php?edit=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                      <a href="categories.php?delete=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
                  </td>
              </tr>
            <?php endwhile; ?>
          <?php endwhile; ?>
          </tbody>
      </table>
   </div>

 </div>

 <?php
include 'includes/footerAD.php';
  ?>
