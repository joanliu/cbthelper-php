<?php namespace Cbthelper;

require 'TestHistoryBuilder.php';
require 'CapsBuilder.php';
require 'AutomatedTest.php';



class Cbthelper{
	static private $username;
	static private $authkey;
	static private $caps;

	//Used to get the selenium capability builder
	//Generating the CapsBuilder pulls in a large amount of data, so user should not call the constructor manually
	function getCapsBuilder(){
		self::$caps = new CapsBuilder();
		return self::$caps;
	}

	//Sets the username and sutjkey used to make the HTTP requests
	function login($username, $authkey){
		self::$username = $username;
		self::$authkey = $authkey;
	}

	//Used to get the TestHistoryBuilder
	//Can also just call the contructor. Method to match getCapsBuilder()
	function getTestHistoryBuilder(){
		return new TestHistoryBuilder();
	}

	//Returns a PHP array with the test history, filtering based on the options given
	//@param options: a PHP array created by TestHistoryBuilder
	function getTestHistory($options){
		$ch = curl_init();
   		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    	curl_setopt($ch, CURLOPT_USERPWD, self::$username. ":" .self::$authkey);
		curl_setopt($ch, CURLOPT_URL, "https://crossbrowsertesting.com/api/v3/selenium/");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		curl_close ($ch);
		return $data;

	}

	//Creates an automated test from the selenium session id
	//@param sessid: string for the selenium session/test id, should come from WebDriver
	function getTestFromId($sessid){
		return new AutomatedTest($sessid, self::$username, self::$authkey);
	}
	function username(){
		return self::$username;
	}
	function authkey(){
		return self::$authkey;
	}
	function caps(){
		return self::$caps;
	}

}




?>