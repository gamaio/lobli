chrome.commands.onCommand.addListener(function(command){ // Keyboard shortcut trigger - Shorten current tab
	if(command == "shortenTab"){
		chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
			var current = tabs[0]
			shortenTabURL(current.id);
		});
	}
});

chrome.browserAction.onClicked.addListener(function(tab){ // Shorten current tab when lobli icon pressed
	chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
		var current = tabs[0]
		shortenTabURL(current.id);
	});
});

/* // Incomplete functions
chrome.runtime.onInstalled.addLIstener(function(data){ // Get a new API key from the get go
	if(data.reason == "install"){
		getNewAPIKey();
	}else if(data.reason == "update"){
		testAPIKey();
	}
});

chrome.runtime.onStartup.addListener(function(){ // Check to see if extension is disabled on startup
	var data = getData("lobliAPIKey");
	if(data != undefined && data == true){
		testAPIKey();
	}
});
*/

function showAlert(text){
	var opt = {
		type: "basic",
		title: "lob.li Chrome",
		message: text,
		iconUrl: "../icons/lobli-128.png"
	}
	chrome.notifications.create('lobli-cr', opt, function(id){});
}

function copyToClipboard(text){
	var clipboard = document.getElementById("clipboard");
	clipboard.value = text;
	clipboard.focus();
	clipboard.select();
	document.execCommand("copy");
}

function testURL(s) { // Stolen from http://stackoverflow.com/a/17726973 since I suck at RegExp - Returns true(bool) on good looking link
    var regexp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    return regexp.test(s);    
}

function shortenTabURL(tabid){ // Use just a tab id to shorten its url
	chrome.tabs.get(tabid, function(tab){
		shortenURL(tab.url);
	});
}

function shortenURL(url){ // Creates a short url and copies it to clipboard
	if(testURL(url)){
		var url = encodeURIComponent(url);
		sendAPIRequest("?shorten&url=" + url, function(req){
			var res = req.responseText.trim();
			switch(res){
				case "dead":
					showAlert("Apparently the link is dead...");
					break;
				case "db": 
					showAlert("I got a database error!");
					break;
				case "Error":
					showAlert("General Error.");
					break;
				default:
					copyToClipboard("http://lob.li/"+res);
					showAlert("Link shortened. Short link copied to clipboard!");
					break;
			}
		});
	}
}

function resolveURL(url){ // For when/if I decide to add the ability to resolve links through the extension
	if(testURL(url)){
		sendAPIRequest("?resolve&url=" + url, function(req){
			var res = req.responseText.trim();
			copyToClipboard(res);
			showAlert("Link Resolved!\n" + res);
		});
	}
}

function linkStats(url){ // Get stats to that specific link (context menu?)
	if(testURL(url)){
		sendAPIRequest("?stats&url=" + url, function(req){
			var res = req.responseText.trim();
			// format this info and make a popup window
		});
	}
}

/* // These functions are also incomplete
function testAPIKey(){ // Compares local key to server
	var key = getData("lobliAPIKey");
	if(key != undefined){
		sendAPIRequest("?testKey&key=" + key, function(req){
			var res = req.responseText.trim();
			var disabled = getData("lobli-disabled");
			if(res == "Invalid API Key"){ // Misformatted or other, try to get a new one
				getNewAPIKey();
			}else if(res == "Blacklisted client"){
				if(disabled != true){
					chrome.storage.sync.set({ "lobli-disabled": true });
				}
				showAlert("For some reason or another, your extension has been disabled.\nFor more info, please email c0de@unps,us");
			}else if(res = "OKAY"){
				chrome.storage.sync.set({ "lobli-disabled": false });
			}
		});
	}else{
		getNewAPIKey();
	}
}

function getNewAPIKey(){ // Tries to get a new API key from the server
	var manifest = chrome.runtime.getManifest();
	sendAPIRequest("?newAPIKey&c=loblichrome&v=" + manifest.version, function(req){
		var res = req.responseText.trim();
		if(res == "Blacklisted client"){ // This client was blacklisted for some reason. Lets disable parts of the extension
			chrome.browserAction.setIcon({ path: "../icons/lobli-19-disabled.png" });
			chrome.storage.sync.set({ "lobli-disabled": true });
			showAlert("D: Your client has been blacklisted!\nIf this was a mistake, please email c0de@unps.us");
		}else{
			chrome.storage.sync.set({ "lobliAPIKey": res, "lobli-disabled": false });
		}
	});
}

function getData(key){
	chrome.storage.sync.get(key, function(data){
		return data;
	});
}
*/

function sendAPIRequest(url, callback){ // Sends a GET request to the server, response is expected to be text and only short id, or resolved link
	var method = "GET";
	var req = new XMLHttpRequest();
	req.open(method, "http://api.lob.li/" + url, true);
	req.onload = function(){
		callback(req);
	};
	req.send();
}