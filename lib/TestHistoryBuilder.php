<?php namespace Cbthelper;
//Builder to generate options for getting test history
//All of the with.. methods return this for method chaining

class TestHistoryBuilder{
	static private $options;

	function __construct(){
		$this->$options = array();
		return $this;
	}

	//Sets the max number of tests to return
	function withLimit($limit){
		$this->$options["num"] = $limit;
		return $this;
	}

	//If set, will only return active or inactive tests
    //@param active: boolean value
	function withActive($active){
		$this->$options["active"] = $active;
		return $this;
	}

	//Will only return test that match the name given
	function withName($name){
		$this->$options["name"]= $name;
		return $this;
	}

	//Will only return tests that match the build given
	function withBuild($build){
		$this->$options["build"] = $build;
		return $this;
	}

	//Will only return tests that navigate to the same url
	function withUrl($url){
		$this->$options["url"] = $url;
		return $this;
	}

	//Will only return tests with the score specified ('pass', 'fail', 'unset')
	function withScore($score){
		$this->$options["score"] = $score;
		return $this;
	}

	//Will only return tests with the same platform (OS)
    //@param platform: string with the platform (eg. 'Windows 10', 'Mac OS 10.13')
	function withPlatform($platform){
		$this->$options["platform"] = $platform;
		return $this;
	}

	//Will only return tests with the same platformType (OS Family)
    //@param platformType: string with the platform type (eg. 'Windows', 'Mac', 'Android')
	function withPlatformType($platformType){
		$this->$options["platformType"] = $platformType;
		return $this;
	}

	//Will only return tests that used the same browser
    //@param browser: a string with the browser name and version: (eg. Chrome 65)
	function withBrowser($browser){
		$this->$options["browser"] = $browser;
		return $this;
	}

    //Will only return tests that used the same browser type
    //@param browserType: a string representing the browser family (eg. 'Chrome', 'Edge', 'Safari')
	function withBrowserType($browserType){
		$this->$options["browserType"] = $browserType;
		return $this;
	}

	//Will only return tests that used the same resolution
    //@param resolution: a string with the form 'WIDTHxHEIGHT' (eg. '1024x768')
	function withResolution($resolution){
		$this->$options["resolution"] = $resolution;
		return $this;
	}


	function withStartDate($startDate){
		$this->$options["startDate"] = $startDate;
		return $this;
	}
	function withEndDate($endDate){
		$this->$options["endDate"] = $endDate;
		return $this;
	}

	//Generates the test history options
   	//@return : a php array to pass to getTestHistory()
	function build(){
		return $this->$options;
	}

}



