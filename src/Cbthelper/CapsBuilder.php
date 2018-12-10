<?php namespace Cbthelper;

//Builder for generating selenium capabilities
//All of the with... methods return this for method chaining
class CapsBuilder{
	static private $options;

	function __construct(){
		$this->$options = array();
		return $this;
	}

	//Sets the platform (OS)
    //@param platform: a string specifying the platform (eg. Windows 7, Mac 10.13)
	function withPlatform($platform){
		$this->$options["platform"] = $platform;
		return $this;
	}

	//Sets the browser
    //@param browser: as string specifying the browser (eg. Edge 17, Chrome 55x64)
	function withBrowserApiName($browser){
		$this->$options["browser_api_name"] = $browser;
		return $this;
	}

	//Set the screens size for the test
	function withResolution($width, $height){
		$this->$options["screenResolution"] = $width.'x'.$height;
		return $this;
	}

	//Sets the name of the test in the web app
	function withName($name){
		$this->$options["name"] = $name;
		return $this;
	}

	//Sets the build number in the web app
	function withBuild($build){
		$this->$options["build"] = $build;
		return $this;
	}

	//Records a video for the length of the test
	function withRecordVideo($bool){
		$this->$options["record_video"] =$bool;
		return $this;
	}

	//Records network traffic for the length of the test
	function withRecordNetwork($bool){
		$this->$options["record_network"] = $bool;
		return $this;
	}

	//Used to generate the capabilites
	function build(){
		return $this->$options;
	}


}