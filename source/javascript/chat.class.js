/**
 * Klasse für die Chat-Funktionen (nach Login)
 * @author Jannis <jannis@gje.ch>
 */
var Chat = new Class({
    /**
     * Millisekundenzeit der letzten Empfangenen Nachricht
     *  @typ Number
     */
    lastrefresh: 0,
    /**
     * Nachrichten-Schlange für ungesendete Nachrichten
     * Enthält Strings
     * @type Array
     */
    queue: [],
    /**
     * Liste der eingeloggten Benutzer und ihre HTML-Elemente in der Onlineliste im Chat
     * Attribute im Objekt sind die Benutzernamen (Strings) und Werte die <li>-Elemente für die Userliste
     * @type Array
     */
    userlist: {},
    /**
     * Speichert alle empfangenen Nachrichtenelemente mit ihrer Uhrzeit als Schlüssel
     * @type Object
     */
    messageElements: {},
	
    /**
     * Initialisiert die Chat-Funktionalitäten und fügt die Sende- und Empfangs-Ereignisse hinzu
     * @constructor
     */
    initialize: function() {
        document.id('chatform').addEvent('submit', function(e) {
            e.stop();
            this.queue.push(document.id('chattext').get('value'));
            document.id('chattext').set('value', '');
        }.bind(this));
        document.id('chatlogout').addEvent('click', function() {
            xhr.send({
                'login': {
                    'name': 'logout',
                    'password': ''
                }
            });
        });
		
        xhr.addEvent('login', this.login.bind(this));
        xhr.addEvent('logout', this.logout.bind(this));
        xhr.addEvent('messages', this.messages.bind(this));
        xhr.addEvent('userlogin', this.userlogin.bind(this));
        xhr.addEvent('userlogout', this.userlogout.bind(this));
    },
	
    /**
     * Wenn eine Nachricht empfangen wurde
     * Aktualisert die Zeit der letzten Nachricht und zeigt sie an
     * @param Array messages Neu Empfangene Nachrichten
     */
    messages: function(messages) {
        messages.each(function(message) {
            if(!this.messageElements[message.time] ||
                (this.messageElements[message.time].getChildren()[0].get('text') == message.sender &&
                    this.messageElements[message.time].getChildren()[1].get('text') == message.message)) {
                if(this.lastrefresh < message.time)
                    this.lastrefresh = message.time;
                this.addMessage(message);
                this.checkOverflow();
            }
        }, this);
    },
	
    /**
     * Zeigt eine Nachricht mit Absender, Zeitpunkt und Text auf dem Bildschirm an
     * @param Object message Eine Nachricht
     */
    addMessage: function(message) {
        var container = new Element('div', {
            'title': this.getMessageTime(message)
        });
        var sender = new Element('span', {
            'text': message.sender + ': ',
            'class': 'messagesender'
        });
        var text = new Element('span', {
            'text': message.message,
            'class': 'messagetext'
        });
        container.grab(sender);
        container.grab(text);
        this.messageElements[message.time] = container;
        document.id('chatmessages').grab(container);
    },
	
    /**
     * Liefert den anzuzeigenden Zeitpunkt (als lesbaren String) einer Nachricht anhand der Microsekundenzeit zurück
     * @param Object message Nachricht, für die der Zeitpunkt zurückgegeben werden soll
     * @return String Sendezeitpunkt im Format "H:i:s, d-m-Y"
     * @type String
     */
    getMessageTime: function(message) {
        var date = new Date();
        date.setTime(message.time);
        var hour = date.getHours();
        hour = (hour < 10) ? '0' + hour : hour;
        var minute = date.getMinutes();
        minute = (minute < 10) ? '0' + minute : minute;
        var second = date.getSeconds();
        second = (second < 10) ? '0' + second : second;
        var day = date.getDate();
        day = (day < 10) ? '0' + day : day;
        var month = date.getMonth() + 1;
        month = (month < 10) ? '0' + month : month;
        var year = date.getFullYear();
        return hour + ':' + minute + ':' + second + ', ' + day + '.' + month + '.' + year;
    },
	
    /**
     * Zeigt einen User in der Userliste an
     * Erzeugt das Listenelement für einen neu eingeloggten User, falls es noch nicht erzeugt wurde
     * und bindet es in die Userliste ein
     * @param Array user Neu eingeloggte Usernamen (Strings)
     */
    userlogin: function(user) {
        user.each(function(name) {
            if(!this.userlist[name]) {
                this.userlist[name] = new Element('li', {
                    'text': name
                });
            }
            document.id('chatuserlist').grab(this.userlist[name]);
        }, this);
    },
    /**
     * Entfernt einen User wieder aus der Userliste
     * Blendet das Listenelement aus
     */
    userlogout: function(user) {
        user.each(function(name) {
            if(this.userlist[name])
                this.userlist[name].dispose();
        }, this);
    },
	
    /**
     * Prüft, ob die Nachrichten aus dem Bildschirm hinauslaufen und entfernt so viele, bis es wieder passt
     */
    checkOverflow: function() {
        var container = document.id('chatmessages');
        while(container.getSize().y > window.getSize().y - 40 || container.getSize().y > 0 && container.getSize().y < container.getScrollSize().y) {
            container.getChildren(':first-child')[0].destroy();
        }
    },
	
    /**
     * Lädt neue Nachrichten vom Server und sendet neu geschriebene mit
     */
    refresh: function() {
        var data = {};
        var important = false;
        data.last = this.lastrefresh;
        if(this.queue.length) {
            data.messages = this.queue;
            important = true;
        }
        xhr.send(data, important);
        this.queue.empty();
    },
	
    /**
     * Ruft regelmässig die Refresh-Methode auf
     * @see Chat.refresh()
     */
    login: function() {
        this.refreshIntervall = this.refresh.periodical(100, this);
        this.checkOverflow.delay(100);
    },
    /**
     * Entfernt das regelmässige Aufrufen der Refresh-Methode und leert die Userliste
     */
    logout: function() {
        clearTimeout(this.refreshIntervall);
        this.messageElements = {};
        document.id("chatmessages").empty();
        this.userlist = {};
        document.id("chatuserlist").empty();
        this.lastrefresh = 0;
    }
});