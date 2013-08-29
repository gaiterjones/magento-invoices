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

 
    Update a magento product attribute - can be used with ajax class:
 *  http://x/index.php?ajax=true&class=MagentoProductAttributeSave&variables=productid=3638|productattribute=ext_amazonprice|attributevalue=999.99
 */

/**
 * MAGE
 * -- Saves mage data
 * @access public
 * @return nix
 */ 
class MagentoProductAttributeSave extends Magento {

	public function __construct($_variables) {

		parent::__construct();
		
		$this->loadClassVariables($_variables);
		
		$this->productAttributeSave();


	}
	
	function productAttributeSave()
	{
		$this->set('success',false);
		$this->set('errormessage','Not defined');
		
		$_id=$this->get('productid');
		$_attribute=$this->get('productattribute');
		$_value=$this->get('attributevalue');
		$_attributeType=$this->get('attributetype');
		
		$_storeId = 0; // default store - all views
		
		if ($_attributeType==='price') {
			$_value=str_replace(',','.',$_value);
		}
		
		if (!is_numeric($_value) && $_attributeType==='price') {
			throw new exception ('Invalid value for '. $_attributeType. ' : '. $_value. '.');
		}
		
		// load product
		$_product = Mage::getModel('catalog/product')->setStoreId($_storeId)->load($_id);
		
		if ($_attribute==='ext_ean') // save attribute in parent NOT child
		{
			// for grouped products load the parent - save the attibute in the parent
			$parentIdGrouped = $_product->loadParentProductIds()->getData('parent_product_ids');
			// for configurable products load the parent - save the attibute in the parent
			$parentIdConfigurable = $_product->loadParentProductIds()->getData('parent_product_ids');	
			
			if (!empty($parentIdGrouped[0])) // check for grouped product parent
			{
				$_product = Mage::getModel('catalog/product')->setStoreId($_storeID)->load($parentIdGrouped[0]);
			
			} else if (!empty($parentIdConfigurable[0])) { // check for configurable product parent

				$_product = Mage::getModel('catalog/product')->setStoreId($_storeID)->load($parentIdConfigurable[0]);
			}		
		}
		
		// save attribute data
		$_product->setData($_attribute, $_value)->getResource()->saveAttribute($_product, $_attribute);

		$_message="Attribute - ". $_attribute. " for product id:". $_id. " saved.";
		
		$_output=array(
			"output"						=>	$_message,
			"value"							=>  $_value
		);
		
		$this->set('success',true);
		$this->set('output',$_output);

	}
	
	private function loadClassVariables($_variables)
	{
		foreach ($_variables as $_variableName=>$_variableData)
		{
			// check for optional data
			if (substr($_variableName, -8) === 'optional') { continue; }
			
			$_variableData=trim($_variableData);
			if (empty($_variableData)) {
				//throw new exception('Class variable '.$_variableName. ' cannot be empty.');
			}
			
			$this->set($_variableName,$_variableData);
						
		}
	}		

}
?>