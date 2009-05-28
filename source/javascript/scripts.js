var XHR = new Class({
	Implements: Events,
	
	logedin: false,
	
	request: new Request.JSON({
		'url': 'ajax.php',
		'link': 'chain',
		'success': function(response) {
			this.messages(response),
			this.user(response),
			this.login(response),
			this.error(response)
		}.bind(this),
		'failure': function() {
			this.error({'error': 400});
		}.bind(this)
	}),
	
	messages: function(response) {
		if(response.messages && response.messages.length) {
			this.fireEvent("messages", [response.messages, response]);
		}
	},
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
	login: function(response) {
		if(this.logedin && request.logedout) {
			this.fireEvent("logout", response);
		} else if(!this.logedin && !request.logedout) {
			this.fireEvent("login", response);
		}
	},
	error: function(response) {
		if(response.error) {
			this.fireEvent("error", [response.error, response]);
			this.fireEvent("error"+response.error, response);
		}
	},

	send: function(data) {
		this.request.send({'data': {'data': data}});
		return this.request;
	},
	load: function() {
		this.send({});
	}
});

var Login = new Class({
	isRegister: false,
	
	initialize: function() {
		$("loginform").addEvent("submit", function(e) {
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
	
	login: function() {
		var name = $("name").get("value");
		var password = $("password").get("value");
		
		if(!this.isRegister) {
			xhr.send({
				'name': name,
				'password': password
			});
		} else {
			var password2 = $("password2").get("value");
			var email = $("email").get("value");
			
			if(password != password2) {
				alert("Die Passwörter stimmen nicht überein!");
			} else {
				xhr.send({
					'name': name,
					'password': password,
					'email': email
				});
			}
		}
	},
	
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
	
	logedin: function() {
		alert("Eingeloggt!");
	},
	
	logedout: function() {
		alert("Ausgeloggt!");
	},
	
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