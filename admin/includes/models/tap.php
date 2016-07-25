<?php
class Tap  
{  
	private $_id;  
	private $_beerId;  
	private $_kegId;
	private $_tapNumber;
	private $_pinId;
	private $_startAmount; 
	private $_currentAmount; 
	private $_active;
	private $_createdDate; 
	private $_modifiedDate; 

	public function __construct(){}

	public function get_id(){ return $this->_id; }
	public function set_id($_id){ $this->_id = $_id; }

	public function get_kegId(){ return $this->_kegId; }
	public function set_kegId($_kegId){ $this->_kegId = $_kegId; }

	public function get_tapNumber(){ return $this->_tapNumber; }
	public function set_tapNumber($_tapNumber){ $this->_tapNumber = $_tapNumber; }
	
	public function get_pinId() { return $this->_pinId; }
	public function set_pinId($_pinId){ $this->_pinId = $_pinId; }
	
	public function get_startAmount(){ return $this->_startAmount; }
	public function set_startAmount($_startAmount){ $this->_startAmount = $_startAmount; }
	
	public function get_currentAmount(){ return $this->_currentAmount; }
	public function set_currentAmount($_currentAmount){ $this->_currentAmount = $_currentAmount; }
	
	public function get_active(){ return $this->_active; }
	public function set_active($_active){ $this->_active = $_active; }
	
	public function get_createdDate(){ return $this->_createdDate; }
	public function set_createdDate($_createdDate){ $this->_createdDate = $_createdDate; }
	
	public function get_modifiedDate(){ return $this->_modifiedDate; }
	public function set_modifiedDate($_modifiedDate){ $this->_modifiedDate = $_modifiedDate; }
	
	public function setFromArray($postArr)  
	{  	
		if( isset($postArr['id']) )
			$this->set_id($postArr['id']);
		else
			$this->set_id(null);
			
		if( isset($postArr['kegId']) )
			$this->set_kegId($postArr['kegId']);
		else
			$this->set_kegId(null);
			
		if( isset($postArr['tapNumber']) )
			$this->set_tapNumber($postArr['tapNumber']);
		else
			$this->set_tapNumber(null);
			
		if( isset($postArr['pinId']) )
			$this->set_pinId($postArr['pinId']);
		else
			$this->set_pinId('0');
			
		if( isset($postArr['startAmount']) )
			$this->set_startAmount($postArr['startAmount']);
		else
			$this->set_startAmount(null);
				
		if( isset($postArr['currentAmount']) )
			$this->set_currentAmount($postArr['currentAmount']);
		else
			$this->set_currentAmount(null);
		
		if( isset($postArr['active']) )
			$this->set_active($postArr['active']);
		else
			$this->set_active(false);
		
		if( isset($postArr['createdDate']) )
			$this->set_createdDate($postArr['createdDate']);
		else
			$this->set_createdDate(null);
			
		if( isset($postArr['modifiedDate']) )
			$this->set_modifiedDate($postArr['modifiedDate']);
		else
			$this->set_modifiedDate(null);
	}  
}
