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
			
			parent::__construct($_variables);

			$this->getOrderHTML();
	}

	public function getOrderHTML()
	{
		
		$this->set('success',false);
		$this->set('output',false);
		$this->set('errormessage','Not defined');
		
		PageMainData::getOrdersHTML();
		
		$_output=$this->get('orderulhtml');
		
		if (!empty($_output)) {
		
			$this->set('success',true);
			$this->set('output',$_output);
		}
	
	}
	
}  
?>