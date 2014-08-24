var express = require('express');
var socket = require('socket.io');
var http = require('http');
var request = require('request');
var phantom = require('node-phantom');
var async = require('async');
var assert = require('assert');


var app=express();
var server = http.createServer(app)
//var io = socket.listen( server );

var fetch_results = false;

/**
*		Here is the structure:  set page.onLoadFinished to be the following algorithm:
*			what page is this loading?  if results page --> do the results;
*										if this is the first time --> enter credentials, do search;
*		Then activate page.open, because it calls page.onLoadFinished when finished
*/



/**
*
*		Define the function for PhantomJS... inputs will be the data grabbed from the user
*			inputs:  county, street, 
*			**CURRENLTY ASSUMES ADDRESS IS PARSED CORRECTLY... WILL DEAL WITH GEOCODING LATER**
**/

function getPollingLoc(master_callback) {
	if('undefined' === typeof master_callback) { 
		master_callback = null;
	} else {
		master_callback = master_callback;
	}
	fetch_results = false;
	phantom.create(function (err,ph) {
		ph.createPage(function (err,page) {
			if(err) console.log(err);
			page.onResourceError = function(errorData) {
				console.log('Unable to load resource (URL:' + errorData.url + ')');
				console.log('Error code: ' + errorData.errorCode + '. Description: ' + errorData.errorString);
			};
			page.onLoadFinished = function(status) {
				console.log('Status: ' + status);
				if(status==='success') {
					page.includeJs('http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', function(err) {
						if(err) console.log(err);
						console.log('looks like JS loaded');
						if(fetch_results) {
							//THIS IS WHERE YOU WILL DO RESULTS SHIT
							console.log("results page shit entered");
							page.render('phantomjs-test2.png');
							ph.exit();
						} else {						
							page.evaluate(function () {
								
								$('input[id=ctl00_ContentPlaceHolder1_CountyList_txtInputBox]').focus();
								$('input[id=ctl00_ContentPlaceHolder1_CountyList_txtInputBox]').val('ERIE');
								$('input[id=ctl00_ContentPlaceHolder1_CountyList_txtInputBox]').keydown();
								$('input[id=ctl00_ContentPlaceHolder1_CountyList_txtInputBox]').keyup();
								$('input[id=ctl00_ContentPlaceHolder1_CountyList_txtInputBox]').blur();
								
								$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').focus();
								$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').val('ERIE');
								$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').keydown();
								$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').keyup();
								$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').blur();
								/*
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

								$('input[id=ctl00_ContentPlaceHolder1_btnSearch]').attr('disabled',false);
								$('input[id=ctl00_ContentPlaceHolder1_btnSearch]').click();
								*/
							}, function(err, result) {
								page.evaluate(function() {
									/*
									$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').focus();
									$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').val('ERIE');
									$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').keydown();
									$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').keyup();
									$('input[id=ctl00_ContentPlaceHolder1_CityList_txtInputBox]').blur
									*/
								}, function(err,results){
									setTimeout(function() {
									console.log("entering here");
									page.render('phantomjs-test2.png');
									ph.exit();
									if(!err) fetch_results = true;
									}, 5000);
								});
							});
						}
					});
					
				} else {
			        console.log(
						"Error opening url \"" + page.reason_url
						+ "\": " + page.reason
					);
					console.log("Connection failed.");
					ph.exit();
				}
			}
			//page.open("https://www.google.com",function (err,status) {});
			page.open("https://www.pavoterservices.state.pa.us/Pages/PollingPlaceInfo.aspx",function (err,status) {});
		});
	}, {parameters:{'ssl-protocol':'any'}});
}

console.log("hello");
getPollingLoc();

//server.listen( 8089 );