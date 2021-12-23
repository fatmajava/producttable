
<?php session_start();?>
<?php if(isset($_SESSION['ID'])):?>
<?php include "config.php"?>
<?php include "includes/header.php"?>
<?php include "includes/navbar.php"?>







<?php require "includes/footer.php"?>
<?php else: ?>
  <?php header("location:index.php")?>
  <?php endif?>

