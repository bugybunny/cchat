<?php
	require "config.inc.php";
	require "index.html";
	
	echo "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Strict//EN' 'http://www.w3.org/TR/html4/strict.dtd'>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UFT-8'>
<title>cchat $cchat_version</title>
</head>
<body>
	<form action='ajax.php' method='post'>
		<textarea rows='20' cols='50' name='data'>{
  'register': {
    'name': 'hansmeier',
    'password': 'pwd203',
    'email': 'hans@meier.com'
  },
  'login': {
    'name': 'hansmeier',
    'password': 'pwd203'
  },
  'messages': [
    'Hi everybody',
    'Nice to see you',
    'Bye!'
  ],
  'last': 1238549349
}</textarea><br>
		<input type='submit' value='testen'>
	</form>
</body>
</html>";
?>