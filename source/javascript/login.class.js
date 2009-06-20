/**
 * Klasse für die Funktionalitäten der Login-Seite
 */
var Login = new Class({
	/**
	 * Ob der Benutzer sich registrieren will, also das Registrieren-Formular geöffnet ist
	 * @type Boolean
	 */
	isRegister: false,
	
	/**
	 * Initialisiert die Login-Funktionalitäten und fügt die Formular-Ereignisse hinzu
	 * @constructor
	 */
	initialize: function() {
		$("login").addEvent("submit", function(e) {
			e.stop();
			this.login();
		}.bind(this));
		
		$("registertoggle").addEvent("click", function(e) {
			e.stop();
			this.toggle();
		}.bind(this));
		
		xhr.addEvent("error", this.error.bind(this));
		xhr.addEvent("login", this.logedin.bind(this));
		xhr.addEvent("logout", this.logedout.bind(this));
	},
	
	/**
	 * Wird ausgeführt, wenn das Login- oder Registrierungsformular abgesendet wurde
	 * Prüft beim Registrieren, ob die Passwörter übereinstimmen und sendet die Daten an den Server
	 */
	login: function() {
		var name = $("name").get("value");
		var password = $("password").get("value");
		
		if(!this.isRegister) {
			xhr.send({
				'login': {
					'name': name,
					'password': password
				}
			});
		} else {
			var password2 = $("password2").get("value");
			var email = $("email").get("value");
			
			if(password != password2) {
				alert("Die Passwörter stimmen nicht überein!");
			} else {
				xhr.send({
					'register': {
						'name': name,
						'password': password,
						'email': email
					}
				});
			}
		}
	},
	
	/**
	 * Gibt die richtige Meldung aus, wenn ein Fehler aufgetreten ist
	 * @param Number code Fehlercode (siehe http://code.google.com/p/cchat/wiki/Datenaustausch ganz unten)
	 */
	error: function(code) {
		switch(code) {
			case 201:
				alert("Benutzer nicht gefunden.");
				break;
			case 202:
				alert("Passwort falsch.");
				break;
			case 301:
				alert("Benutzername ist bereits vergeben. Bitte einen anderen wählen.");
				break;
		}
	},
	
	/**
	 * Blendet die Login-Seite aus und zeigt die Chat-Seite an
	 * Wird ausgeführt, wenn der Benutzer eingeloggt wurde
	 */
	logedin: function() {
		$("login").setStyle("display", "none");
		$("chat").setStyle("display", "block");
	},
	
	/**
	 * Blendet die Chat-Seite aus und zeigt die Login-Seite an
	 * Wird ausgeführt, wenn der Benutzer ausgeloggt wurde
	 */
	logedout: function() {
		$("chat").setStyle("display", "none");
		$("login").setStyle("display", "block");
	},
	
	/**
	 * Zeigt die zusätzlichen Felder für die Registrierung an oder blendet sie aus
	 */
	toggle: function() {
		this.isRegister = !this.isRegister;
		var register = $("register");
		var registerHeight = register.getScrollSize().y;
		
		if(this.isRegister) {
			register.tween("height", 0, registerHeight);
			$("loginsubmit").set("value", "Registrieren");
			$("registertoggle").set("text", "Login");
		} else {
			register.tween("height", registerHeight, 0);
			$("loginsubmit").set("value", "Login");
			$("registertoggle").set("text", "Registrieren");
		}
	}
});