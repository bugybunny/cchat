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