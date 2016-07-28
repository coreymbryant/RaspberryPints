<?php 
require_once __DIR__.'/conn.php';
$ok=1; 

//This is our size condition 
if ($_FILES['uploaded']['size']> 10000) 
{ 
  echo "Your file is too large.<br>"; 
  $ok=0; 
} 

//This is our limit file type condition 
if ($_FILES['uploaded']['type']!="text/xml") 
{ 
  echo "Invalid file type<br>"; 
  $ok=0; 
} 

//Here we check that $ok was not set to 0 by an error 
if ($ok==0) 
{ 
  echo "Sorry your file was not uploaded"; 
} 

//If everything is ok we try to upload it 
else 
{ 

    $xml=simplexml_load_file($_FILES['uploaded']['tmp_name']);

    $catNum = $xml->RECIPE[0]->STYLE->CATEGORY_NUMBER . $xml->RECIPE[0]->STYLE->STYLE_LETTER;
    $styleName = $xml->RECIPE[0]->STYLE->NAME;
    $sql = "SELECT id from beerStyles where name='" . $xml->RECIPE[0]->STYLE->NAME . "' and catNum='" . $catNum . "';";

    $qry = mysqli_query($con,$sql);
    $styleId = mysqli_fetch_assoc($qry)['id'];
    if ($styleId == '')
    {
      $sql = "SELECT id from beerStyles where name='" . $xml->RECIPE[0]->STYLE->NAME . "';";
      $qry = mysqli_query($con,$sql);
      $styleId = mysqli_fetch_assoc($qry)['id'];
    }

    if ($styleId == '')
    {
      echo "Error - file not uploaded: Beer Style '" . $styleId . "' not found. <br>";
      echo 'Return to <a href="../beer_list.php">Beer List</a><br />';
    }
    else
    {
      $sql = "";
      $sql = "INSERT INTO beers(name, beerStyleId, ogEst, fgEst, srmEst, ibuEst, createdDate, modifiedDate ) " .
          "VALUES(" . 
          "'" . $xml->RECIPE[0]->NAME . "', " .
          $styleId . ", " .
          "'" . $xml->RECIPE[0]->OG . "', " . 
          "'" . $xml->RECIPE[0]->FG . "', " . 
          "'" . $xml->RECIPE[0]->EST_COLOR . "', " . 
          "'" . $xml->RECIPE[0]->IBU . "' " .
          ", NOW(), NOW()); " ;

      /* echo $sql .'<br>'; */
      $qry = mysqli_query($con,$sql);

      $beerId = mysqli_fetch_row(mysqli_query($con,'select LAST_INSERT_ID()'))[0];
      /* echo $beerId . '<br>'; */

      if ($beerId == '')
      {
        echo "Error - file not uploaded: Beer not found after insert. <br>";
        echo 'Return to <a href="beer_list.php">Beer List</a><br />';
      }
      else
      {
        $fermentables = array();
        foreach ($xml->RECIPE->FERMENTABLES->FERMENTABLE as $fermentable) 
        {
          $fermentables["'" . $fermentable->NAME . "'"] = $fermentable->AMOUNT;
        }

        arsort($fermentables, SORT_NUMERIC);

        foreach ($fermentables as $fermentable => $val) 
        {
          $sql = "";
          $sql = "INSERT INTO fermentables(name, beerId, createdDate, modifiedDate) " .
              "VALUES(" . 
              $fermentable . ", " .
              $beerId . 
              ", NOW(), NOW());" ;
           /* echo $sql . "<br>"; */
          $qry = mysqli_query($con,$sql);
        }

        $hops = array();
        foreach ($xml->RECIPE->HOPS->HOP as $hop) 
        {
          $hops[] = $hop->NAME;
        }

        $hops = array_unique($hops);
        foreach($hops as $hop)
        {
          $sql = "";
          $sql = "INSERT INTO hops(name, beerId, createdDate, modifiedDate) " .
              "VALUES(" . 
              "'" . $hop . "', " .
              $beerId . 
              ", NOW(), NOW());" ;
           /* echo $sql . "<br>"; */
          $qry = mysqli_query($con,$sql);
        }

        foreach ($xml->RECIPE->YEASTS->YEAST as $yeast) 
        {
          $sql = "";
          $sql = "INSERT INTO yeasts(name, beerId, createdDate, modifiedDate) " .
              "VALUES(" . 
              "'" . $yeast->NAME . "', " .
              $beerId . 
              ", NOW(), NOW());" ;
           /* echo $sql . "<br>"; */
          $qry = mysqli_query($con,$sql);
        }
      }

      echo "<script>location.href='../beer_list.php';</script>";
    }



  
} 
?> 
