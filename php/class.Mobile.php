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

//
// class to detect mobile device
//
class Mobile
{
    /**
     * Mobile Response
     */
    const MOBILE_RESPONSE = 'Mobile';

   /**
     * iPhone Response
     */
    const IPHONE_RESPONSE = 'iPhone';

    /**
     * iPad Response
     */
    const IPAD_RESPONSE = 'iPad';

    /**
     * iPod Response
     */
    const IPOD_RESPONSE = 'iPod';
	
    /**
     * Android Response
     */
    const ANDROID_RESPONSE = 'Android';

    /**
     * BlackBerry Response
     */
    const BLACKBERRY_RESPONSE = 'BlackBerry';
	
    /**
     * Windows Mobile
     */
    const WINDOWS_MOBILE = 'IEMobile';	
	
	
	
    /**
     * Retrives is iPhone Flag
     * @return boolean
     */
    public function iPhone()
    {
        try {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::IPHONE_RESPONSE) !== false);
        } catch(Exception $e){
            return false;
        }
    }

    /**
     * Retrives is iPad Flag
     * @return boolean
     */
    public function iPad()
    {
        try {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::IPAD_RESPONSE) !== false);
        } catch(Exception $e){
            return false;
        }
    }

    /**
     * Retrives is iPod Flag
     * @return boolean
     */
    public function iPod()
    {
        try {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::IPOD_RESPONSE) !== false);
        } catch(Exception $e){
            return false;
        }
    }		
	
    /**
     * Retrives is Android Flag
     * @return boolean
     */
    public function Android()
    {
        try {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::ANDROID_RESPONSE) !== false);
        } catch(Exception $e){
            return false;
        }        
    }

    /**
     * Retrives is BlackBerry Flag
     * @return boolean
     */
    public function BlackBerry()
    {
        try {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::BLACKBERRY_RESPONSE) !== false);
        } catch(Exception $e){
            return false;
        }
    }
	
    /**
     * Retrives is Windows Mobile Flag
     * @return boolean
     */
    public function WindowsMobile()
    {
        try {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::WINDOWS_MOBILE) !== false);
        } catch(Exception $e){
            return false;
        }
    }	

    /**
     * Retrives target platform
     * @return string
     */
    public function isMobilePlatform()
    {
            if (    /** Select a platform */
                    self::iPhone() ||
					self::iPod() ||
					self::WindowsMobile() ||
                    self::Android() ||
                    self::BlackBerry()

                ) { return true; }        
			
        return false;
    }	

}
