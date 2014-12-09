chrome.contextMenus.onClicked.addListener(function(info, tab) {
	switch(info.menuItemId) {
		case "shortenLink":
			shortenURL(info.linkUrl, tab.id);
			break;
		case "shortenFile":
			shortenURL(info.srcUrl, tab.id);
			break;
	}
});

chrome.runtime.onInstalled.addListener(function() {
	chrome.contextMenus.create({"title": "Shorten link to this file with lob.li", "contexts": ["image", "video", "audio"], "id": "shortenFile"});
	chrome.contextMenus.create({"title": "Shorten link with lob.li", "contexts": ["link"], "id": "shortenLink"});
});

chrome.runtime.onStartup.addListener(function(){
	chrome.contextMenus.create({"title": "Shorten link to this file with lob.li", "contexts": ["image", "video", "audio"], "id": "shortenFile"});
	chrome.contextMenus.create({"title": "Shorten link with lob.li", "contexts": ["link"], "id": "shortenLink"});
});
