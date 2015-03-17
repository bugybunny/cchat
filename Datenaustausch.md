# Einführung #

Der Austausch zwischen dem Frontend und dem Backend geschieht per JSON.

# Details #

## Query ##

Query ist das Senden der Daten von Frontend zu Backend. Es geschieht per POST über die Variable data. Sie wird genutzt, um Eingaben zu senden, Logins durchzuführen oder einfach nur auf neue Nachrichten zu prüfen. Sie erfolgt UTF-8 codiert.

### Beispiel ###
```
{
  "register": {
    "name": "hansmeier",
    "password": "pwd203",
    "email": "hans@meier.com"
  },
  "login": {
    "name": "hansmeier",
    "password": "pwd203"
  },
  "messages": [
    "Hi everybody",
    "Nice to see you",
    "Bye!"
  ],
  "last": 1238549349
}
```

### Erklärungen ###

  * **register**: (optional) Speichert den Benutzer in der Datenbank und ändert den Benutzer auf diese Daten. _name_ und _passwort_ sind Name und Passwort des Benutzers, _email_ die Mailadresse. Benutzer wird von **login** überschrieben, falls es angegeben ist.
  * **login**: (optional) Ändert den Benutzer falls Name und Passwort korrekt. _name_ und _passwort_ entsprechen Namen und Passwort des Benutzers. Falls die Angaben falsch sind wird der Benutzer ausgeloggt und ein errorcode gesendet. Falls nicht angegeben, wird der letzte Benutzer angenommen. Falls der Name "logout" und das Passwort "" lauten, wird der Benutzer ohne Fehlermeldung ausgeloggt.
  * **messages**: (optional) Sendet Nachrichten vom aktuellen Benutzer. Enthält beliebig viele Nachrichten. Falls kein Benutzer eingeloggt ist, werden die Nachrichten verworfen. Falls keine Nachrichten angegeben sind, werden keine verwendet.
  * **last**: (optional) Prüft auf neue Nachrichten ab der angegebenen Milli-Unix-Zeit. Falls nicht angegeben, wird 0 angenommen.

## Answer ##

Answer ist das Senden der Daten von Backend zu Frontend. Sie erfolgt über den Mime-Type _text/json_. Sie wird genutzt, um neue Nachrichten anzuzeigen oder dem Benutzer mitzuteilen, dass er nicht angemeldet ist. Sie erfolgt UTF-8 codiert.

### Beispiel ###
```
{
  "messages": [
    {
      "sender": "Tom",
      "message": "Hi Bruce",
      "time": 1238549978
    },
    {
      "sender": "Peter",
      "message": "Have a nice day",
      "time": 1238552034
    }
  ],
  "user": {
    "login": [
      "hansmeier",
      "Tom"
    ],
    "logout": [
      "Peter"
    ]
  },
  "logedout": true,
  "error": 0
}
```

### Erklärungen ###

  * **messages**: (optional) Zeigt neue Nachrichten ab der gewünschten Zeit in der Query an. Enthält maximal 30 Nachrichten. _sender_ ist der Absender und _message_ die Nachricht. _time_ ist der Sendezeitpunkt in Milli-Unix-Zeit.
  * **user**: (optional) Enthält die Liste der Benutzer, die sich eingeloggt (_login_) oder ausgeloggt (_logout_) haben.
  * **logedout**: (optional) true, wenn der Benutzer noch nicht eingeloggt ist. Falls nicht angegeben, wird "false" angenommen.
  * **error**: (optional) Ob ein Fehler aufgetreten ist. Fehlercodes siehe unten. Falls nicht angegeben, wird 000 angenommen.
    * **000**: kein Fehler
    * **101**: Aktion fehlgeschlagen: Nicht eingeloggt
    * **201**: Login fehlgeschlagen: Benutzer nicht gefunden
    * **202**: Login fehlgeschlagen: Passwort falsch
    * **301**: Registrieren fehlgeschlagen: Benutzer bereits vorhanden
    * **302**: Registrieren fehlgeschlagen: Emailadresse ist ungültig
    * **303**: Registrieren fehlgeschlagen: Es sind nicht alle Felder ausgefüllt
    * **400**: Anfrage fehlgeschlagen / ajax.php nicht gefunden / keine Verbindung
    * **500**: Server- oder unbekannter Fehler