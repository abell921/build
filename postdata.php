<?php
include_once( dirname( __FILE__ ) . '/include/db_connect.php' );
$party = $_POST['party'];
$issueCode = $_POST['issue'];

class buildBallot
{

	
	private $_DBH;
	private	$party;
	private	$issueCode;
	private $outCandidates=null;
	private $outIssues=null;
	private $sql;
	
	
	public function __construct($DBH, $parParty, $parIssue) {
		
		$this->_DBH = $DBH;
		
		$this->party = $parParty;
		$this->issueCode = $parIssue;
		$this->filterCandidateData();
		$this->filterIssueData($this->outCandidates);
	}
	
	/**
	*	
	*	@param $candObj		Object containing candidates; the object retrieved from fetchAll(PDO::FETCH_OBJ)
	*/
	private function filterIssueData($candObj) {
		//Requires a candidate object to filter issues
		$ISsql = 'SELECT * FROM `cand_issues` WHERE ';
		foreach($candObj as $currCand) {
			$ISsql .= 'cand_id=';
			$ISsql .= $currCand->cand_id;
			if($currCand != end($candObj)) {
				$ISsql .= ' OR ';
			}	//Query will grab all the issues for the candidates grabbed below
		} 
		//echo $ISsql;
		/*
		$ISsql = 'SELECT * FROM `cand_issues` WHERE ';
		foreach ($this->issueCode as $issue) {
			$ISsql .= ' code="';
			$ISsql .= $issue;
			$ISsql .= '"';
			if($issue != end($this->issueCode)) {
				$ISsql .= ' OR';
			}
		}*/
		
		
		$ISquery = $this->_DBH->prepare($ISsql);
		$ISquery->execute();
		$candidate_issues = $ISquery->fetchAll( PDO::FETCH_OBJ );
		
		$this->outIssues = $candidate_issues;
	}
	
	
	private function filterCandidateData() {
		$CDsql = 'SELECT * FROM `candidate_data` WHERE ';
		foreach ($this->party as $parVal) {
			$CDsql .= ' party="';
			$CDsql .= $parVal;
			$CDsql .= '"';
			if($parVal != end($this->party)) {
				$CDsql .= ' OR';
			}
		}
		//echo $CDsql;
		
		$CDquery = $this->_DBH->prepare($CDsql);
		$CDquery->execute();
		$candidate_info = $CDquery->fetchAll( PDO::FETCH_OBJ );
		
		$this->outCandidates = $candidate_info;
	}
	
	public function getIssueData() {
		return $this->outIssues;
	}
	
	public function getCandidateData() {
		return $this->outCandidates;
	}
		
}

$newBallot = new buildBallot($DBH, $party, $issueCode);
$candidateObj = $newBallot->getCandidateData();
$issueObj = $newBallot->getIssueData();

function decodeParty($party) {
	$dec = array(
		"R" => "Republican",
		"D" => "Democract"
	);
	return $dec[$party];
}

function decodeIssue($issue) {
	$dec = array(
		"abor" => "Abortion",
		"govt" => "Government",
		"drug" => "Drugs",
		"educ" => "Education",
		"guns" => "Guns",
		"ener" => "Energy",
		"heal" => "Health Care",
		"minw" => "Minimum Wage",
		"jobs" => "Jobs"
	);
	return $dec[$issue];
}
//print_r($candidateObj);
/*
function sortMe($candObj) {
	$currentPos=null;
	if(isset($candObj[0]->position)) {
		$currentPos=$candObj[0]->position;
	}
	foreach($candObj as $currCand) {
		if($currCand->position=$currentPos) {
			echo $currCand->last_name;
		
		
		} 
	
	}

}*/


/*
include_once( dirname( __FILE__ ) . '/class/phEngine.class.php' );
$phEngineFrame = new phantomEngine('ERIE', 1, 1, 1);
//$engine_proc = $phEngineFrame->getEngine();
$content = $phEngineFrame->getContent();
echo $content;
$phEngineFrame->closeEngine();
*/

//sortMe($candidateObj);


?>

<html>
<head>

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<link type="text/css" rel="stylesheet" href="css/list.css"/>
<link type="text/css" rel="stylesheet" href="css/font_push.css"/>


</head>
<body>

	<div id="wrap">
		<?php
		
		function sortMe($candObj, $issueObj) {
			$currentPos=null;
			if(isset($candObj[0]->position)) {
				$currentPos=$candObj[0]->position;
				echo "<h2>$currentPos</h2>";
			}
			foreach($candObj as $currCand) {
				if($currCand->position=$currentPos) {
					$currParty = decodeParty($currCand->party); //Necessary because party is stored as a single character, and we need a string.
					echo "<div class='candidate-box $currCand->party'>
							<div class='box-inset'>
								<div class='cand-profile'><img src='$currCand->profile'></div>
								<div class='cand-header'><h1>$currCand->first_name $currCand->middle_init $currCand->last_name</h1>
									<div class='cand-info'>$currParty<h7>$currCand->city, $currCand->state</div>
									<div class='cand-info'>
										<a class='twitter-button' href='$currCand->twitter'></a>
										<h7><a href='$currCand->website'>Website</a></h7>
									</div>
								</div>
								<div class='cand-issues'>
									<ul>";
									
					$x=0;
					foreach($issueObj as $Issue) {
						if($Issue->cand_id === $currCand->cand_id && $x<=2) {
							$currIssueTitle = decodeIssue($Issue->code);
							$currIssueContent = $Issue->position;
							echo "			<li><b>$currIssueTitle:</b> $currIssueContent</li>";
							$x++; 
						}
					}
								
					echo "
									</ul>
								</div>
							</div>					
						</div>";
				}
			}
		}
		
		sortMe($candidateObj, $issueObj);
		?>
	</div>



</body>
</html>


<!--
<div class="candidate-box D">
	<div class="box-inset">
		<div class="cand-profile"> <img src="profiles/corbett-profile.png"></div>
		<div class="cand-header"><h1>Tom Corbett</h1>
			<div class="cand-info">Republican<h7>Philadelphia, PA</h7></div>
			<div class="cand-info">
				<a class="twitter-button" href="#"></a>
				<h7><a href="https://twitter.com/GovernorCorbett">Website</a></h7>
			</div>
		</div>
		<div class="cand-issues">
			<ul>
				<li><b>Energy:</b> Supports natural gas drilling in PA</li>
				<li><b>Guns:</b> Strong supporter of 2nd amendment rights</li>
				<li><b>Education:</b> Allow kids in bad schools to attend private schools</li>
			</ul>
		</div>
	</div>
</div>

<div class="candidate-box D">
	<div class="box-inset">
		<div class="cand-profile"> <img src="profiles/wolf-profile.png"></div>
		<div class="cand-header"><h1>Tom Wolf</h1>
			<div class="cand-info">Democrat<h7>Central Pennslyvania</h7></div>
			<div class="cand-info">
				<a class="twitter-button" href="#"></a>
				<h7><a href="https://twitter.com/GovernorCorbett">Website</a></h7>
			</div>
		
		</div>
		<div class="cand-issues">
			<ul>
				<li><b>Energy:</b> In favor of natural gas drilling (The Marcellus Shale)</li>
				<li><b>Wage:</b> Increase minimum wage to $10.10</li>
				<li><b>Jobs:</b> Rebuild PA manufacturing sector</li>
			</ul>
		</div>
	</div>
</div>
<div class="candidate-box">
	3
</div>
-->