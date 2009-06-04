var Chat = new Class({
	lastrefresh: 0,
	
	initialize: function() {
		$('chatform').addEvent('submit', function(e) {
			e.stop();
			xhr.send({
				'messages': [
					$('chattext').get('value')
				],
				'lastrefresh': this.lastrefresh
			});
		});
		$('chatlogout').addEvent('click', function() {
			xhr.send({
				'login': {
					'name': 'logout',
					'password': ''
				}
			});
		});
	},
	
	refresh: function() {
		xhr.send({
			'lastrefresh': this.lastrefresh
		});
	},
	
	login: function() {
		this.refresh = this.refresh.periodical(100);
	},
	logout: function() {
		$clear(this.refresh);
	}
});