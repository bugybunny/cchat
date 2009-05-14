document.addEvent('domready', function() {
	var ajax = new Request.JSON({
		'url': '../source/ajax.php',
		'data': {'data': '{}'},
		'link': 'chain'
	});
	ajax.addEvent('success', function(json, text) {
		$('data').set('value', text);
	});
	$('form').addEvent('submit', function(event) {
		event.stop();
	});
	$('textsenden').addEvent('click', function() {
		var data = JSON.encode({
			"messages": [
				$('texttext').get('value')
			]
		});
		ajax.send({'data': {'data': data}});
		$('texttext').set('value', '');
	});
	$('loginsenden').addEvent('click', function() {
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
	$('registersenden').addEvent('click', function() {
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
	$('aktualisieren').addEvent('click', function() {
		ajax.send();
	});
});