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
}
if( isset($_GET['bottleId'])){
  $bottleId = $_GET["bottleId"];
  $sql = "INSERT INTO drank (bottleId, amountDrank, createdDate, modifiedDate)" .
    " values (" . $bottleId . ", 1,NOW(),NOW())" ;
}


/* echo $sql; */ 

mysqli_query($con,$sql);
header("location:../../index.php");
exit;
