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
 * -- Class to retrieve customer data from Magento
 * @access public
 * @return Magento Order
 */
class MagentoCustomerExport
{

	protected $__config;
	protected $__;
	
	public function __construct($_variables) {
		
			$this->loadConfig();
			
			// load app translator			
			$_languageCode=$this->__config->get('languagecode');
			if (empty($_languageCode)) { $_languageCode='en';}
			$this->__t=new Translator($_languageCode);	
			
			$this->loadClassVariables($_variables);

			$this->exportCustomer();
			
	}

	
	public function exportCustomer()
	{
		
		$this->set('success',false);
		$this->set('output',false);
		$this->set('errormessage','Could not export customer data.');

		$_customerData=false;
		$_jsonData=stripslashes($this->get('customerdata'));
		$_customerDataArray=json_decode($_jsonData,true);
		
		if (count($_customerDataArray) > 0)
		{
		
			foreach ($_customerDataArray as $_key => $_value)
			{
				// get customer data from json array
				$_customerData=$_customerData.utf8_decode(str_replace(';', ':',$_value)). ';';
			}

			$_customerID=$this->get('customerid');
			
			if ($_customerData && $this->writeData($_customerData,$_customerID)) {
			
				$this->set('success',true);
				$this->set('output','Data Exported');
			}
		} else {
			$this->set('errormessage','No customer data found to export.');
		}
	
	}
	
	// -- export data
	//
	protected function writeData($_data,$_id)
	{
		
		if (is_writable($this->__config->get('cachefolder'))) {
			//file_put_contents($this->__config->get('cachefolder').$_id. '.CustomerData', $_data);
			file_put_contents($this->__config->get('cachefolder'). 'NewCustomerData.txt', $_data);
			return true;
		}
		
		return false;
	}	
	
	// -- get app config
	protected function loadConfig()
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
	
	
	protected function loadClassVariables($_variables)
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