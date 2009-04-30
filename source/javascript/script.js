document.addEvent('domready', function() {
	var ajax = new Request({
		'url': 'ajax.php',
		'data': '{}',
		'link': 'chain',
		'noCache': true
	});
	ajax.addEvent('success', function(text) {
		$('data').set('value', text);
	});
	$('form').addEvent('submit', function() {
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