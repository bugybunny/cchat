<?php
	require "config.inc.php";
	
	echo "
<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Strict//EN' 'http://www.w3.org/TR/html4/strict.dtd'>
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UFT-8'>
	<title>cchat $cchat_version</title>
	<script type='text/javascript' src='javascript/mootools-core.js'></script>
	<script type='text/javascript' src='javascript/script.js'></script>
</head>
<body>
	<form id='form' action='ajax.php' method='post'>
		<p>
			<label for='texttext'>Text</label>: <input type='text' length='30' id='texttext' name='texttext'>
			<input id='textsenden' type='button' value='senden'>
		</p>
		<p>
			<label for='loginname'>Login</label>: <input type='text' length='30' id='loginname' name='loginname'>
			<label for='loginpwd'>Passwort</label>: <input type='text' length='30' id='loginpwd' name='loginpwd'>
			<input id='loginsenden' type='button' value='login'> * Passwort in Klartext!
		</p>
		<p>
			<label for='registername'>User</label>: <input type='text' length='30' id='registername' name='registername'>
			<label for='registerpwd'>Passwort</label>: <input type='text' length='30' id='registerpwd' name='registerpwd'>
			<label for='registermail'>Mail</label>: <input type='text' length='30' id='registermail' name='registermail'>
			<input id='registersenden' type='button' value='register'> * Passwort in Klartext!
		</p>
		<p>
			<label for='data'>Antwort</label>:<br>
			<textarea rows='20' cols='50' id='data' name='data'></textarea>
		</p>
		
		<p>
			<input type='button' id='aktualisieren' value='aktualisieren'>
		</p>
	</form>
</body>
</html>";
?>