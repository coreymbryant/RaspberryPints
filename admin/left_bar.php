<!-- Left Dark Bar Start -->
<div id="leftside">

<!-- Start User Echo -->
<div id="welcome"> &nbsp; Logged in as: <br />
	&nbsp;
	<?php
    require_once 'includes/functions.php';
		$sql="SELECT `name` FROM `users` WHERE username='$_SESSION[myusername]'";
		$result=mysqli_query($con,$sql);
		echo mysqli_result($result, 0, 'name');
	?>
</div>

<!-- End User Echo -->
<div class="user">
	<a href="../index.php"><img src="img/logo.png<?php echo "?" . time(); ?>" width="120" height="120" class="hoverimg" alt="Brewery Logo" /></a>
</div>

<!-- Start Navagation -->
<ul id="nav">
	<li>
		<ul class="navigation">
			<li class="heading selected">Welcome</li>
			<li><a href="../serving.php">Serving</a></li>
		</ul>
	<li>
	<li>
		<ul class="navigation">
            <li class="heading selected">Basic Setup</li>
			<li><a href="beer_list.php">My Beers</a></li>
			<li><a href="keg_list.php">My Kegs</a></li>
			<li><a href="tap_list.php">My Taps</a></li>
			<li><a href="bottle_list.php">My Bottles</a></li>
		</ul>
	</li>
		<li>
		<ul class="navigation">
            <li class="heading selected">Personalization</li>
			<li><a href="personalize.php#columns">Show/Hide Columns</a></li>
			<li><a href="personalize.php#header">Headers</a></li>
			<li><a href="personalize.php#logo">Brewery Logo</a></li>
			<li><a href="personalize.php#background">Background Image</a></li>
		</ul>
	</li>
</ul>

<!-- End Navagation -->
<!-- Left Dark Bar End --> 
