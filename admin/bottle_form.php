<?php
session_start();
if(!isset( $_SESSION['myusername'] )){
	header("location:index.php");
}
require_once __DIR__.'/includes/conn.php';
require_once __DIR__.'/../includes/config_names.php';
require_once __DIR__.'/includes/html_helper.php';
require_once __DIR__.'/includes/functions.php';

require_once __DIR__.'/includes/models/bottle.php';
require_once __DIR__.'/includes/models/bottleType.php';
require_once __DIR__.'/includes/models/beer.php';

require_once __DIR__.'/includes/managers/bottle_manager.php';
require_once __DIR__.'/includes/managers/bottleType_manager.php';
require_once __DIR__.'/includes/managers/beer_manager.php';

$htmlHelper = new HtmlHelper();
$bottleManager = new BottleManager();
$bottleTypeManager = new BottleTypeManager();
$beerManager = new BeerManager();

$beerList = $beerManager->GetAllActive();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $bottle = new Bottle();
  $bottle->setFromArray($_POST);
  $bottleManager->Save($bottle);
	redirect('bottle_list.php');
}

if( isset($_GET['id'])){
	$bottle = $bottleManager->GetById($_GET['id']);
}else{
	$bottle = new Bottle();
}

$bottleTypeList = $bottleTypeManager->GetAll();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>RaspberryPints</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/wysiwyg.css" rel="stylesheet" type="text/css" />
	<!-- Theme Start -->
<link href="styles.css" rel="stylesheet" type="text/css" />
	<!-- Theme End -->
<link href='http://fonts.googleapis.com/css?family=Fredoka+One' rel='stylesheet' type='text/css'>
</head>
	<!-- Start Header  -->
<?php
include 'header.php';
?>
	<!-- End Header -->
		
	<!-- Top Breadcrumb Start -->
	<div id="breadcrumb">
		<ul>	
			<li><img src="img/icons/icon_breadcrumb.png" alt="Location" /></li>
			<li><strong>Location:</strong></li>
			<li><a href="bottle_list.php">Bottle List</a></li>
			<li>/</li>
			<li class="current">Bottle Form</li>
		</ul>
	</div>
	<!-- Top Breadcrumb End --> 
	
	<!-- Right Side/Main Content Start -->
	<div id="rightside">
		<div class="contentcontainer med left">
	<p>
		Fields marked with <b><font color="red">*</font></b> are required.<br><br>

	<form id="bottle-form" method="POST">
		<input type="hidden" name="id" value="<?php echo $bottle->get_id() ?>" />
		
		<table width="800" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td style="vertical-align:middle;">
					<b>Bottle type: <font color="red">*</font></b>
				</td>
				<td>
					<?php echo $htmlHelper->ToSelectList("bottleTypeId", $bottleTypeList, "name", "id", $bottle->get_bottleTypeId(), "Select One"); ?>
				</td>
			</tr>
			<tr>
				<td>
					 <b>Beer Name: <font color="red">*</color></b>
				</td>
				<td>
					<?php echo $htmlHelper->ToSelectList("beerId", $beerList, "name", "id", $bottle->get_beerId(), "Select One"); ?>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:middle;">
					<b>Cap RGBa: <font color="red">*</font></b>
				</td>
				<td>
          <input type="text" id="capRgba" class="mediumbox" name="capRgba" value="<?php echo $bottle->get_capRgba() ?>" /> 
          red,green,blue,alpha
				</td>
			</tr>
			<tr>
				<td style="vertical-align:middle;">
					<b>Cap Number: <font color="red">*</font></b>
				</td>
				<td>
					<input type="text" id="capNumber" class="mediumbox" name="capNumber" value="<?php echo $bottle->get_capNumber() ?>" />
          0 = NULL
				</td>
			</tr>
			<tr>
				<td style="vertical-align:middle;">
					<b>Start Amount</b> (bottles): <b><font color="red">*</font></b>
				</td>
				<td>
					<input type="text" id="startAmount" class="mediumbox" name="startAmount" value="<?php echo $bottle->get_startAmount() ?>" />
				</td>
			</tr>
			<tr>
				<td style="vertical-align:middle;">
					<b>Current Amount</b> (bottles): <b><font color="red">*</font></b>
				</td>
				<td>
					<input type="text" id="currentAmount" class="mediumbox" name="currentAmount" value="<?php echo $bottle->get_currentAmount() ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input name="save" type="submit" class="btn" value="Save" />
					<input name="cancel" type="button" class="btn" value="Cancel" onclick="window.location='bottle_list.php'"/>
				</td>
			</tr>
											
		</table>
		<br />
		<div align="right">			
			&nbsp &nbsp 
		</div>
	
	</form>
	</div>
	<!-- End On Tap Section -->

	<!-- Start Footer -->   
<?php 
include 'footer.php';
?>

	<!-- End Footer -->
		
	</div>
	<!-- Right Side/Main Content End -->
	<!-- Start Left Bar Menu -->   
<?php 
include 'left_bar.php';
?>
	<!-- End Left Bar Menu -->  
	<!-- Start Js  -->
<?php
include 'scripts.php';
?>


	<!-- End Js -->
	<!--[if IE 6]>
	<script type='text/javascript' src='scripts/png_fix.js'></script>
	<script type='text/javascript'>
	DD_belatedPNG.fix('img, .notifycount, .selected');
	</script>
	<![endif]--> 
	
<script>
	$(function() {
		
		$('#bottle-form').validate({
      rules: {
        bottleTypeId: { required: true },
        beerId: { required: true },
        capRgba: { required: true },
        capNumber: { required: true },
        startAmount: { required: true },
        currentAmount: { required: true }
      }
		});
		
	});
</script>

</body>
</html>
