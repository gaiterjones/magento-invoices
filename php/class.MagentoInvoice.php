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
 * -- Class to render an Invoice using data from Magento
 * @access public
 * @return Magento Invoice
 */
class MagentoInvoice
{

	public $__config;
	public $__;
	public $__t;
	
	public function __construct($_variables) {
		
			$this->loadConfig();
			
			$this->loadClassVariables($_variables);
			
			$_addVAT=$this->get('addvat');
			if ($_addVAT==='TRUE') { $this->set('addvat',true); }
			if ($_addVAT==='FALSE') { $this->set('addvat',false); }
			
			$_invoicePrinted=$this->get('print');
			if ($_invoicePrinted==='TRUE') { $this->set('print',true); }
			if ($_invoicePrinted==='FALSE') { $this->set('print',false); }			
			
			
			$this->getInvoice();
	}
	
	private function getInvoice()
	{
		$this->set('success',false);
		$this->set('output',false);
		$this->set('errormessage','Not defined');
		
		$_storeID=$this->get('storeid');
		$_orderID=$this->get('orderid');
		
		$_obj=new MagentoOrders(array(
								'orderid' => $_orderID,
								'storeid' => $_storeID
								));
		$_success=$_obj->get('success');
		
		if($_success) {
			
			$_invoicePrinted=$this->get('print');
			
			if ($_invoicePrinted) { $this->ackOrder($_orderID); }
			
			$_order=$_obj->get('output');

			$this->set('order',$_order);
			
			$_pageHeader=MagentoInvoiceData::pageHeader();
			$_invoiceHeaderTable=MagentoInvoiceData::invoiceHeaderTable();
			$_invoiceHeader=MagentoInvoiceData::invoiceHeader();
			$_invoiceFooter=MagentoInvoiceData::invoiceFooter();
			$_invoiceItemsTable=MagentoInvoiceData::invoiceItemsTable();
			
			$this->set('success',true);
			$this->set('output',array(
				'pageheader'				=> $_pageHeader,
				'invoiceheader'				=> $_invoiceHeader,
				'invoiceheadertable'		=> $_invoiceHeaderTable,
				'invoiceitemstable'			=> $_invoiceItemsTable,
				'invoicefooter'				=> $_invoiceFooter
				));
		}
		
		
		
		unset($_obj);
		
	}
	
	// -- order ack by creating ack file in cache folder
	//
	protected function ackOrder($_orderID)
	{
		
		if (is_writable($this->__config->get('cachefolder'))) {
			file_put_contents($this->__config->get('cachefolder').md5($_orderID). 'MageInvoice', $_orderID);
			return true;
		}
		
		return false;
	}	
	
	// get app config
	private function loadConfig()
	{
		$this->__config= new config();
		
		// load app translator	
		$_languageCode=$this->__config->get('languagecode');
		if (empty($_languageCode)) { $_languageCode='en';}
		$this->__t=new Translator($_languageCode);
		
		$this->set('languagecode',$_languageCode);
		$this->set('watermarkurl',$this->__config->get('watermarkurl'));
		$this->set('logourl',$this->__config->get('logourl'));
		$this->set('headertext',$this->__config->get('headertext'));
		$this->set('showcomments',$this->__config->get('showcomments'));;
		$this->set('footertext',$this->__config->get('footertext'));		
		$this->set('addvat',$this->__config->get('addvat'));
		$this->set('vatrate',(int)$this->__config->get('vatrate'));
		$this->set('watermarkurl',$this->__config->get('watermarkurl'));
		
		// stores configured language array
		$this->set('storeslanguage',explode(',',$this->__config->get('storeslanguage')));
		
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
				throw new exception('Class variable '.$_variableName. '('. $_variableData. ') cannot be empty.');
			}
			
			$this->set($_variableName,$_variableData);
						
		}
	}

	public function formatMoney($_x)
	{
		setlocale(LC_MONETARY, 'de_DE');
		
		//$_formatX=money_format('%=*^-14#8.2i', $_x);
		
		$_formatX=money_format('%.2n', $_x);
		
		return ($_formatX);
	
	}

}  
?>