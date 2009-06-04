var XHR = new Class({
	Implements: Events,
	
	logedin: false,
	
	initialize: function() {
		this.request = new Request.JSON({
			'url': 'ajax.php',
			'link': 'chain'
		});
		this.request.addEvent('success', function(response) {
			this.messages(response),
			this.user(response),
			this.login(response),
			this.error(response)
		}.bind(this));
		this.request.addEvent('failure', function() {
			this.error({'error': 400});
		}.bind(this));
	},
	
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
		if(this.logedin && response.logedout) {
			this.fireEvent("logout", response);
		} else if(!this.logedin && !response.logedout) {
			this.fireEvent("login", response);
		}
	},
	error: function(response) {
		if(response.error && response.error != 0) {
			this.fireEvent("error", [response.error, response]);
			this.fireEvent("error"+response.error, response);
		}
	},

	send: function(data) {
		data = JSON.encode(data);
		this.request.send({'data': {'data': data}});
		return this.request;
	},
	load: function() {
		this.send({});
	}
});