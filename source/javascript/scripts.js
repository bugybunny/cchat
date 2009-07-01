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
	
	xhr.addEvent('failure', function(fails) {
		if(fails == 100) {
			alert('Konnte Chat seit 10 Sekunden nicht mehr erreichen... Bitte prüfe deine Internet-Verbindung.');
		} else if(fails == 300) {
			xhr.fireEvent('logout', {'error': 400, 'logedout': true});
			alert('Konnte leider keine Verbindung mehr seit 30 Sekunden herstellen... Du wurdest darum automatisch ausgeloggt.');
		}
	});
});