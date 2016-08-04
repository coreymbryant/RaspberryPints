<?php
session_start();
if(!isset( $_SESSION['myusername'] )){
}
require_once '../admin/includes/functions.php';
require_once '../admin/includes/conn.php';

if( isset($_GET['tapId'])){
  $tapId = $_GET["tapId"];
  $sql = "INSERT INTO pours (tapId, amountPoured, createdDate, modifiedDate)" .
    " values (" . $tapId . ", .125,NOW(),NOW())" ;
  mysqli_query($con,$sql);
}
if( isset($_GET['bottleId'])){
  $bottleId = $_GET["bottleId"];
  $sql = "UPDATE bottles SET currentAmount = currentAmount-1  where id=" . $bottleId . ";";
  mysqli_query($con,$sql);
}


/* echo $sql; */ 

header("location:../../index.php");
exit;
