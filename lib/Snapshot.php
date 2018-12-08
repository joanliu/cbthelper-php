<?php namespace Cbthelper;

//Represents a snapshot for selenium test
class Snapshot{
	static $hash;
	static private $testId;
	static $info;
	static private $url;
	static private $username;
	static private $authkey;

	//@param hash: the hash for this image, returned by rest api when taking a screenshot
	//@param test: an AutomatedTest object that represents a test currently running
	function __construct($hash, $testId, $username, $authkey){
			self::$hash = $hash;
			self::$testId = $testId;
			self::$info = $this->getInfo();
			self::$url = 'https://crossbrowsertesting.com/api/v3/selenium/'.$testId.'/snapshots/'.$hash;
			self::$username= $username;
			self::$authkey = $authkey;
	} 

	//Calls out to api to get updated info this snapshot
	//@return : a response object with all of the info for this Snapshot
	function getInfo(){
		$ch = curl_init();
   		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    	curl_setopt($ch, CURLOPT_USERPWD, self::$username . ":" . self::$authkey);
		curl_setopt($ch, CURLOPT_URL, self::$url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$info = curl_exec($ch);
		curl_close ($ch);
		return $info;
	}

	//Sets the description for this snapshot
	function setDescription($description){
		$options = array('description'=>$description);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    	curl_setopt($ch, CURLOPT_USERPWD, self::$username . ":" . self::$authkey);
		curl_setopt($ch, CURLOPT_URL, self::$url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$info = curl_exec($ch);
		curl_close ($ch);
	}

	//Downloads the snapshot to the given location
	//@param location: a string with the local and filename for the image. Should have a .png extension
	function saveSnapshot($location){
		$body = json_decode(self::$info, TRUE);
		$url = $body["image"];

		//Checks if directory exists, creates it if it doesn't
		$path=dirname($location, 1);
		if(!file_exists($path)){
			mkdir($path, 0777, TRUE);
		}

		//Downloads the image to the given location 
		$options = array(
      		CURLOPT_FILE => is_resource($location) ? $location : fopen($location, 'w'),
      		CURLOPT_FOLLOWLOCATION => true,
      		CURLOPT_URL => $url,
      		CURLOPT_FAILONERROR => true, 
    	);
    	$ch = curl_init();
    	curl_setopt_array($ch, $options);
    	$return = curl_exec($ch);
	}

}

?>
