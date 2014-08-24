var async = require('async');
var webPage = require('webpage');
var page = webPage.create();
var system = require('system');
var args = system.args;


var city = args[1];
//console.log("city: [" + city + "]");

var url = 'https://www.pavoterservices.state.pa.us/Pages/PollingPlaceInfo.aspx';
var testindex = 0, loadInProgress = false, loadFailed=false;

var pollingAddress = [];

page.onConsoleMessage = function(msg) {
  //console.log(msg);
};

page.onLoadStarted = function() {
  loadInProgress = true;
  alert('started');
  //console.log("load started");
};

page.onLoadFinished = function() {
  loadInProgress = false;
  alert('finished');
  //console.log("load finished");
};

var steps = [
  function() {
    //Load Login Page
    page.open(url, function(status) {
		if(status==='fail') {
			console.log('Load failed');
			loadFailed=true;
		}
	});
  },
  function() {
    //Enter Credentials
    page.evaluate(function(arg1) {
		//console.log("arg1" + arg1);
		$('input[id=ctl00_ContentPlaceHolder1_CountyList_txtInputBox]').focus();
		$('input[id=ctl00_ContentPlaceHolder1_CountyList_txtInputBox]').val(arg1);
		$('input[id=ctl00_ContentPlaceHolder1_CountyList_txtInputBox]').keydown();
		$('input[id=ctl00_ContentPlaceHolder1_CountyList_txtInputBox]').keyup();
		$('input[id=ctl00_ContentPlaceHolder1_CountyList_txtInputBox]').blur();
		
		$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').focus();
		$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').val('ERIE');
		$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').keydown();
		$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').keyup();
		$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').blur();
		
		$('input[id=ctl00_ContentPlaceHolder1_StreetNameList_txtInputBox]').focus();
		$('input[id=ctl00_ContentPlaceHolder1_StreetNameList_txtInputBox]').val('W 54 ST');
		$('input[id=ctl00_ContentPlaceHolder1_StreetNameList_txtInputBox]').keydown();
		$('input[id=ctl00_ContentPlaceHolder1_StreetNameList_txtInputBox]').keyup();
		$('input[id=ctl00_ContentPlaceHolder1_StreetNameList_txtInputBox]').blur();

		$('input[id=HouseNumber1]').focus();
		$('input[id=HouseNumber1]').val('1142');
		$('input[id=HouseNumber1]').change();
		$('input[id=HouseNumber1]').keydown();
		$('input[id=HouseNumber1]').keyup();
		$('input[id=HouseNumber1]').blur();
		return;
    }, city);
  }, 
  function() {
    //Login
    page.evaluate(function() {
		$('input[id=ctl00_ContentPlaceHolder1_btnSearch]').attr('disabled',false);
		$('input[id=ctl00_ContentPlaceHolder1_btnSearch]').click();
		return;
    });
  }, 
  function() {
    // Output content of page to stdout after form has been submitted
    //page.evaluate(function() {
    //  console.log(document.querySelectorAll('html')[0].outerHTML);
    //});
	pollingAddress = page.evaluate(function() {
		var table = document.getElementById('ctl00_ContentPlaceHolder1_AddressTable');
		var rowLength = table.rows.length;
		var pollingLocData = [];
		
		for(var i=0; i<rowLength; i+=1){
			var row = table.rows[i];
			
			//pollingAddress.push(row);
			var cellLength = row.cells.length;
			for(var j=0; j<cellLength; j+=1){
				var cellText=row.cells[j].innerText;
				if(cellText==='' ) {
					//do nothing;
				} else {
					pollingLocData.push(cellText);
				}
			}		
		}
		
		return pollingLocData;
	});
	//console.log(pollingAddress);
	//page.render('phantomjs-test3.png');
  }
];


interval = setInterval(function() {
  if (!loadInProgress && typeof steps[testindex] == "function" && !loadFailed) {
    //console.log("step " + (testindex + 1));
    steps[testindex]();
    testindex++;
	//clearInterval(interval);
  }
  if (typeof steps[testindex] != "function") {
    //console.log("test complete!");
    console.log(pollingAddress);
	phantom.exit();
  }
}, 50);