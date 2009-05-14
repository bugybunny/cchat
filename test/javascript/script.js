document.addEvent('domready', function() {
	var ajax = new Request.JSON({
		'url': '../source/ajax.php',
		'data': {'data': '{}'},
		'link': 'chain'
	});
	ajax.addEvent('success', function(json, text) {
		$('data').set('value', text);
	});
	$('text').addEvent('submit', function(e) {
		e.stop();
		var data = JSON.encode({
			"messages": [
				$('texttext').get('value')
			]
		});
		ajax.send({'data': {'data': data}});
		$('texttext').set('value', '');
	});
	$('login').addEvent('submit', function(e) {
		e.stop();
		var data = JSON.encode({
			"login": {
				"name": $('loginname').get('value'),
				"password": $('loginpwd').get('value')
			}
		});
		ajax.send({'data': {'data': data}});
		$('loginname').set('value', '');
		$('loginpwd').set('value', '');
	});
	$('register').addEvent('submit', function(e) {
		e.stop();
		var data = JSON.encode({
			"register": {
				"name": $('registername').get('value'),
				"password": $('registerpwd').get('value'),
				"email": $('registermail').get('value')
			}
		});
		ajax.send({'data': {'data': data}});
		$('registername').set('value', '');
		$('registerpwd').set('value', '');
		$('registermail').set('value', '');
	});
	$('answer').addEvent('submit', function(e) {
		e.stop();
		ajax.send();
	});
});