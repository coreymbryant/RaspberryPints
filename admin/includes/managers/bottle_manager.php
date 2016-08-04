<?php
require_once __DIR__.'/../models/bottle.php';

class BottleManager{

  public $link;

  function __construct()
  {
    include __DIR__.'/../conn.php';
    $this->link = $con;
  }
	
	function Save($bottle){
		$sql = "";
		
		if($bottle->get_id()){
			$sql = 	"UPDATE bottles " .
					"SET " .
						"bottleTypeId = '" . $bottle->get_bottleTypeId() . "', " .
						"beerId = '" . $bottle->get_beerId() . "', " .
						"capRgba = '" . $bottle->get_capRgba() . "', " .
						"capNumber = " . $bottle->get_capNumber() . ", " .
						"pinId = '" . $bottle->get_pinId() . "', " .
						"startAmount = '" . $bottle->get_startAmount() . "', " .
						"currentAmount = '" . $bottle->get_currentAmount() . "', " .
						"modifiedDate = NOW() ".
            "WHERE id = " . $bottle->get_id();
					
		}else{
      $sql = 	"INSERT INTO bottles(bottleTypeId, beerId, capRgba, capNumber, pinId, 
        startAmount, currentAmount, active, createdDate, modifiedDate ) " .  
        "VALUES(" .
        "'". $bottle->get_bottleTypeId() . "', " .
        "'". $bottle->get_beerId() . "', " .
        "'". $bottle->get_capRgba() . "'," .
        $bottle->get_capNumber() . ", " .
        "'". $bottle->get_pinId() . "', " .
        "'". $bottle->get_startAmount() . "', " .
        "'". $bottle->get_currentAmount() . "', " .
        " '1', NOW(), NOW())";
		}		
		
		//echo $sql; exit();
		
		mysqli_query($this->link,$sql);
	}
	
	function UpdateCounts(){
		$sql= "UPDATE bottleTypes SET used = ( " .
      "SELECT SUM(bottles.startAmount - IFNULL(drank.amountDrank,0)) " .
      "FROM bottles, drank " .
      "WHERE drank.bottleId = bottles.id AND bottles.bottleTypeId = bottleTypes.id " .
      "GROUP BY bottles.bottleTypeId)"; 
		mysqli_query($this->link,$sql);
	}

	function GetById($id){
		$id = (int) preg_replace('/\D/', '', $id);
	
		$sql="SELECT * FROM bottles WHERE id = $id";
		$qry = mysqli_query($this->link,$sql);
		
		if( $i = mysqli_fetch_array($qry) ){
			$bottle = new Bottle();
			$bottle->setFromArray($i);
			return $bottle;
		}
		
		return null;
	}

	function getBottleCount(){
		$sql="SELECT COUNT(*) AS bottleCount FROM bottles WHERE active = '1'";

		$qry = mysqli_query($this->link,$sql);
		$bottle = mysqli_fetch_array($qry);
		
    return $bottle['bottleCount'];
	}


	function GetActiveBottles(){
		$sql="SELECT * FROM bottles WHERE active = 1";
		$qry = mysqli_query($this->link,$sql);
		
		$bottles = array();
		while($i = mysqli_fetch_array($qry)){
			$bottle = new Bottle();
			$bottle->setFromArray($i);
			$bottles[$bottle->get_id()] = $bottle;
		}
		
		return $bottles;
	}
	
	function removeBottle($id){
		$sql="DELTE FROM drank WHERE bottleId = $id";
		mysqli_query($this->link,$sql);
		$sql="UPDATE bottles SET active = 0, modifiedDate = NOW() WHERE id = $id";
		mysqli_query($this->link,$sql);
	}

}
