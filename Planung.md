# Einführung #

Dies ist die Planung für das Projekt.

# Anforderungen #

Der Chat _muss_ können:
  * ERLEDIGT: User muss sich zum Lesen und Schreiben registrieren
  * ERLEDIGT: Der Chat muss ein Login haben
  * ERLEDIGT: Wenn ein User etwas schreibt, müssen es die eingeloggten User sehen
  * ERLEDIGT: Vor jeder Nachricht muss der Username des Verfassers stehen
  * ERLEDIGT: Beim Login sieht der User die letzten 30 geschriebenen Nachrichten
  * ERLEDIGT: Muss im Internet Explorer 8, Firefox 3 und Safari 4 funktionieren

Der Chat _sollte_ können:
  * ERLEDIGT: Liste der eingeloggten Benutzer
  * ERLEDIGT: Wenn sich ein User ein- oder ausloggt, erscheint im Chat eine Nachricht "xy hat sich eingeloggt" bzw. "xy hat sich ausgeloggt".
  * Simple Formatierungierungsmöglichkeiten für Nachrichten wie **fett** oder _kursiv_
  * ERLEDIGT: Regelmässig überprüfen ob der Benutzer noch online ist
  * E-Mail Validierung nach Registrierung. Login erfolgt zwar direkt bei der Registrierung, aber neuer Login ist erst nach der Bestätigung der Adresse möglich. Nicht aktivierte Accounts werden nach sieben Tagen gelöscht.
  * "Passwort vergessen" Funktion. Neues Passwort wird nach Bestätigung der Mail per Mail zugeschickt.
  * ERLEDIGT: Alte Nachrichten werden aus Datenschutzgründen gelöscht
  * ERLEDIGT: Fehler werden aufgezeichnet und je nach Konfiguration im Chat ausgegeben
  * ERLEDIGT: Funktioniert im Internet Explorer 6 & 7, Firefox 2 und Safari 3

Der Chat _könnte_ können:
  * Verschiedene Benutzerrechte (kicken, stummschalten, ...)
  * Benutzerfarben
  * ERLEDIGT: Anzeige der Zeit bei einer Nachricht
  * ERLEDIGT: Automatisches Login, wenn die Seite versehentlich neu geladen oder geschlossen wurde

# Abgrenzungen #
Der Chat muss _nicht_ können:
  * Dateien (z.B. Bilder, Musikdateien) an andere User verschicken
  * Bilder und Smileys anzeigen
  * Nachrichten an einzelne User schicken

# Technik #

  * Hintergrundverarbeitung: PHP
  * Anzeige: HTML
  * Dynamische Anzeigen: JavaScript
  * Datenaustausch: JSON
  * Datenspeicherung: MySQL