/*

Copyright [2015] [Thilina Hasantha (thilina.hasantha[at]gmail.com)]

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

 */

function NotificationManager() {
	this.baseUrl = "";
	this.templates = {};
}

NotificationManager.method('setBaseUrl' , function(url) {
	this.baseUrl = url;
});

NotificationManager.method('setTemplates' , function(data) {
	this.templates = data;
});

NotificationManager.method('setTimeUtils' , function(timeUtils) {
	this.timeUtils = timeUtils;
});

NotificationManager.method('getNotifications' , function(name, data) {
	var that = this;
	$.getJSON(this.baseUrl, {'a':'getNotifications'}, function(data) {
		if(data.status == "SUCCESS"){
			that.renderNotifications(data.data[1],data.data[0]);
		}
	});
});

NotificationManager.method('clearPendingNotifications' , function(name, data) {
	var that = this;
	$.getJSON(this.baseUrl, {'a':'clearNotifications'}, function(data) {
		
	});
});

NotificationManager.method('renderNotifications' , function(notifications, unreadCount) {
	
	if(notifications.length == 0){
		return;
	}
	
	var t = this.templates['notifications'];
	if(unreadCount > 0){
		t = t.replace('#_count_#',unreadCount);
		if(unreadCount > 1){
			t = t.replace('#_header_#',"You have "+unreadCount+" new notifications");
		}else{
			t = t.replace('#_header_#',"You have "+unreadCount+" new notification");
		}
		
	}else{
		t = t.replace('#_count_#',"");
		t = t.replace('#_header_#',"You have no new notifications");
	}
	
	var notificationStr = "";
	
	for (index in notifications){
		notificationStr += this.renderNotification(notifications[index]);
	}
	
	t = t.replace('#_notifications_#',notificationStr);
	
	$obj = $(t);
	
	if(unreadCount == 0){
		$obj.find('.label-danger').remove();
	}
	
	$obj.attr("id","notifications");
	var k = $("#notifications");
	k.replaceWith($obj);
	
	$(".navbar .menu").slimscroll({
        height: "320px",
        alwaysVisible: false,
        size: "3px"
    }).css("width", "100%");
	
	this.timeUtils.convertToRelativeTime($(".notificationTime"));
});


NotificationManager.method('renderNotification' , function(notification) {
	var t = this.templates['notification'];
	t = t.replace('#_image_#',notification.image);
	
	try{
		var json = JSON.parse(notification.action);
		t = t.replace('#_url_#',this.baseUrl.replace('service.php','?')+json['url']);
	}catch(e){
		t = t.replace('#_url_#',"");
	}

	t = t.replace('#_time_#',notification.time);
	t = t.replace('#_fromName_#',notification.type);
	t = t.replace('#_message_#',this.getLineBreakString(notification.message,27));
	return t;
});


NotificationManager.method('getLineBreakString' , function(str, len) {
	var t = "";
	try{
		var arr = str.split(" ");
		var count = 0;
		for(var i=0;i<arr.length;i++){
			count += arr[i].length + 1;
			if(count > len){
				t += arr[i] + "<br/>";
				count = 0;
			}else{
				t += arr[i] + " ";
			}
		}
	}catch(e){}
	return t;
});