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

function showAlert(text){
	var opt ={
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
					copyToClipboard("http://b.lob.li/?"+res);
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

function sendAPIRequest(url, callback){ // Sends a GET request to the server, response is expected to be text and only short id, or resolved link
	var method = "GET";
	var req = new XMLHttpRequest();
	req.open(method, "http://b.lob.li/ch/" + url, true);
	req.onload = function(){
		callback(req);
	};
	req.send();
}