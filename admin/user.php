<?php
require_once '../core/init.php';
if(!is_logged_in()){
  login_error_redirect();
}

if(!has_permission()){
  permission_error_redirect('index.php');
}
include 'includes/headerAD.php';
include 'includes/navigationAD.php';
$userQuery = $db->query("Select * from users order by full_name");

//deleted

if(isset($_GET['delete'])){
  $delete_id = sanitize($_GET['delete']);
  $db->query("Delete from users where id = '$delete_id'");
  $_SESSION['success_flash'] = 'User has been deleted';
  header('Location: user.php');
}

//add new users

if(isset($_GET['add'])){
  $name=((isset($_POST['name']))?sanitize($_POST['name']):'');
  $email=((isset($_POST['email']))?sanitize($_POST['email']):'');
  $password=((isset($_POST['password']))?sanitize($_POST['password']):'');
  $confirm=((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
  $permission=((isset($_POST['permission']))?sanitize($_POST['permission']):'');

  $errors= array();
  if($_POST){
    //validate form
    $emailQuery = $db->query("select * from users where email='$email'");
    $emailCount = mysqli_num_rows($emailQuery);

    if($emailCount != 0){
      $errors[] = 'That email already exists in our database';
    }
    $required = array('name','email','password','confirm','permission');
    foreach ($required as $f) {
      if(empty($_POST[$f])){
        $errors[] = 'You must fill out all fields';
        break;
      }
    }
    if(strlen($password)<6){
      $errors[] = 'Your password must be at least 6 charates';
    }
    if($password != $confirm){
      $errors[] ='Your password does not match';
    }

    if(!empty($errors)){
      echo display_errors($errors);
    }else{
      //add user to database$
      $hashed = password_hash($password,PASSWORD_DEFAULT);
      $db->query("insert into users(full_name,email,password,permissions) values('$name','$email','$hashed','$permission')");
      $_SESSION['success_flash'] = 'User has been added';
      header('Location: user.php');

    }
  }
?>
<h2 class="text-center"> Add A New User </h2><hr><br>
  <form action="user.php?add=1" method="post">
    <div class="form-group col-md-6">
        <label for="name"> Full Name: </label>
        <input type="text" name="name" id="name" class="form-control" value="<?=$name;?>">
    </div>

    <div class="form-group col-md-6">
        <label for="email"> Email: </label>
        <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
    </div>

    <div class="form-group col-md-6">
        <label for="password"> Password: </label>
        <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div>
    <div class="form-group col-md-6">
        <label for="confirm">Confirm Password: </label>
        <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
    </div>
    <div class="form-group col-md-6">
      <label for="permission">Permissions</label>
      <select class="form-control" name="permission">
        <option value=""<?=(($permission == '')?'selected':'') ;?>></option>
        <option value="editor"<?=(($permission == 'editor')?'selected':'') ;?>>Editor</option>
        <option value="admin-editor"<?=(($permission == 'admin-editor')?'selected':'') ;?>>Admin</option>
      </select>
    </div>
    <div class="form-group col-md-6 text-center" style="margin-top:25px">
      <input type="submit" value="Add User" class="btn btn-primary">
      <a href="user.php" class="btn btn-default">Cancel</a>

    </div>

  </form>
<?php
}else{

 ?>
<div>
  <h2 class="text-center">Users</h2>
  <a href="user.php?add=1" class="btn btn-success pull-right" style="margin-top:-40px">Add new User</a>
</div>
<hr>
<br>
<table class="table table-bordered table-striped table-condensed">
  <thead>
      <th>Tools</th>
      <th> Name </th>
      <th> Email</th>
      <th>Join Date </th>
      <th> Last Login</th>
      <th> permissions</th>
  </thead>
  <tbody>
    <?php while($user=mysqli_fetch_assoc($userQuery)):?>
        <tr>
            <td>
              <?php if($user['id'] != $user_data['id']): ?>
                    <a href="user.php?edit=<?=$user['id']; ?>" class = "btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="user.php?delete=<?=$user['id'];?>"  onclick="return  confirm('do you want to delete this user Y/N ???')"  class = "btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
              <?php endif;?>
            </td>
            <td><?=$user['full_name']; ?> </td>
            <td><?=$user['email']; ?> </td>
            <td><?=pretty_date($user['join_date']); ?> </td>
            <td><?= pretty_date($user['last_login']); ?> </td>
            <td><?=$user['permissions']; ?> </td>
        </tr>
  <?php endwhile; ?>
  </tbody>
</table>
</br>



<?php }  include 'includes/footerAD.php'; ?>
