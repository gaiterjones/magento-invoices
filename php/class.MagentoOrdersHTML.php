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
 * -- Class to return html for displaying orders - used by ajax
 * @access public
 * @return Magento Order HTML
 */
class MagentoOrdersHTML
{

	protected $__config;
	protected $__;
	protected $__t;
	
	public function __construct($_variables) {
		
			$this->loadConfig();
			$this->loadClassVariables($_variables);
	
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
	
	
	// -- get app config
	private function loadConfig()
	{
		$this->__config= new config();
		
		// load app translator			
		$_languageCode=$this->__config->get('languagecode');
		if (empty($_languageCode)) { $_languageCode='en';}
		$this->__t=new Translator($_languageCode);	
		
		$this->set('cachefolder',$this->__config->get('cachefolder'));
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