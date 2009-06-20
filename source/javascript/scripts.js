/**
 * Initialisiert all die Chat-Scripts.
 * @author Jannis <jannis@gmx.ch>
 */

var login, chat;
var xhr = new XHR();

document.addEvent("domready", function() {
	$("page").setStyle("display", "block");
	
	login = new Login();
	chat = new Chat();
	
	// leere Anfrage stellen, damit der Benutzer sofort eingeloggt wird, wenn er noch online ist
	xhr.send({});
	
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