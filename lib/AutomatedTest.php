<?php namespace Cbthelper;

include 'Snapshot.php';
include 'Video.php';

//Helpful representation of a selenium test
class AutomatedTest{
	static private $testId;
	static private $username;
	static private $authkey;

	//@param testId: the selenium session ID, usually from webdriver
	function __construct($testId, $username, $authkey){
		self::$testId = $testId;
		self::$username= $username;
		self::$authkey = $authkey;
	}

	//Sets the score for our test in the CBT app
    //@param score: should be 'pass', 'fail', or 'unset'.
	function setScore($score){
		$url = "https://crossbrowsertesting.com/api/v3/selenium/" . self::$testId;
		$options = array('action'=>'set_score', 'score'=>$score);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    	curl_setopt($ch, CURLOPT_USERPWD, self::$username . ":" . self::$authkey);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$info = curl_exec($ch);
		curl_close ($ch);
	}

	//Sets the description for the test in the web app
	function setDescription($description){
		$url = "https://@crossbrowsertesting.com/api/v3/selenium/" . self::$testId;
		$options = array('description'=>$description);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    	curl_setopt($ch, CURLOPT_USERPWD, self::$username . ":" . self::$authkey);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$info = curl_exec($ch);
		curl_close ($ch);
	}


	//Sends the command to take a snapshot and returns a Snapshot instance
    //@param description: (optional) shortcut for Snapshot->setDescription
	function takeSnapshot($description = null){
			$url = "https://" . self::$username . ":" . self::$authkey . "@crossbrowsertesting.com/api/v3/selenium/" . self::$testId. "/snapshots";
			
			$opts = array('http' => array('method'  => 'POST',));
			$context  = stream_context_create($opts);
			$response = file_get_contents($url, false, $context);
			$hash = json_decode($response, TRUE)["hash"];
			$snap = new Snapshot($hash, self::$testId, self::$username, self::$authkey);
			if ($description != null){
				$snap->setDescription($description);
			} 
			return $snap;
	}

	//Downloads all snapshots for this test into the provided directory
	function saveAllSnapshots($directory, $useDescription=false){
		$url = "https://crossbrowsertesting.com/api/v3/selenium/".self::$testId."/snapshots/";
		$prefix = "image";
		$count = 0;
		$ch = curl_init();
	   	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	    curl_setopt($ch, CURLOPT_USERPWD, self::$username. ":" . self::$authkey);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close ($ch);

		$body =json_decode($response, TRUE);
		foreach ($body as $key) {
			$ret= new Snapshot($key["hash"], self::$testId, self::$username, self::$authkey);
			$data =json_decode($ret::$info, true);
			if ($useDescription and $data["description"]!=null) {
				$img = $data["description"] . ".png";
			}
			else{
				$img = $prefix . (string) $count .".png";
			}
			
			$path = $directory . "/". $img;
			$ret::saveSnapshot($path);
			$count++;
			
		}

	}

	#Return the video recording for this test
	function RecordingVideo($description = null){
		$ch = curl_init();
	   	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	    curl_setopt($ch, CURLOPT_USERPWD, self::$username. ":" . self::$authkey);
		curl_setopt($ch, CURLOPT_URL, "https://crossbrowsertesting.com/api/v3/selenium/".self::$testId."/videos/");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close ($ch);

		$body =json_decode($response, TRUE);
		foreach ($body as $key) {
			$video = new Video($key["hash"], self::$testId, self::$username, self::$authkey);		
			if ($description != null){
				$video->setDescription($description);
			} 
			return $video;
		}

	}

	#Downloads all videos for this test into a directory
	function saveAllVideos($directory, $useDescription=false){
		$prefix = "video";
		$count = 0;
		$ch = curl_init();
	   	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	    curl_setopt($ch, CURLOPT_USERPWD, self::$username. ":" . self::$authkey);
		curl_setopt($ch, CURLOPT_URL, "https://crossbrowsertesting.com/api/v3/selenium/".self::$testId."/videos/");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close ($ch);

		$body =json_decode($response, TRUE);
		foreach ($body as $key) {
			$ret= new Video($key["hash"], self::$testId, self::$username, self::$authkey);
			$data =json_decode($ret::$info, true);
			if ($useDescription and $data["description"]!=null) {
				$vid = $data["description"] . ".mp4";
			}
			else{
				$vid = $prefix . (string) $count .".mp4";
			}
			
			$path = $directory . "/". $vid;
			$ret::saveVideo($path);
			$count++;
			
		}
		
	}


}


?>