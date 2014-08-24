<?php

/****************
*		
*			The class below is based on this working code:  (all you need to do is put in the <?php ?> header.
*			Notice that this example takes inputs from a get.


$desc= array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("file", "C:/wamp/www/ctv_build/tmp/error-output.txt", "a") // stderr is a file to write to
);

$pipes = array();

$city = 'ERIE';
$cmd = 'phantomjs --ssl-protocol=any C:/wamp/www/ctv_build/js/phengine.js ' . $city;
$phengine = proc_open($cmd , $desc, $pipes);
$contentText = stream_get_contents($pipes[1]);
$content = preg_replace('/[^a-zA-Z0-9\s\p{P}]/', '', $contentText);
echo $content;

fclose($pipes[0]);
fclose($pipes[1]);
//if($pipes[2]) fclose($pipes[2]);
proc_close($phengine);


*
*
*
****************/


class phantomEngine
{
	private $desc= array(
	   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
	   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
	   2 => array("file", "C:/wamp/www/ctv_build/tmp/error-output.txt", "a") // stderr is a file to write to
	);
	private $pipes = array();
	private $city;
	private $county;
	private $street;
	private $houseNum;
	private $phengine;
	private $engineIsOpen=false;
	
	public function __construct($city, $county, $street, $houseNum) {
		$this->city = $city;
		$this->county = $county;
		$this->street = $street;
		$this->houseNum = $houseNum;
		
		$this->initializeEngine();
	}
	
	private function initializeEngine() {
		$cmd = $this->generateCmd();
		$this->phengine = proc_open($cmd, $this->desc, $this->pipes);
		$this->engineIsOpen=true;
	}
	
	private function generateCmd() {
		$cmd = 'phantomjs --ssl-protocol=any C:/wamp/www/ctv_build/js/phengine.js ' . $this->city;
		return $cmd;
	}
	
	public function getEngine() {
		if($this->engineIsOpen) {
			//do nothing;
		}else {
			$this->initializeEngine();
		}
		return $this->phengine;
	}
	
	public function closeEngine() {
		fclose($this->pipes[0]);
		fclose($this->pipes[1]);
		if(isset($pipes[2])) fclose($pipes[2]);
		if(proc_close($this->phengine)) {
			$this->engineIsOpen=false;
		}
	}
	
	public function getContent() {
		if($this->engineIsOpen){
			//echo "yes its open";
			$contentText = stream_get_contents($this->pipes[1]);
			$content = preg_replace('/[^a-zA-Z0-9\s\p{P}]/', '', $contentText);
			return $content;
		} else {
			return false;
		}
	}
}



?>