<?php session_start();?>



<?php 

if(isset($_GET['action'])){
    $do = $_GET['action'];
}else{
    $do = "index";
}

?>
<?php if(isset($_SESSION['ID'])):?>
  <?php include "config.php"?>
<?php include "includes/header.php"?>
<?php include "includes/navbar.php"?>
<?php if ($do == "index") : ?>
    <h1 class="text-center">All Products</h1>
    <?php

    $stmt = $con->prepare("SELECT * FROM `products`");
    $stmt->execute();
    $products = $stmt->fetchAll();

?>
<div class="container">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">pro_name</th>
      <th scope="col">pro_price</th>
      <th scope="col">category</th>
      <th scope="col">control</th>

    </tr>
  </thead>
  <tbody>
      <?php foreach ($products as $product) :?>

        <th scope="row">1</th>
      <td><?= $product['pro_name']?></td>
      <td><?= $product['pro_price']?></td>
      <td><?= $product['category']?></td>
      <td >
          <a class = "btn btn-info"> show</a>
          <a class = "btn btn-warning"> edit</a>
          <a class="btn btn-danger"> delete</a>
    </td> 
    </tr>
        <?php endforeach ?>
        </tbody>
</table>
<a class="btn btn-primary" href="?action=create">add product</a>

</div>

<?php elseif($do == "create"):?>
    <h1 class=text-center> add product</h1>
<div class="container">
<form method="post" action="?action=store">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">pro_name</label>
    <input type="text" class="form-control" name="pro_name">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">pro_price</label>
    <input type="text" class="form-control" name="pro_price" >
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">category</label>
    <input type="text" class="form-control" name="category">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
<?php elseif($do == "store"):?>
    <?php
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $pro_name = $_POST['pro_name'];
        $pro_price =$_POST['pro_price'];
        $category = $_POST['category'];
        $stmt =$con->prepare(
            "INSERT INTO `products`( `pro_name`, `pro_price`, `category`) 
            VALUES (?,?,?)");
        $stmt->execute(array($pro_name , $pro_price, $category ));
        header("location:products.php");

    }
        ?>

<?php endif?>


<?php include "includes/footer.php"?>
<?php else: ?>
  <?php header("location:index.php")?>
  <?php endif?>