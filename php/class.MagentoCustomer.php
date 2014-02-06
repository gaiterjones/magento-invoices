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
class MagentoCustomer
{

	protected $__config;
	protected $__;
	public $__t;
	
	public function __construct($_variables) {
		
			$this->loadConfig();
			
			// load app translator			
			$_languageCode=$this->__config->get('languagecode');
			if (empty($_languageCode)) { $_languageCode='en';}
			$this->__t=new Translator($_languageCode);	
			
			$this->loadClassVariables($_variables);

			$this->getCustomer();
			
	}

	
	public function getCustomer()
	{
		
		$this->set('success',false);
		$this->set('output',false);
		$this->set('errormessage','Error retrieving data or no data found.');
		
		$_orderID=false;
		$_timeRange=false;
		$_collection=false;
		
		$_storeID=$this->get('storeid');
		$_emailAddress=$this->get('emailaddress');
		
		if ($_emailAddress) {$this->getMagentoCustomerByEmail($_emailAddress,$_storeID);}

		$_collection=$this->get('collection');
		$_addressData=$this->get('addressdata');
		
		if ($_collection && $_addressData) {
		
			$_output=array(
				'prefix' => $_collection->getPrefix(),
				'firstname' => $_collection->getFirstname(),
				'lastname' => $_collection->getLastname(),
				'suffix' => $_collection->getSuffix(),
				'email' => $_collection->getEmail(),
				'street' => $_addressData['street'],
				'city' => $_addressData['city'],				
				'country' => $this->get('customercountry'),				
				'countryid' => $_addressData['country_id'],
				'postcode' => $_addressData['postcode'],
				'telephone' => $_addressData['telephone'],
				'company' => $_addressData['company']
			);
			
			$this->set('success',true);
			$this->set('output',array('customerid' => $_collection->getEntity_id(), 'addressdata' => $_output));
		} else {
		
			$this->set('errormessage','Customer is not registered - no data found.');
		}
	
	}
	
	private function getMagentoCustomerByEmail($_emailAddress,$_storeID)
	{

		$_customerCountry=null;
		
		$_obj=new MagentoCollection();
			$_obj->getCustomerByEmailAddress($_emailAddress,$_storeID);

		$_collection=$_obj->get('collection');
		$_addressData=$_obj->get('addressdata');
		
		if ($_addressData['country_id']) { $_customerCountry=$_obj->getCustomerCountry($_addressData['country_id']); }
		
			unset($_obj);
			
		$this->set('collection',$_collection);
		$this->set('addressdata',$_addressData);
		$this->set('customercountry',$_customerCountry);
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