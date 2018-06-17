<?php
	if (!file_exists(__DIR__.'/includes/config.php')) {
		header('Location: install/index.php', true, 303);
		die();
	}
?>
<?php
	require_once __DIR__.'/includes/config_names.php';

	require_once __DIR__.'/includes/config.php';

	require_once __DIR__.'/admin/includes/managers/tap_manager.php';

	require_once __DIR__.'/admin/includes/managers/bottle_manager.php';
	
	//This can be used to choose between CSV or MYSQL DB
	$db = true;
	
	// Setup array for all the beers that will be contained in the list
	$taps = array();
	$bottles = array();
	
	if($db){
		// Connect to the database
		db();
		
		
		$config = array();
		$sql = "SELECT * FROM config";
		$qry = mysqli_query($link,$sql);
		while($c = mysqli_fetch_array($qry)){
			$config[$c['configName']] = $c['configValue'];
		}
		
		$sql =  "SELECT * FROM vwGetActiveTaps";
		$qry = mysqli_query($link,$sql);
		while($b = mysqli_fetch_array($qry))
		{
			$beeritem = array(
				"id" => $b['id'],
				"beername" => $b['name'],
				"style" => $b['style'],
				"notes" => $b['notes'],
				"og" => $b['ogEst'],
				"fg" => $b['fgEst'],
				"srm" => $b['srmEst'],
				"ibu" => $b['ibuEst'],
				"startAmount" => $b['startAmount'],
				"amountPoured" => $b['amountPoured'],
				"remainAmount" => $b['remainAmount'],
				"tapNumber" => $b['tapNumber'],
				"srmRgb" => $b['srmRgb']
			);
			$taps[$b['tapNumber']] = $beeritem;
		}
		
		$tapManager = new TapManager();
		$numberOfTaps = $tapManager->GetTapNumber();

		$sql =  "SELECT * FROM vwGetFilledBottles";
		$qry = mysqli_query($link,$sql);
    $rowNumber = 1;
		while($b = mysqli_fetch_array($qry))
		{
			$beeritem = array(
				"id" => $b['id'],
				"beername" => $b['name'],
				"style" => $b['style'],
				"notes" => $b['notes'],
				"og" => $b['ogEst'],
				"fg" => $b['fgEst'],
				"srm" => $b['srmEst'],
				"ibu" => $b['ibuEst'],
				"volume" => $b['volume'],
				"startAmount" => $b['startAmount'],
				"remainAmount" => $b['remainAmount'],
				"capRgba" => $b['capRgba'],
				"capNumber" => $b['capNumber'],
				"srmRgb" => $b['srmRgb']
			);
			$bottles[$rowNumber] = $beeritem;
      $rowNumber = $rowNumber+1;
		}
		$bottleManager = new BottleManager();
    $bottleManager->UpdateCounts();
		$numberOfBottles = $bottleManager->getBottleCount();
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">

<html>
	<head>
    <title><?php echo $config[ConfigNames::PageTitle] ; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<!-- Set location of Cascading Style Sheet -->
		<link rel="stylesheet" type="text/css" href="style.css">
		
		<?php if($config[ConfigNames::UseHighResolution]) { ?>
			<link rel="stylesheet" type="text/css" href="high-res.css">
		<?php } ?>
		
		<link rel="shortcut icon" href="favicon.ico">
	</head> 

	<body>
		<div class="bodywrapper">
			<!-- Header with Brewery Logo and Project Name -->
			<div class="header clearfix">
				<div class="HeaderLeft">
				</div>
				<div class="HeaderCenter">
					<h1 id="HeaderTitle">
					<?php if($config[ConfigNames::UseHighResolution]) { ?>			
						<a href="admin/admin.php"><img src="<?php echo $config[ConfigNames::LogoUrl] . "?" . time(); ?>" height="200" alt=""></a>
					<?php } else { ?>
						<a href="admin/admin.php"><img src="<?php echo $config[ConfigNames::LogoUrl] . "?" . time(); ?>" height="100" alt=""></a>
					<?php } ?>
					</h1>
				</div>
				<div class="HeaderRight">
				</div>
			</div>
			<!-- End Header Bar -->
			
      <br>
      <br>
      <h1>
        <p style="text-align:center">  
          <?php
            if (mb_strlen($config[ConfigNames::HeaderText], 'UTF-8') > ($config[ConfigNames::HeaderTextTruncLen])) {
              $headerTextTrunced = substr($config[ConfigNames::HeaderText],0,$config[ConfigNames::HeaderTextTruncLen]) . "...";
              echo $headerTextTrunced ; }
            else
              echo $config[ConfigNames::HeaderText];
          ?>
        </p>
      </h1>
      <br>
			
      <div STYLE=" height: 100%; width: 100%; overflow: auto;">
			<table>
        <?php if($config[ConfigNames::ShowTableHeadings]){ ?>
				<thead>
					<tr>
						<?php if($config[ConfigNames::ShowTapNumCol]){ ?>
							<th class="tap-num">
								TAP<br>#
							</th>
						<?php } ?>
						
						<?php if($config[ConfigNames::ShowSrmCol]){ ?>
							<th class="srm">
								&nbsp;<hr>COLOR
							</th>
						<?php } ?>
						
						<th class="name">
							BEER NAME &nbsp; & &nbsp; STYLE<hr>TASTING NOTES
						</th>
						
						
						<?php if($config[ConfigNames::ShowKegCol]){ ?>
							<th class="keg">
								STARTING<hr>REMAINING
							</th>
						<?php } ?>
					</tr>
				</thead>
        <?php } ?>

				<tbody>
					<?php for($i = 1; $i <= $numberOfTaps; $i++) {
						if( isset($taps[$i]) ) {
							$beer = $taps[$i];
					?>
							<tr class="<?php if($i%2 > 0){ echo 'altrow'; }?>" id="<?php echo $beer['id']; ?>">
								<?php if($config[ConfigNames::ShowTapNumCol]){ ?>
									<td class="tap-num">
										<a href ="./includes/drank.php/?tapId=<?php echo $beer['id']; ?>"><span class="tapcircle"><?php echo $i; ?></span>
									</td>
								<?php } ?>
							
								<?php if($config[ConfigNames::ShowSrmCol]){ ?>
									<td class="srm">
										
										<div class="srm-container">
											<div class="srm-indicator" style="background-color: rgb(<?php echo $beer['srmRgb'] != "" ? $beer['srmRgb'] : "0,0,0" ?>)"></div>
											<div class="srm-stroke"></div> 
										</div>
										
									</td>
								<?php } ?>
							
								<td class="name">
									<h1><?php echo $beer['beername']; ?></h1>
                                    <br/>
                                    <a class="btn" href ="./includes/drank.php/?tapId=<?php echo $beer['id']; ?>&amount=.03125"><span>Sample</span></a>
                                    &nbsp; &nbsp; &nbsp;
                                    <a class="btn" href ="./includes/drank.php/?tapId=<?php echo $beer['id']; ?>&amount=.125"><span>Pint</span></a>
                                    &nbsp; &nbsp; &nbsp;
                                    <a class="btn" href ="./includes/drank.php/?tapId=<?php echo $beer['id']; ?>&amount=.5"><span>Growler</span></a>
								</td>
							
								
								
								<?php if($config[ConfigNames::ShowKegCol]){ ?>
									<td class="keg">
										
										
										<h3><?php echo number_format((($beer['remainAmount']) )); ?> gal</h3>
										<?php 
											// Code for new kegs that are not full
                                                                                        $tid = $beer['id'];
                                                                                        $sql = "Select kegId from taps where id=".$tid." limit 1";
                                                                                        $kegID = mysqli_query($link,$sql);
                                                                                        $kegID = mysqli_fetch_array($kegID);
                                                                                        //echo $kegID[0];
                                                                                        $sql = "SELECT `kegTypes`.`maxAmount` as kVolume FROM  `kegs`,`kegTypes` where  kegs.kegTypeId = kegTypes.id and kegs.id =".$kegID[0]."";
                                                                                        $kvol = mysqli_query($link,$sql);
                                                                                        $kvol = mysqli_fetch_array($kvol);
                                                                                        $kvol = $kvol[0];
                                                                                        $kegImgClass = "";
                                                                                        if ($beer['startAmount']>=$kvol) {
                                                                                        $percentRemaining = $beer['remainAmount'] / $beer['startAmount'] * 100;
                                                                                        } else {
                                                                                        $percentRemaining =  $beer['remainAmount'] / $kvol * 100;
                                                                                        }
											if( $beer['remainAmount'] <= 0 ) {
												$kegImgClass = "keg-empty";
												$percentRemaining = 100; }
											else if( $percentRemaining < 15 )
												$kegImgClass = "keg-red";
											else if( $percentRemaining < 25 )
												$kegImgClass = "keg-orange";
											else if( $percentRemaining < 45 )
												$kegImgClass = "keg-yellow";
											else if ( $percentRemaining < 100 )
												$kegImgClass = "keg-green";
											else if( $percentRemaining >= 100 )
												$kegImgClass = "keg-full";
										?>
										<div class="keg-container">
											<div class="keg-indicator"><div class="keg-full <?php echo $kegImgClass ?>" style="height:<?php echo $percentRemaining; ?>%"></div></div>
										</div>
										<h3><?php echo number_format(($beer['remainAmount'] * 128)); ?> fl oz</h3>
									</td>
								<?php } ?>
							</tr>
						<?php }else{ ?>
							<tr class="<?php if($i%2 > 0){ echo 'altrow'; }?>">
								<?php if($config[ConfigNames::ShowTapNumCol]){ ?>
									<td class="tap-num">
										<span class="tapcircle"><?php echo $i; ?></span>
									</td>
								<?php } ?>
							
								<?php if($config[ConfigNames::ShowSrmCol]){ ?>
									<td class="srm">
										<h3></h3>										
										<div class="srm-container">
											<div class="srm-indicator"></div>
											<div class="srm-stroke"></div> 
										</div>
										
										<h2></h2>
									</td>
								<?php } ?>
							
								<td class="name">
									<h1>Nothing on tap</h1>
									<h2 class="subhead"></h2>
									<p></p>
								</td>
							

								<?php if($config[ConfigNames::ShowKegCol]){ ?>
									<td class="keg">
										<h3><?php echo "&nbsp;"; ?></h3>
										<div class="keg-container">
											<div class="keg-indicator"><div class="keg-full keg-empty" style="height:0%"></div></div>
										</div>
										<h3><?php echo "&nbsp;"; ?></h3>
									</td>
								<?php } ?>

							</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
			</table>

      <br>
      <h1>
        <p style="text-align:center">  
          <?php
                      if ($numberOfBottles>0){
            if (mb_strlen($config[ConfigNames::BottleHeaderText], 'UTF-8') > ($config[ConfigNames::BottleHeaderTextTruncLen])) {
              $bottleHeaderTextTrunced = substr($config[ConfigNames::BottleHeaderText],0,$config[ConfigNames::BottleHeaderTextTruncLen]) . "...";
              echo $bottleHeaderTextTrunced ; }
            else
              echo $config[ConfigNames::BottleHeaderText];
                      }
          ?>
        </p>
      </h1>
      <br>
			
      <div STYLE=" height: 100%; width: 100%; overflow: auto;">
			<table>
        <?php if($config[ConfigNames::ShowTableHeadings]){ ?>
          <thead>
            <tr>
              <?php if($config[ConfigNames::ShowTapNumCol]){ ?>
                <th class="tap-num">
                  CAP<br>#
                </th>
              <?php } ?>
              
              <?php if($config[ConfigNames::ShowSrmCol]){ ?>
                <th class="srm">
                  &nbsp;<hr>COLOR
                </th>
              <?php } ?>
              
              <th class="name">
                BEER NAME &nbsp; & &nbsp; STYLE<hr>TASTING NOTES
              </th>
              
              <?php if( ($config[ConfigNames::ShowAbvCol]) | ($config[ConfigNames::ShowIbuCol]) ){ ?>
                <th class="abv">
                  <?php if($config[ConfigNames::ShowAbvCol]){echo "ALCOHOL";} else {echo "&nbsp;";} ?>
                  <hr>
                  <?php if($config[ConfigNames::ShowIbuCol]){echo "BITTERNESS";} else {echo "&nbsp;";} ?>
                </th>
              <?php } ?>
              
              <?php if($config[ConfigNames::ShowKegCol]){ ?>
                <th class="keg">
                  VOLUME<hr>REMAINING
                </th>
              <?php } ?>
            </tr>
          </thead>
        <?php } ?>
				<tbody>
					<?php for($i = 1; $i <= $numberOfBottles; $i++) {
						if( isset($bottles[$i]) ) {
							$beer = $bottles[$i];
					?>
							<tr class="<?php if($i%2 > 0){ echo 'altrow'; }?>" id="<?php echo $beer['id']; ?>">
								<?php if($config[ConfigNames::ShowTapNumCol]){ ?>
									<td class="tap-num">
                    <a href ="./includes/drank.php/?bottleId=<?php echo $beer['id']; ?>">
                    <?php if ($beer['capNumber'] != 0 ){ ?>
                      <span class="bottlecircle" style="background-color: rgba(<?php echo $beer['capRgba'] ?>)">
                      <?php echo $beer['capNumber'] ?>
                      </span>
                    <?php }else{ ?>
                      <span class="bottlecircle" style="background-color: rgba(<?php echo $beer['capRgba'] ?>)">&nbsp</span>
                    <?php } ?>
                    </a>
									</td>
								<?php } ?>
							
								<?php if($config[ConfigNames::ShowSrmCol]){ ?>
									<td class="srm">
										
										<div class="srm-container">
											<div class="srm-indicator" style="background-color: rgb(<?php echo $beer['srmRgb'] != "" ? $beer['srmRgb'] : "0,0,0" ?>)"></div>
											<div class="srm-stroke"></div> 
										</div>
										
									</td>
								<?php } ?>

								<td class="name">
									<h1><?php echo $beer['beername']; ?></h1>
                                    <br/>
                                    <a class="btn" href="./includes/drank.php/?bottleId=<?php echo $beer['id']; ?>"><span>Bottle</span></a>
								</td>
							
								
								
								<?php if($config[ConfigNames::ShowKegCol]){ ?>
									<td class="keg">
										
										<h3><?php echo number_format(($beer['volume'])); ?> oz</h3> 
										<?php 
                      $kegImgClass = "";
                      $percentRemaining = $beer['remainAmount'] / $beer['startAmount'] * 100;
											if( $beer['remainAmount'] <= 0 ) {
												$kegImgClass = "bottle-empty";
												$percentRemaining = 100; }
											else if( $percentRemaining < 15 )
												$kegImgClass = "bottle-red";
											else if( $percentRemaining < 25 )
												$kegImgClass = "bottle-orange";
											else if( $percentRemaining < 45 )
												$kegImgClass = "bottle-yellow";
											else if ( $percentRemaining < 100 )
												$kegImgClass = "bottle-green";
											else if( $percentRemaining >= 100 )
												$kegImgClass = "bottle-full";
										?>
										<div class="bottle-container">
											<div class="bottle-indicator"><div class="bottle-full <?php echo $kegImgClass ?>" style="height:<?php echo $percentRemaining; ?>%"></div></div>
										</div>
										<h3><?php echo number_format(($beer['remainAmount'])); ?> bottles</h3>
									</td>
								<?php } ?>

              </tr>
							<tr class="<?php if($i%2 > 0){ echo 'altrow'; }?>" id="<?php echo $beer['id']; ?>">
							
              </tr>
            <?php } ?>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</body>
</html>
