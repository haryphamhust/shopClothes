<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fuckIDE/core/init.php';
if(!is_logged_in()){
  login_error_redirect();
}
include 'includes/headerAD.php';

$hashed = $user_data['password'];

$password = ((isset($_POST["password"]))?sanitize($_POST["password"]):'');
$password = trim($password);

$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);

$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);

$new_hashed = password_hash($password,PASSWORD_DEFAULT);
$user_id = $user_data['id'];

$errors = array();




?>
<style>
#login-form{
width: 50%;
height: 60%;
border: 2px solid #000;
border-radius: 15px;
box-shadow: 7px 7px 15px rgba(0, 0, 0, 0.6);
margin: 5% auto;
padding: 15px;
background-color: #fff;
}
body{
background-image:url("/fuckIDE/images/headerlogo/background.png");
background-size:100vw 100vh;
background-attachment: fixed;
}
</style>

<div id = "login-form">
  <div>
<?php
if($_POST){
//form validation
if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
  $errors[] = "you must provide Old password, password and confirm";
}

// pastword is more than 6 characters
if(strlen($password) < 6){
  $errors[] = "Password must be be at least 6 character";
}
//if new password matches Confirm
if($password != $confirm){
  $errors[] = "The new password and confirm new password does not match";
}


if($hashed !=$old_password){
  $errors[] = 'The password does not match our records, please try again';
}

if(!empty($errors)){
  echo display_errors($errors);
}else{
//change PASSWORD
$db->query("UPDATE users SET password = '$new_hashed' where id = '$user_id'");
$_SESSION['success_flash']= 'Your password has been updated';
header('Location:index.php');
}

}

 ?>

  </div>
  <h2 class= "text-center">Change Password</h2>
  <form action="change_password.php" method = "post">
    <div class= "form-group">
      <label for="old_password">Old Password:</label>
      <input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password;?>">
     </div>

     <div class= "form-group">
       <label for="password">New Password:</label>
       <input type ="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div>

    <div class= "form-group">
      <label for="confirm">Confirm New Password:</label>
      <input type ="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
   </div>

    <div class="form-group">
      <input type="submit" value="Change" class="btn btn-primary">
      <a href="index.php" class="btn btn-primary">Cancel</a>
    </div>
  </form>
  <p class= "text-right"><a href="/fuckIDE/index.php" alt="home">Visit site</a> </p>
</div>



<?php
include 'includes/footerAD.php';
 ?>
