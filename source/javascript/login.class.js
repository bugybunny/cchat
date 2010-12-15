/**
 * Klasse für die Funktionalitäten der Login-Seite
 * @author Jannis <jannis@gje.ch>
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
        document.id("loginform").addEvent("submit", function(e) {
            e.stop();
            this.login();
        }.bind(this));
		
        document.id("registertoggle").addEvent("click", function(e) {
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
        var name = document.id("name").get("value");
        var password = document.id("password").get("value");
		
        if(!this.isRegister) {
            xhr.send({
                'login': {
                    'name': name,
                    'password': password
                }
            });
        } else {
            var password2 = document.id("password2").get("value");
            var email = document.id("email").get("value");
			
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
            case 302:
                alert("Mailadresse hat ein ungültiges Format.");
                break;
            case 303:
                alert("Bitte fülle alle Felder aus.");
                break;
        }
    },
	
    /**
     * Blendet die Login-Seite aus und zeigt die Chat-Seite an
     * Wird ausgeführt, wenn der Benutzer eingeloggt wurde
     * Schliesst die Registrierungs-Felder, damit der Benutzer nach dem Logout wieder den Login angezeigt bekommt
     */
    logedin: function() {
        document.id("login").setStyle("display", "none");
        document.id("chat").setStyle("display", "block");
        if(this.isRegister) {
            this.toggle();
        }
    },
	
    /**
     * Blendet die Chat-Seite aus und zeigt die Login-Seite an
     * Wird ausgeführt, wenn der Benutzer ausgeloggt wurde
     */
    logedout: function() {
        document.id("chat").setStyle("display", "none");
        document.id("login").setStyle("display", "block");
    },
	
    /**
     * Zeigt die zusätzlichen Felder für die Registrierung an oder blendet sie aus
     */
    toggle: function() {
        this.isRegister = !this.isRegister;
        var register = document.id("register");
        var registerHeight = register.getScrollSize().y;
		
        if(this.isRegister) {
            register.tween("height", 0, registerHeight);
            document.id("loginsubmit").set("value", "Registrieren");
            document.id("registertoggle").set("text", "Login");
        } else {
            register.tween("height", registerHeight, 0);
            document.id("loginsubmit").set("value", "Login");
            document.id("registertoggle").set("text", "Registrieren");
        }
    }
});