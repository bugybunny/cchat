# Einführung #
Die Nachrichten und Userdaten werden in einer Datenbank gespeichert.
Die Datenbank cchat hat zwei Tabellen, user und action.

## ERD ##
Das ERD ist unter http://www.imagedose.de/8935/image/ zu sehen

## Tabellen user ##

In dieser Tabelle werden die Userdaten gespeichert, die benötigt werden, damit sich ein User einloggen kann.

**Die Tabelle user braucht einen Datensatz wo user.id 1 ist, sonst kommt es zu Fehlern. Dieser User gibt die Fehler-, Einlog- und Auslogmeldungen aus!
Der User-Datensatz ist in [database.sql](http://code.google.com/p/cchat/source/browse/trunk/install/database.sql) enthalten und wird beim ausführen der Datei angelegt.**
Es ist nicht möglich, sich bei dem User einzuloggen.

| **Feldname** | **Datentyp** | **Beschreibung** |
|:-------------|:-------------|:-----------------|
| id | INTEGER | Eindeutige UserID |
| name | VARCHAR(45) | Username |
| password | VARCHAR(65) | Passwort, welches der User eingegeben hat. SHA256 verschlüsselt und double-salted |
| salt | VARCHAR(50) | Zufällig generierte Zeichenkette die bei der Passwortüberprüfung zur erhöhten Sicherheit miteinbezogen wird. |
| mail | VARCHAR(60) | Mailadresse des Users. Wird benötigt um den Account zu aktivieren und wenn der User sein Passwort vergessen hat |
| register | DATETIME | Zeitpunkt der Userregistrierung |
| activated | DATETIME | Zeitpunkt der Accountaktivierung (per E-Mail) |
| logedin | TINYINT(1) | Status: 0 = false = User nicht ist eingeloggt; 1 = true = User ist eingeloggt |
| lastrefresh | DATETIME | Zeitpunkt, wann der User zuletzt etwas gemacht hat. z.B. die Seite neu geladen, sich eingeloggt oder registriert der eine Nachricht geschrieben hat |

## Tabelle action ##

Die Tabelle action speichert alle Aktionen von Usern. Darunter fallen geschriebene Nachrichten, einloggen und ausloggen.

| **Feldname** | **Datentyp** | **Beschreibung** |
|:-------------|:-------------|:-----------------|
| id | INTEGER | Eindeutige ActionID |
| typ | INTEGER | Aktionstyp: 10 = Nachricht; 20 = Login; 30 = Logout; 40 = Error. Kann bei Bedarf erweitert werden |
| text | VARCHAR(512) | Bei allen Nachrichtentypen wird hier die anzuzeigende Nachricht gespeichert oder ein leerer String, falls nichts angezeigt werden soll. |
| userid | INTEGER | ID des Users, der die Aktion ausgelöst hat |
| time | BIGINT(20)| Zeitpunkt der Aktion |

## MySQL-Dump ##
Der MySQL-Dump der fast leeren Datenbank ist unter http://code.google.com/p/cchat/source/browse/trunk/source/install.sql zu finden. Die Datenbank erhält einen User mit der ID 1 und dem Username System.
Die Datenbank wird erstellt, falls sie noch nicht existiert.