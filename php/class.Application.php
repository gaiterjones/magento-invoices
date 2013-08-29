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

/* Main application class */
class Application
{
	
	protected $__;
	protected $__config;
	protected $__progress;
	
	public function __construct() {
		
		try
		{
			$this->set('errorMessage','');
			$this->loadConfig();
			$this->getExternalVariables();
			$this->renderPage();
		
		}
		catch (Exception $e)
	    {
	    	$this->set('errorMessage', 'An error has occurred : '. $e->getMessage(). ' <a class="fancybox" href="#errorReport">!</a>
			<div id="errorReport" style="display: none;">Error trace (if available) - <pre>'. $e->getTraceAsString(). '</pre></div>');
			
			$this->renderPage();
	    	exit;
	    }
	}

	private function loadConfig()
	{
		$this->__config = new config();

	}
	
	private function getExternalVariables()
	{
		
		// -- initialise variables from GET	
		//
		if(isset($_GET['ajax'])){ $_ajaxRequest = true;} else { $_ajaxRequest = false;}								// ajax requests boolean
		if(isset($_GET['class'])){ $_ajaxClass = $_GET['class'];} else { $_ajaxClass = false;}	

		// -- process ajax requests
		//
		if ($_ajaxRequest)
		{
			if ($_ajaxClass) { // ajax class set

				$_ajaxRequest=new ajaxRequest($_ajaxClass);
				header('Access-Control-Allow-Origin: *');
				header('Content-type: text/json; charset=utf-8');
					echo $_ajaxRequest;
						unset($_ajaxRequest);
							exit;
			}
			// invalid ajax class
			exit;
		}	
		
		// -- store id 
		if(isset($_GET['storeid']))
		{
			// set store manually
			$_storeID = $_GET['storeid'];
			//$this->getLanguageFromStoreID($_storeID);
			
		} else {
			// set store based on browser language
			//$_storeID=$this->getStoreLanguage($this->getBrowserLanguages());
			$_storeID='0';
		}
		
		// store class variables
		$this->set('storeid',$_storeID);
	}
	
		// -- render page
		//
		public function renderPage()
		{
			// ouput methods
			// 1. HTML
			
			$this->set('requestedpage','main');
			
			// get Page class
			$_pageClass=explode('-',$this->get('requestedpage'));
			$_requestedPage=$_pageClass[0];
			$_requestedSubPage=null;
			
			if (isset($_pageClass[1])) { $_requestedSubPage=$_pageClass[1]; }
			
			$_pageClass='Page'.ucfirst($_requestedPage);
			
			if (!file_exists('php/class.' . $_pageClass . '.php')) { throw new exception('Requested page class '. $_pageClass. ' is not valid.'); }
			
			$_page = new $_pageClass(array(
			  "storeid"			 		=> 		$this->get('storeid'),
			  "errorMessage" 		 	=>		$this->__['errorMessage']
			));			
			
			// output
			
			
				echo $_page;
			
			unset($_page);
		}	

		/**
		 * clean_up function.
		 * 
		 * @access private
		 * @param mixed $text
		 * @return void
		 */
		private function clean_up ($text)
		{
			$cleanText=$this->replaceHtmlBreaks($text," ");
			$cleanText=$this->strip_html_tags($cleanText);
			$cleanText=preg_replace("/&#?[a-z0-9]+;/i"," ",$cleanText);
			$cleanText=htmlspecialchars($cleanText);
			
			return $cleanText;
		}
		
		/**
		 * strip_html_tags function.
		 * 
		 * @access private
		 * @param mixed $text
		 * @return void
		 */
		private function strip_html_tags( $text )
		{
		    $text = preg_replace(
		        array(
		          // Remove invisible content
		            '@<head[^>]*?>.*?</head>@siu',
		            '@<style[^>]*?>.*?</style>@siu',
		            '@<script[^>]*?.*?</script>@siu',
		            '@<object[^>]*?.*?</object>@siu',
		            '@<embed[^>]*?.*?</embed>@siu',
		            '@<applet[^>]*?.*?</applet>@siu',
		            '@<noframes[^>]*?.*?</noframes>@siu',
		            '@<noscript[^>]*?.*?</noscript>@siu',
		            '@<noembed[^>]*?.*?</noembed>@siu',
		          // Add line breaks before and after blocks
		            '@</?((address)|(blockquote)|(center)|(del))@iu',
		            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
		            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
		            '@</?((table)|(th)|(td)|(caption))@iu',
		            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
		            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
		            '@</?((frameset)|(frame)|(iframe))@iu',
		        ),
		        array(
		            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
		            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
		            "\n\$0", "\n\$0",
		        ),
		        $text );
		    return strip_tags( $text );
		}
		
		function roundPrice($price){
			$rounded = round($price, 1);
			$rounded = $rounded + 0.09;
			return number_format($rounded, 2);
		}		
		
		/**
		 * replaceHtmlBreaks function.
		 * 
		 * @access private
		 * @param mixed $str
		 * @param mixed $replace
		 * @param mixed $multiIstance (default: FALSE)
		 * @return void
		 */
		private function replaceHtmlBreaks($str, $replace, $multiIstance = FALSE)
		{
		  
		    $base = '<[bB][rR][\s]*[/]*[\s]*>';
		    
		    $pattern = '|' . $base . '|';
		    
		    if ($multiIstance === TRUE) {
		        //The pipe (|) delimiter can be changed, if necessary.
		        
		        $pattern = '|([\s]*' . $base . '[\s]*)+|';
		    }
		    
		    return preg_replace($pattern, $replace, $str);
		}

		public function set($key,$value)
		{
			$this->__[$key] = $value;
		}
			
	  	public function get($variable)
		{
			return $this->__[$variable];
		}
		
	}
?>