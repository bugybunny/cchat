var Chat = new Class({
	lastrefresh: 0,
	queue: [],
	
	initialize: function() {
		$('chatform').addEvent('submit', function(e) {
			e.stop();
			this.queue.push($('chattext').get('value'));
			$('chattext').set('value', '');
		}.bind(this));
		$('chatlogout').addEvent('click', function() {
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
	},
	
	messages: function(messages) {
		messages.each(function(message) {
			if(this.lastrefresh < message.time)
				this.lastrefresh = message.time;
			this.addMessage(message);
			this.checkOverflow();
		}, this);
	},
	
	addMessage: function(message) {
			var container = new Element('div');
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
			$('chatmessages').grab(container);
	},
	
	checkOverflow: function() {
		var container = $('chatmessages');
		while(container.getSize().y < container.getScrollSize().y) {
			container.getChildren(':first-child')[0].destroy();
		}
	},
	
	refresh: function() {
		if(!xhr.isRunning) {
			xhr.send({
				'messages': this.queue,
				'last': this.lastrefresh
			});
			this.queue.empty();
		}
	},
	
	login: function() {
		this.refreshIntervall = this.refresh.periodical(100, this);
	},
	logout: function() {
		$clear(this.refreshIntervall);
	}
});