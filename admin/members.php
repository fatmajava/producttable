
<?php session_start();?>
<?php include "config.php"?>
<?php include "includes/header.php"?>
<?php include "includes/navbar.php"?>

<?php 

if(isset($_GET['action'])){
    $do = $_GET['action'];
}else{
    $do = "index";
}

?>

<?php if ($do == "index") : ?>
    <h1 class="text-center">All Members</h1>
    <?php 
    $stmt =$con->prepare("SELECT * FROM `users` ");
    $stmt->execute();
    $users =$stmt->fetchAll();
    ?>
    <div class= "container">
    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">username</th>
      <th scope="col">created_at</th>
      <th scope="col">control</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($users as $user) : ?>
    <tr>
      <th scope="row">1</th>
      <td><?= $user['username']?></td>
      <td><?= $user['created_at']?></td>
      <td >
          <a class = "btn btn-info"> show</a>
          <a class = "btn btn-warning"> edit</a>
          <a class="btn btn-danger"> delete</a>
    </td> 
    </tr>
    <?php endforeach ?>
  </tbody>
</table>
<a class="btn btn-primary" href="?action=create">add user</a>
    </div>
<?php elseif($do == "create"):?>
     <h1 class=text-center> add user</h1>
     <div class="container">
     <form method="post" action="?action=store">
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
 
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
     </div>
<?php elseif($do == "store"):?>
     <?php 
     if ($_SERVER['REQUEST_METHOD'] == 'POST'){
         $username = $_POST['username'];
         $email =$_POST['email'];
         $password =sha1($_POST['password']);
         $phone = $_POST['phone'];
         $stmt =$con->prepare(
           "INSERT INTO `users`( `username`, `email`, `password`, `role`, `phone`, `img`, `created_at`) 
         VALUES (?,?,?,'user',?,Null,now() )");
         $stmt->execute(array($username , $email , $password , $phone));
         header("location:members.php");

     }
        ?>
     <?php elseif($do == "edit"):?>
     <h1> Hello edit page</h1>
     <?php elseif($do == "update"):?>
     <h1> Hello update page</h1>
     <?php elseif($do == "show"):?>
     <h1> Hello show page</h1>
     <?php elseif($do == "delete"):?>
     <h1> Hello delete page</h1>
     <?php else: ?>
        <h1>404 page</h1>
    <?php endif?>




<?php include "includes/footer.php"?>


