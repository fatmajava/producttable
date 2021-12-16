
<!-- search about require \ include _once | require_once -->
<!-- regex -->

<!-- crud => create record update delete -->
<?php session_start();?>
<?php include "config.php"?>
<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $email = $_POST['adminemail'];
    $password = sha1($_POST['adminpass']);
    $stmt = $con->prepare("SELECT * FROM `users` WHERE `email`=? AND `password`=? ");
    $stmt->execute(array($email , $password));
    $count = $stmt->rowCount();
    $user = $stmt->fetch();
    if ($count == 1){
        $_SESSION['ID'] =$user['id'];
        $_SESSION['USERNAME'] =$user['username'];
        $_SESSION['EMAIL'] =$user['email'];
        $_SESSION['ROLE'] =$user['role'];
        header("Location:dashboard.php");
    }else{
        echo "sorry dont have permeiision";
    }
}

?>
<?php include "includes/header.php"?>
  <body>
      <div class="container-fluid">
          <h1 class="text-center">admin panel</h1>
  <form method="post" action="index.php">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="email" class="form-control" name="adminemail" id="email">
    <div id="emailHelp" class="form-text"></div>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" name="adminpass">
  </div>
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
  <?php include "includes/footer.php"?>

  </body>
</html>