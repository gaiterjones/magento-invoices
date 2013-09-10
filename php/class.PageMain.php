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
 * PageMain class.
 * 
 * @extends Page
 */
class PageMain extends Page {


	public function __construct($_variables) {
	
		// load parent
		parent::__construct($_variables);
		
		// define class variables
		$_array=array(
			"mobileplatform"			=> Mobile::isMobilePlatform(),
			"ordertimerange"	 		=> $this->__config->get('ordertimerange'),
			"timezone"			 		=> $this->__config->get('timezone'),
			"cachefolder"		 		=> $this->__config->get('cachefolder')
		);
		
		// load class variables
		$this->loadClassVariables($_array);
		
		// load the order html
		PageMainData::getOrdersHTML();
		
		// load the page html
		PageMainHTML::html();
		
		// render page
		$this->createPage();
		
	}


		/**
		 * renderPage function.
		 * 
		 * @access private
		 * @return void
		 */
		private function createPage()
		{
			if (!empty($_errorMessage)) {  $this->set('selectedcollectionname', 'error'); }
			$_HTMLArray=$this->get('html');
			$_errorMessage=$this->get('errorMessage');
			
			/* render html from html array */
			foreach ($_HTMLArray as $_obj)
			{
				$_usePageHtml=false;
				
				foreach ($_obj as $_key=>$_value)
				{
					
					if ($_key === 'page')
					{				
						$_array=$_value;
						foreach ($_array as $_key=>$_page)
						{
							// render default html
							if ($_page == '*')	{$_usePageHtml=true;}
						
							
							if (empty($_errorMessage))
							{
								// no errors
								
							
															
							
							} else {
							
								// display error html
								
								if ($_page == 'error')	{$_usePageHtml=true; }
								
							}
							
	
						
						
						}
	
					}
					
					if ($_key === 'html')
					{
						if ($_usePageHtml)
						{
							$_pageHtml=$_pageHtml.$_value;
						}
					}
	
				}
	
			}
			
			$this->set('pageHtml',$_pageHtml);
		}



		/**
		 * __toString function.
		 * 
		 * @access public
		 * @return void
		 */
		public function __toString()
		{
			$html=$this->get('pageHtml');
					return $html;
		}

}
?>