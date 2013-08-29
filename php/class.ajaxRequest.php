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
 
 	usage index.php?ajex=true&class=className&variables=variable1=data1|variables2=data2
 */
 
/**
 * ajaxRequest class
 * a generic ajax php connector class
 * for connecting ajax requests to
 * PHP classes and returning data.
 */
 
class ajaxRequest {

	protected $__;
	protected $__config;

	public function __construct($_class) {

		$this->loadConfig();
		
		$this->processAjaxRequest($_class);

	}
	
	private function processAjaxRequest($_class)
	{
		
			// function variables
			$_responseArray = array();
			$_output=''; // ajax output
			$_return=''; // class return string
			$_classVariableArray=array();
			
				
			try // to work
			{
				
				// File Upload uploadify flash plugin
				if(array_key_exists('Filedata',$_FILES) && $_FILES['Filedata']['error'] == 0 ){
					
					$_tempFileName=$_FILES['Filedata']['tmp_name'];
					$_origFileName=$_FILES['Filedata']['name'];
					
					$_classVariableArray['tempfilename']= $_tempFileName;
					$_classVariableArray['origfilename']= $_origFileName;
					
					$_classVariableArray['xhrajaxupload']= false;
					
					if (isset($_GET['variables'])) {
						
						foreach ($_GET as $_key=>$_variableData)
						{
							$_classVariableArray[$_key]=$_variableData;
						}
					}					

				// File Uploads via AJAX XHR Ajax
				} elseif (isset($_SERVER['HTTP_X_FILENAME'])) { // filename 
				
					$_classVariableArray['uploadfilename']= $_SERVER['HTTP_X_FILENAME']; // used for generic FileUploadXHR class
					$_classVariableArray['tempfilename']= $this->__config->get('uploadFileCache').time().'-'.$_SERVER['HTTP_X_FILENAME'];
					$_classVariableArray['origfilename']= $_SERVER['HTTP_X_FILENAME'];
					$_classVariableArray['xhrajaxupload']= true;
					
					if (isset($_GET['variables'])) {
						
						foreach ($_GET as $_key=>$_variableData)
						{
							$_classVariableArray[$_key]=$_variableData;
						}
					}
					
				// validate class variables from POST or GET data
				// if POST variable array set then data is contained
				// in POST
				} elseif (isset($_POST['variables'])) { // if post contains variables
				
				 	foreach ($_POST as $_key=>$_variableData) // extract post variables to associative array
				 	{
					 	$_classVariableArray[$_key]=$_variableData;	
				 	}
					

				// No POST[variables] use GET 	
				} elseif (isset($_GET['variables'])) {
				
					$_variables = $_GET['variables'];
					
					// extract variable data into array
					$_classVariables=explode('|',$_variables);
					
					
					foreach ($_classVariables as $_key=>$_variableData) // create new associative variable array
					{
						
						if (empty($_variableData)) { throw new exception('Ajax class variable contains no data.');}
						
						$_variableDataArray=explode('=',$_variableData);
						
						if (!isset($_variableDataArray[0])){ throw new exception('Ajax class variable incorrectly formatted.');}
						if (!isset($_variableDataArray[1])){ throw new exception('Ajax class variable incorrectly formatted.');}
						
						// create class associative variable array
						$_classVariableArray[$_variableDataArray[0]]=$_variableDataArray[1];
					
					}					
					
					
				} else { // no variable data found in POST or GET
					throw new exception('Ajax class variables or data not found in POST or GET');
				}
				
				
				// call class with associative variable array from ajax request
				$_obj=new $_class($_classVariableArray);
				
					$_objSuccess=$_obj->get('success');

				if (!$_objSuccess){throw new exception(
					$_obj->get('errormessage')
					);}
					
				$_output=$_obj->get('output'); // class output data
				
				if (is_array($_output)) // output might be an array
				{
					foreach ($_output as $_outputKey=>$_outputData)
					{
						$_responseArray[$_outputKey] =  $_outputData;
					}
					
				} else {
				
					$_responseArray['output'] =  $_output;
				}
				
				$_responseArray['status'] = 'success';
				
				
				unset($_obj);
				
				// create json encoded response and return
				$_ajaxRequestReturn=json_encode($_responseArray);				
					$this->set('return',$_ajaxRequestReturn);
				
			}
			
			catch (Exception $e)
		    {
			    // return status as error
				$_responseArray['status'] = 'error';
				
				// return exception error
			    $_responseArray['output'] = $e->getMessage();
				
				$_ajaxMessage=$e->getMessage();
				$_responseArray['errormessage'] = $_ajaxMessage;
				
				$_ajaxRequestReturn=json_encode($_responseArray);
					$this->set('return',$_ajaxRequestReturn);
		    
		    }
	}

	private function loadConfig()
	{
		$this->__config= new config();
	}
	
	private function loadSession()
	{
		
		if (isset ($_POST['PHPSESSID'])) { session_id($_POST['PHPSESSID']); } // session id required for flash uploadify flash uploader
		if (!isset($_SESSION)) {
			
			session_start();
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

	public function __toString()
	{
		$_return=$this->get('return');
				return $_return;
	}
	
	 public function __destruct()
	{
	       unset($this->__config);
	       unset($this->__);
	}

}


