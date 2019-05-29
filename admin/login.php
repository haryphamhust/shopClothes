<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fuckIDE/core/init.php';
include 'includes/headerAD.php';





$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST["password"]))?sanitize($_POST["password"]):'');

$hashed = password_hash($password, PASSWORD_DEFAULT);

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
if(empty($_POST['email']) || empty($_POST['password'])){
  $errors[] = "you must provide email and password";
}
//validate Email
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
  $errors[] = "you must enter a valid email";
}
// pastword is more than 6 characters
if(strlen($password) < 6){
  $errors[] = "Password must be be at least 6 character";
}
//check if email exists in the database$
$query = $db->query(" SELECT * from users where email='$email'");
$user = mysqli_fetch_assoc($query);
$userCount = mysqli_num_rows($query);

echo  $user['email'].    $user['password'];



if($userCount <1){
  $errors[] = "That email doesn't exist in our database";
}

$passCheck = trim($user["password"]);
$password = trim($password);

// if(password_verify($password,$passCheck)){

if($password===$passCheck){
  //log user in
  // $errors='Login success';
  $user_id = $user['id'];
  login($user_id);
}else{
  // $countPassCheck = strlen($passCheck);
  // $countPassWord = strlen($password);
  //
  // $value = password_verify($password,$passCheck);
  $errors[] = 'The password does not match our records, please try again';
  // echo 'two pass compare \n'.'<br>';
  // echo'$countPassWord:'.$countPassWord. $password.'<br>';
  // echo '$countPassCheck:'.$countPassCheck.$passCheck.'<br>';
  // echo 'boolean:'.(int)$value;
  // echo'<br>';
  // echo var_dump($passCheck);
  // echo var_dump($password);

}

if(!empty($errors)){
  echo display_errors($errors);
}

}

 ?>

  </div>
  <h2 class= "text-center">Login</h2>
  <form action="login.php" method = "post">
    <div class= "form-group">
      <label for="email">Email:</label>
      <input type="text" name="email" id="email" class="form-control" value="<?=$email;?>">
     </div>

     <div class= "form-group">
       <label for="password">Password:</label>
       <input type ="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div>

    <div class="form-group">
      <input type="submit" value="Login" class="btn btn-default">
    </div>
  </form>
  <p class= "text-right"><a href="/fuckIDE/index.php" alt="home">Visit site</a> </p>
</div>



<?php
include 'includes/footerAD.php';
 ?>
