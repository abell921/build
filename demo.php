<?php
/*
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
		//$this->county = $county;
		//$this->street = $street;
		//$this->houseNum = $houseNum;
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
		//if($pipes[2]) fclose($pipes[2]);
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

$phEngineFrame = new phantomEngine('ERIE', 1, 1, 1);
$engine_proc = $phEngineFrame->getEngine();
$content = $phEngineFrame->getContent();
echo $content;

*/


?>
<html>
<head>


<meta name="viewport" content="width=device-width" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<link type="text/css" rel="stylesheet" href="css/demo.css"/>
<link type="text/css" rel="stylesheet" href="css/font_push.css"/>

<!-- Never miss another election -->

 <script type="text/javascript">
        $(document).ready(function () {
		
			/*
			*		Check box UI
			*		
			*/
			$select_all = $("input[type='checkbox']").filter('#i0');
			$select_all.bind( "click", function() {
				if($select_all.is(':checked')) {
					$("input[name^='issue'").prop('checked',true);
				} else {
					$("input[name^='issue'").prop('checked',false);
				}
			});
			$("input[name^='issue'").bind("click", function() {
				if($select_all.is(':checked')) {
					$select_all.prop('checked',false);
				}
			});
			
			
			
		
		});
</script>


</head>
<body>

	<div id="content">
		<div id="big-box">
			<table>
				<tr><td><input style="width:160px" type="text" placeholder="City"></input>
						<input style="width:160px; margin-left:5px" type="text" placeholder="County"></input></td>
				<tr><td><input style="width:331px;" type="text" placeholder="Address"></input></td></tr>
			</table>
		</div>
		
		
		
		
		
		
		<br><br><br>
		<form name="input" action="postdata.php" method="post">
		<table> <!-- table containing political parties -->
			<tr>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="party[]" value="D" id="p1" checked><label for="p1"><span></span><h7>Democrat</h7></label>
					</div>
				</td>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="party[]" value="R" id="p2" checked><label for="p2"><span></span><h7>Republican</h7></label></td>
					</div>
				</td>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="party[]" value="Third" id="p3" checked><label for="p3"><span></span><h7>3rd Party</h7></label></td>					
					</div>
				</td>
			</tr>
		</table>
		
		<br><br><br>
		
		<table> <!-- Table containing issues -->
			<t>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="null" value="selectall" id="i0" checked><label for="i0"><span></span><h7>Select All</h7></label>
					</div>				
				</td>
			<tr>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="issue[]" value="heal" id="i1" checked><label for="i1"><span></span><h7>Healthcare</h7></label>
					</div>
				</td>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="issue[]" value="minw" id="i2" checked><label for="i2"><span></span><h7>Minimum Wage</h7></label>
					</div>
				</td>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="issue[]" value="ener" id="i3" checked><label for="i3"><span></span><h7>Energy</h7></label>					
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="issue[]" value="tax" id="i4" checked><label for="i4"><span></span><h7>Taxes</h7></label>
					</div>
				</td>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="issue[]" value="healthcare" id="i5" checked><label for="i5"><span></span><h7>Healthcare</h7></label>
					</div>
				</td>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="issue[]" value="healthcare" id="i6" checked><label for="i6"><span></span><h7>Healthcare</h7></label>					
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="issue[]" value="healthcare" id="i7" checked><label for="i7"><span></span><h7>Healthcare</h7></label>
					</div>
				</td>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="issue[]" value="healthcare" id="i8" checked><label for="i8"><span></span><h7>Healthcare</h7></label>
					</div>
				</td>
				<td>
					<div class="checkbox-wrap1">
						<input type="checkbox" name="issue[]" value="healthcare" id="i9" checked><label for="i9"><span></span><h7>Healthcare</h7></label>					
					</div>
				</td>
			</tr>			
			

		</table>
			
		<input type="submit" value="Submit">
		</form>
		
	</div>


</body>
</html>