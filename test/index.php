<?php
	require "../source/config.inc.php";
	
	echo "
<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Strict//EN' 'http://www.w3.org/TR/html4/strict.dtd'>
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UFT-8'>
	<title>cchat $cchat_version</title>
	<script type='text/javascript' src='../source/javascript/mootools-core.js'></script>
	<script type='text/javascript' src='javascript/script.js'></script>
</head>
<body>
	<form id='text' action='ajax.php' method='post'>
		<p>
			<label for='texttext'>Text</label>: <input type='text' length='30' id='texttext' name='texttext'>
			<input type='submit' value='senden'>
		</p>
	</form>
	<form id='login' action='ajax.php' method='post'>
		<p>
			<label for='loginname'>Login</label>: <input type='text' length='30' id='loginname' name='loginname'>
			<label for='loginpwd'>Passwort</label>: <input type='password' length='30' id='loginpwd' name='loginpwd'>
			<input type='submit' value='login'>
		</p>
	</form>
	<form id='register' action='ajax.php' method='post'>
		<p>
			<label for='registername'>User</label>: <input type='text' length='30' id='registername' name='registername'>
			<label for='registerpwd'>Passwort</label>: <input type='password' length='30' id='registerpwd' name='registerpwd'>
			<label for='registermail'>Mail</label>: <input type='text' length='30' id='registermail' name='registermail'>
			<input type='submit' value='register'>
		</p>
	</form>
	<form id='answer' action='ajax.php' method='post'>
		<p>
			<input type='submit' value='aktualisieren'>
		</p>
		
		<p>
			<label for='data'>Antwort</label>:<br>
			<textarea rows='20' cols='50' id='data' name='data'></textarea>
		</p>
	</form>
</body>
</html>";