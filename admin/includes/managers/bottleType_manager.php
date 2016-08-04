<?php
require_once __DIR__.'/../models/bottleType.php';

class BottleTypeManager{

  public $link;

  function __construct()
  {
    include __DIR__.'/../conn.php';
    $this->link = $con;
  }

	function GetAll(){
		$sql="SELECT * FROM bottleTypes ORDER BY displayName";
		$qry = mysqli_query($this->link,$sql);
		
		$bottleTypes = array();
		while($i = mysqli_fetch_array($qry)){
			$bottleType = new BottleType();
			$bottleType->setFromArray($i);
			$bottleTypes[$bottleType->get_id()] = $bottleType;		
		}
		
		return $bottleTypes;
	}
	
	
		
	function GetById($id){
		$sql="SELECT * FROM bottleTypes WHERE id = $id";
		$qry = mysqli_query($this->link,$sql);
		
		if( $i = mysqli_fetch_array($qry) ){		
			$bottleType = new BottleType();
			$bottleType->setFromArray($i);
			return $bottleType;
		}

		return null;
	}
}
