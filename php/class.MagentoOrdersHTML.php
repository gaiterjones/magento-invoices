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
 * Magento Orders HTML extends Orders
 * -- Class to return html for displaying orders - used by ajax
 * @access public
 * @return Magento Order HTML
 */
class MagentoOrdersHTML extends MagentoOrders
{

	public function __construct($_variables) {
			
		// load parent
		parent::__construct($_variables);
		
		// define class variables
		$_array=array(
			"timezone"			 		=> $this->__config->get('timezone'),
			"cachefolder"		 		=> $this->__config->get('cachefolder')
		);
		$this->loadClassVariables($_array);
		
		$this->getOrderHTML();

	}
	
	// -- get order html
	private function getOrderHTML()
	{
		$this->set('success',false);
		$this->set('output',false);
		$this->set('errormessage','Not defined');
		
		// -- generate the html
		PageMainData::getOrdersHTML();
		
		// -- ajax return
		$_output=$this->get('orderulhtml');
		
		if (!empty($_output)) {
		
			$this->set('success',true);
			$this->set('output',$_output);
		}
	}	
	
}  
?>