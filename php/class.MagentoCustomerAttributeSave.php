<?php
/**
 *  
 *  Copyright (C) 2012 paj@gaiterjones.com
 *
 *	This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  @category   PAJ
 *  @package    
 *  @license    http://www.gnu.org/licenses/ GNU General Public License
 * 	
 
    Update a magento product attribute - can be used with ajax class:
 *  http://shop.vw-e.de/products/index.php?ajax=true&class=MagentoProductAttributeSave&variables=productid=3638|productattribute=ext_amazonprice|attributevalue=999.99
 */

 
class MagentoCustomerAttributeSave extends Magento {

	public function __construct($_variables) {

		parent::__construct();
		
		$this->loadClassVariables($_variables);
		
		$this->customerAttributeSave();


	}
	
	function customerAttributeSave()
	{
		$this->set('success',false);
		$_output=false;
		$this->set('errormessage','Not defined');
		
		$_id=$this->get('customerid');
		$_attribute=$this->get('customerattribute');
				
		$_supportedAttributes = array('customer_activated','group_id','suffix','firstname','lastname','street','postcode');
		
		if (in_array($_attribute, $_supportedAttributes)) {
			
			$_value=$this->get('attributevalue');
			$_storeId = 0; // default store - all views
			
			// load customer
			$_customer = Mage::getModel('customer/customer')->load($_id);
			// set attribute
			
			if ($_attribute==="customer_activated")	{ $_customer->customer_activated = $_value; }
			if ($_attribute==="group_id")	{ $_customer->group_id = $_value; }
			if ($_attribute==="suffix")	{ $_customer->suffix = $_value; }
			if ($_attribute==="firstname")	{ $_customer->firstname = $_value; }
			if ($_attribute==="lastname")	{ $_customer->lastname = $_value; }
			if ($_attribute==="street")	{ $_customer->street = $_value; }
			if ($_attribute==="postcode")	{ $_customer->postcode = $_value; }
			
			// save customer
			if($_customer->save()){
			
				$_message='Customer attribute - '. $_attribute. ' for id : '. $_id. ' changed to "'. $_value. '".';
			
				$_output=array(
					"output"						=>	$_message,
					"value"							=>  $_value
				);
				
				$this->set('success',true);
			}
			
		} else {
			$this->set('errormessage','The attribute "'. $_attribute. '" cannot be changed.');
		}
		
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