var xhr, login;

document.addEvent("domready", function() {
	$("page").setStyle("display", "block");
	
	xhr = new XHR();
	login = new Login();
	
	// Default errors
	xhr.addEvent("error", function(code) {
		switch(code) {
			case 400:
				alert("Keine Verbindung zum Chat / Netzwerkstörung...");
				break;
			case 500:
				alert("Es ist ein unbekannter Serverfehler aufgetreten...");
				break;
		}
	});
});