var Chat = new Class({
	lastrefresh: 0,
	queue: [],
	userlist: {},
	
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
		xhr.addEvent('userlogin', this.userlogin.bind(this));
		xhr.addEvent('userlogout', this.userlogout.bind(this));
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
		$('chatmessages').grab(container);
	},
	
	getMessageTime: function(message) {
		var date = new Date();
		var messageTime = Math.floor(message.time);
		date.setTime(messageTime);
		var hour = date.getHours();
			hour = (hour < 10) ? '0' + hour : hour;
		var minute = date.getMinutes();
			minute = (minute < 10) ? '0' + minute : minute;
		var second = date.getSeconds();
			second = (second < 10) ? '0' + second : second;
		var day = date.getDay();
			day = (day < 10) ? '0' + day : day;
		var month = date.getMonth() + 1;
			month = (month < 10) ? '0' + month : month;
		var year = date.getFullYear();
		return hour + ':' + minute + ':' + second + ', ' + day + '.' + month + '.' + year;
	},
	
	userlogin: function(user) {
		user.each(function(name) {
			if(!this.userlist[name]) {
				this.userlist[name] = new Element('li', {
					'text': name
				});
			}
			$('chatuserlist').grab(this.userlist[name]);
		}, this);
	},
	userlogout: function(user) {
		user.each(function(name) {
			if(this.userlist[name])
				this.userlist[name].dispose();
		}, this);
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