document.addEvent('domready', function() {
	var ajax = new Request.JSON({
		'url': 'ajax.php',
		'data': '{}',
		'link': 'chain',
		'noCache': true
	});
	ajax.addEvent('success', function(json, text) {
		$('data').set('value', text);
	});
	$('form').addEvent('submit', function(event) {
		event.stop();
		var data = {
			"login": {
				"name": "hans",
				"password": "maier"
			},
			"messages": [
				$('text').get('value')
			]
		};
		ajax.send({'data': data});
	});
});