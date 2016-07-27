<?php
session_start();
if(!isset( $_SESSION['myusername'] )){
	header("location:index.php");
}
require_once 'includes/conn.php';
require_once '../includes/config_names.php';
require_once 'includes/html_helper.php';
require_once 'includes/functions.php';

require_once 'includes/models/keg.php';
require_once 'includes/models/kegType.php';
require_once 'includes/models/kegStatus.php';
require_once 'includes/models/beer.php';

require_once 'includes/managers/keg_manager.php';
require_once 'includes/managers/kegStatus_manager.php';
require_once 'includes/managers/kegType_manager.php';
require_once 'includes/managers/beer_manager.php';

$htmlHelper = new HtmlHelper();
$kegManager = new KegManager();
$kegStatusManager = new KegStatusManager();
$kegTypeManager = new KegTypeManager();
$beerManager = new BeerManager();

$config = array();
$sql = "SELECT * FROM config";
$qry = mysqli_query($con,$sql);
while($c = mysqli_fetch_array($qry))
{
  $config[$c['configName']] = $c['configValue'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$keg = new Keg();
	$keg->setFromArray($_POST);
	$kegManager->Save($keg);
	redirect('keg_list.php');
}

if( isset($_GET['id'])){
	$keg = $kegManager->GetById($_GET['id']);
}else{
	$keg = new Keg();
}

$kegStatusList = $kegStatusManager->GetAll();
$kegTypeList = $kegTypeManager->GetAll();
$beerList = $beerManager->GetAllActive();

if( isset($_GET['beerId'])){
	$beer = $beerManager->GetById($_GET['beerId']);
}else{
	$beer = new Beer();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $config[ConfigNames::PageTitle]; ?></title>
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
			<li><a href="keg_list.php">Keg List</a></li>
			<li>/</li>
			<li class="current">Keg Form</li>
		</ul>
	</div>
	<!-- Top Breadcrumb End --> 
	
	<!-- Right Side/Main Content Start -->
	<div id="rightside">
		<div class="contentcontainer med left">
	<p>
		fields marked with an * are required

	<form id="keg-form" method="POST">
		<input type="hidden" name="id" value="<?php echo $keg->get_id() ?>" />

		<table width="950" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td>
					Label: <b><font color="red">*</color></b>
				</td>
				<td>
					<input type="text" id="label" class="mediumbox" name="label" value="<?php echo $keg->get_label() ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Type: <b><font color="red">*</color></b>
				</td>
				<td>
					<?php echo $htmlHelper->ToSelectList("kegTypeId", $kegTypeList, "name", "id", $keg->get_kegTypeId(), "Select One"); ?>
				</td>
			</tr>	
			<tr>
				<td>
					Notes: <b><font color="red"></color></b>
				</td>
				<td>
					<textarea id="notes" class="text-input textarea" name="notes" style="width:500px;height:100px"><?php echo $keg->get_notes() ?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Status: <b><font color="red">*</color></b>
				</td>
				<td>
					<?php echo $htmlHelper->ToSelectList("kegStatusCode", $kegStatusList, "name", "code", $keg->get_kegStatusCode(), "Select One"); ?>
				</td>
			</tr>
			<tr>
				<td>
					<b>Beer Name: </b>
				</td>
				<td>
					<?php echo $htmlHelper->ToSelectList("beerId", $beerList, "name", "id", $keg->get_beerId(), "Select One"); ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input name="save" type="submit" class="btn" value="Save" />
					<input type="button" class="btn" value="Cancel" onclick="window.location='keg_list.php'" />
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

<script>
	$(function() {		
		var beerList = { 
			<?php foreach($beerList as $beerItem){ 
				echo $beerItem->get_id() . ": " . $beerItem->toJson() . ", "; 
			} ?>
		};
		
		$('#keg-form').validate({
			rules: {
				label: { required: true, number: true },
				kegTypeId: { required: true },
				kegStatusCode: { required: true },
				notes: { required: false },
				beerId: { required: false }
			}
		});
		
	});
</script>

	<!-- End Js -->
	<!--[if IE 6]>
	<script type='text/javascript' src='scripts/png_fix.js'></script>
	<script type='text/javascript'>
	DD_belatedPNG.fix('img, .notifycount, .selected');
	</script>
	<![endif]--> 
	
</body>
</html>
