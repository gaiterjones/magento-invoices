<?php
/**
 *  
 *  Copyright (C) 2013
 *
 *
 *  @who	   	PAJ
 *  @info   	paj@gaiterjones.com
 *  @license    blog.gaiterjones.com
 * 	
 *
 */
 
 
/**
 * Magento Orders
 * -- Class to retrieve order data from Magento
 * @access public
 * @return Magento Order
 */
class MagentoOrders
{

	protected $__config;
	protected $__;
	
	public function __construct($_variables) {
		
			$this->loadConfig();
			$this->loadClassVariables($_variables);			
			
			$this->getOrders();
	}

	
	public function getOrders()
	{
		
		$this->set('success',false);
		$this->set('output',false);
		$this->set('errormessage','Not defined');
		
		$_orderID=false;
		$_timeRange=false;
		$_collection=false;
		
		$_storeID=$this->get('storeid');
		$_orderID=$this->get('orderid');
		$_timeRange=$this->get('timerange');
		
		if ($_orderID) {$this->getMagentoOrderByID($_orderID,$_storeID);}
		
		if ($_timeRange) {$this->getMagentoOrderByDate($_timeRange,$_storeID);}

		$_collection=$this->get('collection');
		
		if ($_collection) {
		
			$this->set('success',true);
			$this->set('output',$_collection);
		}
	
	}
	
	private function getMagentoOrderByDate($_timeRange,$_storeID)
	{

		$_obj=new MagentoCollection();
			$_obj->getOrdersByDate($_timeRange,$_storeID);
				

		$_collection=$_obj->get('collection');
			unset($_obj);
			
		$this->set('collection',$_collection);
	}
	
	private function getMagentoOrderByID($_orderID,$_storeID)
	{
		
		$_obj=new MagentoCollection();
			$_obj->getOrdersByOrderID($_orderID,$_storeID);
				

		$_collection=$_obj->get('collection');
			unset($_obj);
			
		$this->set('collection',$_collection);
	}	
	
	// -- get app config
	private function loadConfig()
	{
		$this->__config= new config();
	}

	public function set($key,$value)
	{
		$this->__[$key] = $value;
	}

	public function get($variable)
	{
		return $this->__[$variable];
	}
	
	
	private function loadClassVariables($_variables)
	{
		foreach ($_variables as $_variableName=>$_variableData)
		{
			// check for optional data
			if (substr($_variableName, -8) === 'optional') { continue; }
			
			$_variableData=trim($_variableData);
			if (empty($_variableData) && $_variableData !='0') {
				throw new exception('Class variable '.$_variableName. ' cannot be empty.');
			}
			
			$this->set($_variableName,$_variableData);
						
		}
	}	

}  
?>