/**
 * Klasse für Datenaustausch mit Server
 * Funktioniert über asynchrone XmlHttpRequests und JSON im Hintergrund über POST
 * Siehe http://code.google.com/p/cchat/wiki/Datenaustausch
 * @author Jannis <jannis@gmx.ch>
 */
var XHR = new Class({
	Implements: Events,
	
	/**
	 * Ob der User bereits eingeloggt ist
	 * @type Boolean
	 */
	logedin: false,
	/**
	 * Zähler für die fehlgeschlagenen Requests
	 * @type Number
	 */
	failurecounter: 0,
	
	/**
	 * Initialisiert die Klasse und fügt die Ereignisse hinzu
	 * @constructor
	 */
	initialize: function() {
		this.request = this.getRequest();
	},
	
	/**
	 * Prüft, ob Nachrichten empfangen wurden und führt das entsprechende Ereignis aus
	 * @param Object response Server-Antwort auf die Anfrage
	 */
	messages: function(response) {
		if(response.messages && response.messages.length) {
			this.fireEvent("messages", [response.messages, response]);
		}
	},
	/**
	 * Prüft, ob sich User ein- oder ausgeloggt haben und führt das entsprechende Ereignis aus
	 * @param Object response Server-Antwort auf die Anfrage
	 */
	user: function(response) {
		if(response.user) {
			if(response.user.login && response.user.login.length) {
				this.fireEvent("userlogin", [response.user.login, response]);
			}
			if(response.user.logout && response.user.logout.length) {
				this.fireEvent("userlogout", [response.user.logout, response]);
			}
		}
	},
	/**
	 * Prüft, ob sich der Benutzer ein- oder ausgeloggt hat und führt das entsprechende Ereignis aus
	 * @param Object response Server-Antwort auf die Anfrage
	 */
	login: function(response) {
		if(this.logedin && response.logedout) {
			this.logedin = false;
			this.fireEvent("logout", response);
		} else if(!this.logedin && !response.logedout) {
			this.logedin = true;
			this.fireEvent("login", response);
		}
	},
	/**
	 * Prüft, ob ein Fehler aufgetreten ist und führt das entsprechende Ereignis aus
	 * @param Object response Server-Antwort auf die Anfrage
	 */
	error: function(response) {
		if(response.error && response.error != 0) {
			this.fireEvent("error", [response.error, response]);
			this.fireEvent("error"+response.error, response);
		}
	},
	
	/**
	 * Zählt die Anzahl fehlgeschlagener Versuche und führt pro 100 ein entsprechendes Event aus
	 * @param Boolean failed Ob die Anfrage fehlgeschlagen ist
	 */
	failure: function(failed) {
		if(failed) {
			this.error({'error': 400});
			this.failurecounter++;
			if(this.failurecounter % 100 == 0) {
				this.fireEvent("failure", this.failurecounter);
				this.fireEvent("failure"+this.failurecounter);
			}
		} else if(this.failurecounter > 0) {
			this.failurecounter = 0;
			this.fireEvent("failure", 0);
			this.fireEvent("failure0");
		}
	},

	/**
	 * Sendet eine Anfrage an den Server
	 * @param Object data Daten, die an den Server gesendet werden sollen
	 */
	send: function(data) {
		data = JSON.encode(data);
		this.getRequest().send({'data': {'data': data}});
	},
	
	/**
	 * Gibt ein Request-Objekt zurück
	 * falls es existiert und nicht bereits eine Anfrage stellt, ist dies die Standard-Request this.request,
	 * ansonsten ein neu erstelltes.
	 * @return Request.JSON
	 */
	getRequest: function() {
		if(this.request && !this.request.running) {
			return this.request;
		} else {
			var request = new Request.JSON({
				'url': 'ajax.php',
				'link': 'chain'
			});
			request.addEvent('success', function(response) {
				this.failure(false);
				this.messages(response);
				this.user(response);
				this.login(response);
				this.error(response);
			}.bind(this));
			request.addEvent('failure', function() {
				this.failure(true);
			}.bind(this));
			return request;
		}
	}
});