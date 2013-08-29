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
 * Main Page class
 * -- Creates a PAGE
 * @access public
 * @return nix
 */
class Page {
	
	public $__;
	public $__config;
	public $__t;
	
		public function __construct($_variables) {
		
			// load class variables
			foreach ($_variables as $key => $value)
			{
				$this->set($key,$value);
			}

			// load app variables
			$this->__config= new config();
			
			// load app translator			
			$_languageCode=$this->__config->get('languagecode');
			if (empty($_languageCode)) { $_languageCode='en';}
			$this->__t=new Translator($_languageCode);			
		}
		
	    function __destruct() {
	       
	       unset($this->__config);
	       unset($this->__);
		   unset($this->__t);
	    }
	   
		public function set($key,$value)
		{
		    $this->__[$key] = $value;
		}
		
	  	public function get($variable)
		{
		    return $this->__[$variable];
		}
		
	   // helpers
	   public function loadClassVariables($_variableArray)
	   {
			if(is_array($_variableArray)) {
			
				foreach ($_variableArray as $key => $value)
				{
					$this->set($key,$value);
				}
			} 
			   
	    }
		
		public function truncateText($_text,$_length=25)
		{
			$_truncatedText=$_text;
			
			if (strlen($_text) > $_length) { $_truncatedText=preg_replace('/\s+?(\S+)?$/', '', substr($_text, 0, $_length)). '...'; }	
			
			return (htmlentities($_truncatedText, ENT_QUOTES, "UTF-8"));
		
		}
		
		/**
		 * curPageURL function.
		 * @what returns url of current page
		 * @access private
		 * @return what
		 */
		public function curPageURL() {
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
		}

		/**
		 * humanTiming function.
		 * @what returns time lapsed in easy human reading form
		 * @access protected
		 * @return string
		 */		
		protected function humanTiming ($time1,$time2)
		{
		
		    $time = $time1 - $time2; // to get the time since that moment
		
		    $tokens = array (
		        31536000 => 'year',
		        2592000 => 'month',
		        604800 => 'week',
		        86400 => 'day',
		        3600 => 'hour',
		        60 => 'minute',
		        1 => 'second'
		    );
		
	    foreach ($tokens as $unit => $text) {
	        if ($time < $unit) continue;
	        $numberOfUnits = floor($time / $unit);
	        return $numberOfUnits.' '. $this->__t->__($text.(($numberOfUnits>1)?'s':''). ' ago.');
	    }
		
		}

		/**
		 * slugify function.
		 * @what returns SEO friendly text for use as url slug
		 * @access protected
		 * @return string
		 */			
		static public function slugify($text)
		{ 
		  // replace non letter or digits by -
		  $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

		  // trim
		  $text = trim($text, '-');

		  // transliterate
		  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		  // lowercase
		  $text = strtolower($text);

		  // remove unwanted characters
		  $text = preg_replace('~[^-\w]+~', '', $text);

		  if (empty($text))
		  {
			return 'other';
		  }

		  return $text;
		}		

		public function getBrowser()
		{

			preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);

			if (count($matches)>1){
			  //Then we're using IE
			  $version = $matches[1];

			  switch(true){
				case ($version<=8):
				  return('MSIE');
				  break;

				case ($version==9):
				  //IE9!
				  return('MSIE9');
				  break;

				default:
				  return('MSIE10+');
			  }
			}

			if (strpos($_SERVER["HTTP_USER_AGENT"], 'Firefox')) { return('Firefox'); }
			if (strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome')) { return('Chrome'); }
			if (strpos($_SERVER["HTTP_USER_AGENT"], 'Safari')) { return('Safari'); }
			
			return ('Other');
			
		}

}
?>