<?php
require_once ("vendor/autoload.php");
use \Cbthelper\Cbthelper as Cbthelper;
use Facebook\WebDriver\Remote\RemoteWebdriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedConditions;


//Set username and authkey for api requests

$username = "YOUR_USERNAME";
$authkey = "YOUR_AUTHKEY";

Cbthelper::login($username, $authkey);

//Build caps
$caps = Cbthelper::getCapsBuilder()
	->withPlatform("Windows 10")
	->withBrowserApiName("Chrome68")
    	->withResolution(1024, 768)
     	->withName("cbthelper test")
     	->withBuild("0.0.1")
     	->withRecordNetwork(false)
     	->withRecordVideo(true)
     	->build();



//wrapped in a try catch to ensure proper ending of test upon an exception
try{
	$host = "http://" . $username . ":" . $authkey . "@hub.crossbrowsertesting.com:80/wd/hub";
	$driver = RemoteWebDriver::create($host, $caps);

	//Initialize an AutomatedTest object with the selenium sessionid
	$myTest = Cbthelper::getTestFromId($driver->getSessionId());

	$driver->get("http://google.com");

	$video = $myTest->RecordingVideo();
	$video->setDescription("google");

	//Easily take a snapshot
	$googleSnap = $myTest->takeSnapshot();

	//Easily set snapshot description
	$googleSnap->setDescription("testsnap");

	//Save the snapshot locally
	$googleSnap->saveSnapshot("test/newfolder/testsnap1.png");

	$driver->get("http://crossbrowsertesting.com");

	//Take snapshot and set description with one call
	$myTest->takeSnapshot("cbtsnap");


	//downloads every snapshot for a given test and saves them in a directory
    //can set useDescription to name the images what we set as the description
    //alternatively can set a prefix (default 'image') and images will be indexed
	$myTest->saveAllSnapshots("test/newfolder", $useDescription = true);

	//Sets the test score
	$myTest->setScore("pass");

	//Quits the driver and ends the test
	$driver->quit();

	$video->saveVideo("test/newfolder/video.mp4");

	//Our test history api call takes a lot of optional parameters
    //The builder makes it easier to get what you want
	$options =Cbthelper::getTestHistoryBuilder()
			->withLimit(1)
			->withName("cbthelper testy")
			->build();

	var_dump($options);

	//Grab our history using the options we created above
	$history = Cbthelper::getTestHistory($options);
	$data =json_decode($history, TRUE);
	var_dump(json_encode($data["selenium"], TRUE));

//Handles any exception, print the error, and quits the driver	
} catch (Exception $e) {
	echo "Caught Exception: " . $ex->getMessage();
	$driver->quit();
}
			


?>
