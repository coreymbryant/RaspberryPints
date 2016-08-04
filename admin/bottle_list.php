<?php
session_start();
if(!isset( $_SESSION['myusername'] )){
	header("location:index.php");
}

require_once __DIR__.'/includes/conn.php';
require_once __DIR__.'/includes/functions.php';
require_once __DIR__.'/includes/html_helper.php';
require_once __DIR__.'/../includes/config_names.php';

require_once __DIR__.'/includes/models/bottle.php';
require_once __DIR__.'/includes/models/bottleType.php';
require_once __DIR__.'/includes/models/beer.php';

require_once __DIR__.'/includes/managers/bottle_manager.php';
require_once __DIR__.'/includes/managers/bottleType_manager.php';
require_once __DIR__.'/includes/managers/beer_manager.php';

$htmlHelper = new HtmlHelper();
$bottleManager = new BottleManager();
$bottleManager->UpdateCounts();
$bottleTypeManager = new BottleTypeManager();
$beerManager = new BeerManager();

if( isset($_POST['newBottle'])){
	redirect("bottle_form.php");
	
}else if( isset($_POST['editBottle'])){
	$id=$_POST['id'];
	redirect("bottle_form.php?id=$id");

}else if( isset($_POST['removeBottle'])){
	$bottleManager->removeBottle($_POST['id']);	
}

$numberOfBottles = $bottleManager->getBottleCount();
$activeBottles = $bottleManager->getActiveBottles();
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
			<li class="current">Bottle List</li>            
		</ul>
	</div>
	<!-- Top Breadcrumb End --> 
	
	<!-- Right Side/Main Content Start -->
	<div id="rightside">
		<div class="contentcontainer med left">
<br />
	<!-- Start On Tap Section -->
	
			<form method="POST">
				<table width="800" border="0" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th>Cap</th>
							<th>Name</th>
							<th>Volume</th>
							<th>Beer</th>
							<th>Start Amount</th>
							<th>Current Amount</th>
							<th colspan="2"></th>
						</tr>
					</thead>
					<tbody>
            <?php foreach ($activeBottles as $bottle) { ?>
							<form method="POST">
										<input type="hidden" name="id" value="<?php echo $bottle->get_id()?>" />
										<tr>
											<td>
                        <?php if ($bottle->get_capNumber() != 0){ ?>
                          <span class="bottlecircle" style="background-color: rgba(<?php echo $bottle->get_capRgba() ?>)">
                          <?php echo $bottle->get_capNumber() ?>
                          </span>
                        <?php }else{ ?>
                          <span class="bottlecircle" style="background-color: rgba(<?php echo $bottle->get_capRgba() ?>)">&nbsp</span>
                        <?php } ?>
											</td>
											
											<td>
												<?php echo $bottleTypeManager->GetById($bottle->get_bottleTypeId())->get_name() ?>
											</td>
											
											<td>
												<?php echo $bottleTypeManager->GetById($bottle->get_bottleTypeId())->get_volume() ?> oz
											</td>

											<td>
                        <?php echo $beerManager->GetById($bottle->get_beerId())->get_name() ?>
											</td>
											
											<td>
												<?php echo $bottle->get_startAmount() ?>
											</td>
											
											<td>
												<?php echo $bottle->get_currentAmount() ?>
											</td>
											
											<td>
												<input name="editBottle" type="submit" class="btn" value="Update Bottle Info" onclick="window.location='bottle_form.php?id=<?php echo $bottle->get_id()?>'" />
											</td>
											
											<td>
                        <form method="POST">
                          <input type='hidden' name='id' value='<?php echo $bottle->get_id()?>'/>
                          <input name="removeBottle" type="submit" class="removeBottle btn" value="Remove Bottle" />
                        </form>
											</td>
											
										</tr>
								<?php } ?>
							</form>						
					</tbody>
				</table>
				<br />
				<div align="right">			
					&nbsp &nbsp 
				</div>
			
			</form>
    <!-- Set Tap Number Form -->
    <?php $htmlHelper->ShowMessage(); ?>
    <p>
    <input type="submit" name="newBottle" class="btn" value="Add a Bottle" onclick="window.location='bottle_form.php'" />
    </p>
    <!-- End Tap Number Form -->
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
	$(function(){
		$('.removeBottle').on('click', function(){
			if(!confirm('Are you sure you want to delete this bottle?')){
				return false;
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
