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
 *
 */
 
class Translator {

    protected $__;
	protected $__cache;
    private $lang = array();

    public function __construct($_languageCode) {
    
    	$this->set('languageCode',$_languageCode);
    }
    
    public function __($str) {

		$lang=$this->get('languageCode');		
	
		if ($lang==='en') { return $str; } // do not translate default language - english

    	try {
			
			$_localeFile= dirname(__FILE__). '/locale/'. $lang. '.txt';			
			
			// load cached translations
			//
			$this->loadMemcache();
			
			$_cacheConnected=$this->__cache->get('memcacheconnected');
			$_cacheKey='TRANSLATIONS_'.  $lang. '_'. filemtime($_localeFile);
			
			$this->loadCachedTranslations($_cacheKey);
    	
	        if (!array_key_exists($lang, $this->lang)) { // translation data array doesn't exist, create it
			
	            if (file_exists($_localeFile)) {
				
	                $strings = array_map(array($this,'splitStrings'),file($_localeFile));
					
	                foreach ($strings as $k => $v) {
	                    $this->lang[$lang][$v[0]] = $v[1];
	                }
					
					// try and memcache translations
					if ($_cacheConnected) { $this->__cache->cacheSet($_cacheKey, serialize($this->lang),86400); }
					
	                return $this->findString($str, $lang);
					
	            } else { // no locale file
	                
					return $str;
	            }
	        
			} else { 
				
				return $this->findString($str, $lang);
	        }
	        
	    } catch (Exception $e) {

		    // catch translation errors quietly, just
		    // return the original string and pretend
		    // nothing happened...
		    return $str;
		}
    }

    private function findString($str,$lang) {
	
        if (array_key_exists($str, $this->lang[$lang])) {
            return $this->lang[$lang][$str];
        }
		    
		$helper=false;
		if ($helper) { file_put_contents('/home/www/medazzaland/cache/translation_helper.txt', $str. "\n", FILE_APPEND);} // helper logs translations not found
        
		return $str;
    }
    
    private function splitStrings($str) {
        return explode('=',trim($str));
    }

	private function loadMemcache()
	{
		$this->__cache=new cacheMemcache();
	}

	private function loadCachedTranslations($_cacheKey)
	{
		$_cacheConnected=$this->__cache->get('memcacheconnected');
		
		if ($_cacheConnected) {

			$_translations=$this->__cache->cacheGet($_cacheKey);

				if ($_translations) { $this->lang=unserialize($_translations);}
		}

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