<?php session_start(); ?>

<?php

if (isset($_GET['action'])) {
  $do = $_GET['action'];
} else {
  $do = "index";
}

?>
<?php if(isset($_SESSION['ID'])):?>
<?php include "config.php" ?>
<?php include "includes/header.php" ?>
<?php include "includes/navbar.php" ?>

<?php if ($do == "index") : ?>
  <?php if (isset($_GET['open']) && $_GET['open'] == 'admin') : ?>
    <h1 class="text-center">All Admin & Moderator</h1>
  <?php else : ?>
    <h1 class="text-center"> All Members</h1>
  <?php endif ?>
  <?php
  // condition to check the page admin tabed on it
  $role = isset($_GET['open']) && $_GET['open'] == 'admin' ? "!=3" : "=2";
  $stmt = $con->prepare("SELECT * FROM `users`WHERE `role` $role ");
  $stmt->execute();
  $users = $stmt->fetchAll();
  ?>
  <div class="container">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">image</th>
          <th scope="col">username</th>
          <th scope="col">created_at</th>
          <th scope="col">control</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user) : ?>
          <tr>
            <th scope="row">1</th>

            <td> <img src="uploads/<?= $user['img'] ?> " style="height:10vh"></td>
         
             
            <td><?= $user['username'] ?></td>
            <td><?= $user['created_at'] ?></td>
            <td>
              <?php if ($_SESSION['ROLE'] == 3) : ?>
                <a class="btn btn-info" href="?action=show&selection=<?= $user['id'] ?>"> show</a>
              <?php else : ?>
                <a class="btn btn-info" href="?action=show&selection=<?= $user['id'] ?>"> show</a>
                <a class="btn btn-warning" href="?action=edit&selection=<?= $user['id'] ?>"> edit</a>
                <a class="btn btn-danger" href="?action=delete&selection=<?= $user['id'] ?>"> delete</a>
              <?php endif ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <a class="btn btn-primary" href="?action=create">add user</a>
  </div>
<?php elseif ($do == "create") : ?>
  <h1 class=text-center> add user</h1>
  <div class="container">
    <form method="post" action="?action=store  "enctype="multipart/form-data" >
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Username</label>
        <input type="text" class="form-control" name="username">
        
      </div>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input type="email" class="form-control" name="email" >
        
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" class="form-control" name="password">
      </div>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">phone</label>
        <input type="number" class="form-control" name="phone">
      </div>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Upload image</label>
        <input type="file" class="form-control" name="image">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
 


<?php elseif ($do == "store") : ?>
  <?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // upload image
    $imagename = $_FILES['image']['name'];
    $imagetype = $_FILES['image']['type'];
    $imagetmp = $_FILES['image']['tmp_name'];
    $imageAllowedExtension = array("image/jpeg", "image/jpg", "image/png");
    if (in_array($imagetype, $imageAllowedExtension ,$imagename)) {
      $image = rand(0, 1000) .$imagename ;
      move_uploaded_file($imagetmp, "uploads/" .$image);
      $username = $_POST['username'];
      $email = $_POST['email'];
      $password = sha1($_POST['password']);
      $phone = $_POST['phone'];}
      else {
      echo "check your extension";}

      if(empty($username)){
        echo "add username";
      }elseif(empty($email)){
        echo "add email";
      }elseif(empty($password)){
        echo  "add password";
      }elseif(empty($phone)){
        echo "add phone";
      }
      else{
      $stmt = $con->prepare(
        "INSERT INTO `users`( `username`, `email`, `password`, `role`, `phone`, `img`, `created_at`) 
         VALUES (?,?,?,2,?,?,now() )"
      );
      $stmt->execute(array($username, $email, $password, $phone, $image));

      header("location:members.php");
    } 
    
    }
  
  ?>

<?php elseif ($do == "edit") : ?>
  <?php
  $userid = $_GET['selection'];
  $stmt = $con->prepare("SELECT * FROM users WHERE `id` =?");
  $stmt->execute(array($userid));
  $user = $stmt->fetch();
  $count = $stmt->rowcount();
  ?>
  <?php if ($count == 1) : ?>
    <h1 class=text-center> Edit user</h1>
    <div class="container">
      <form method="post" action="?action=update">
        <input type="hidden" class="form-control" name="userid" value="<?= $user['id'] ?>">
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Username</label>
          <input type="text" class="form-control" name="username" value="<?= $user['username'] ?>">
        </div>
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Email address</label>
          <input type="email" class="form-control" name="email" value="<?= $user['email'] ?>">
        </div>
        <div class="mb-3">
          <label for="exampleInputPassword1" class="form-label">Password</label>
          <input type="password" class="form-control" name="newpassword">
          <input type="password" class="form-control" name="oldpassword" value="<?= $user['password'] ?>">
        </div>
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">phone</label>
          <input type="number" class="form-control" name="phone" value="<?= $user['phone'] ?>">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
    <?php else:?>
      <script>
        window.history.back();
      </script>
  <?php endif ?>
  


<?php elseif ($do == "update") : ?>
  <?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //  if condition to check password
    // if(!empty($_POST['newpassword'])){
    // $password = sha1($_POST['newpassword']);
    // }else{
    //   $password= $_POST['oldpassword'];
    // }

    $password = !empty($_POST['newpassword']) ? sha1($_POST['newpassword']) : $_POST['oldpassword'];
    $userid = $_POST['userid'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $stmt = $con->prepare("UPDATE `users` SET `username`=?, `password`=? ,`email`=?,`phone`=? WHERE `id`=? ");
    $stmt->execute(array($username, $email, $password, $phone, $userid));
    header("location:members.php");
  }
  ?>

<?php elseif ($do == "show") : ?>
  <?php
  $userid = $_GET['selection'];
  $stmt = $con->prepare("SELECT * FROM users WHERE `id` =?");
  $stmt->execute(array($userid));
  $user = $stmt->fetch();
  // echo "<pre>";
  // print_r($user);
  // echo "</pre>";
  ?>
      <h1 class=text-center> Show user</h1>
      <div class="container">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">image</th>
          <th scope="col">username</th>
          <th scope="col">created_at</th>
          <th scope="col">email</th>
          <th scope="col">phone</th>

        </tr>
      </thead>
      <tbody>
          <tr>
            <th scope="row">1</th>
            <td> <img src="/uploads/<?= $user['img']?> " style="height:10vh"></td>
            <td><?= $user['username'] ?></td>
            <td><?= $user['created_at'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['phone'] ?></td>

          </tr>
      </tbody>
    </table>



<?php elseif ($do == "delete") : ?>
  <?php
  $userid = $_GET['selection'];
  $stmt = $con->prepare("DELETE FROM `users` WHERE `id`= ?");
  $stmt->execute(array($userid));
  header("location:members.php");

  ?>
<?php else : ?>
  <h1>404 page</h1>
<?php endif ?>




<?php require "includes/footer.php" ?>
<?php else: ?>
  <?php header("location:index.php")?>
  <?php endif?>