<?php
session_start();
if(!isset( $_SESSION['myusername'] )){
header("location:index.php");
}
require 'includes/conn.php';
require '../includes/config_names.php';
require_once 'includes/functions.php';

// Get values from form 
$page_title=encode($_POST['page_title']);




// update data in mysql database
$sql="UPDATE config SET configValue='$page_title' WHERE configName ='pageTitle'";
$result=mysqli_query($con,$sql);

// if successfully updated.
if($result){
echo "Successful";
echo "<BR>";
echo "<script>location.href='personalize.php';</script>";
}

else {
echo "ERROR";
}

?> 
