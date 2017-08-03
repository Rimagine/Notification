$(document).ready(function() {
	checknotif();
	// setInterval(function(){ checknotif(); }, 1000);
	for (i = 0; i < 5; i++) { 
    checknotif();
	}
});
function checknotif() {
	if (!Notification) {
		$('body').append('<h4 style="color:red">*Browser does not support Web Notification</h4>');
		return;
	}
	if (Notification.permission !== "granted"){
		Notification.requestPermission();
		console.log("requested Permission");
	}else {
		console.log("granted");
		 // var notification = new Notification('Notification title', {
		 //      icon: 'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
		 //      body: "Hey there! You've been notified!",
		 //    });

		$.ajax(
		{
			url : "ajax.php",
			type: "POST",
			success: function(data, textStatus, jqXHR)
			{
				var data = jQuery.parseJSON(data);
				console.log(data);
				if(data.result == true){
					console.log("data true");
					var data_notif = data.notif;
					
					for (var i = data_notif.length - 1; i >= 0; i--) {
						var theurl = data_notif[i]['url'];
						var notifikasi = new Notification(data_notif[i]['title'], {
							icon: data_notif[i]['icon'],
							body: data_notif[i]['msg'],
						});
						notifikasi.onclick = function () {
							window.open(theurl); 
							notifikasi.close();     
						};
						setTimeout(function(){
							notifikasi.close();
						}, 5000);
					};
				}else{
					console.log("data false");
				}
			},
			error: function(jqXHR, textStatus, errorThrown)
			{

			}
		});	

	}
};