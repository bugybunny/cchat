<?php
/**
 * Hauptseite des Chats (Login, Registrierung, Chat)
 * @author Jannis <jannis@gmx.ch>
 */

include_once "config.inc.php";

echo "
<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Strict//EN' 'http://www.w3.org/TR/html4/strict.dtd'>
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UFT-8'>
	<title>cchat $cchat_version</title>
	<link rel='stylesheet' type='text/css' href='css/style.css'>
	<script type='text/javascript' src='javascript/mootools-core.js'></script>
	<script type='text/javascript' src='javascript/xhr.class.js'></script>
	<script type='text/javascript' src='javascript/login.class.js'></script>
	<script type='text/javascript' src='javascript/scripts.js'></script>
</head>
<body>
	<noscript>You need JavaScript to access cchat.</noscript>
	<div id='page'>
		<div id='login'>
			<form id='loginform' action='' method='post'>
				<label for='name'>Name</label>: <input id='name' type='text' name='name'><br>
				<label for='name'>Passwort</label>: <input id='password' type='password' name='password'><br>
				<div id='register'>
					<label for='name'>Wiederholen</label>: <input id='password2' type='password' name='password2'><br>
					<label for='name'>E-Mail</label>: <input id='email' type='text' name='email'><br>
				</div>
				<input id='loginsubmit' type='submit' value='Login'> <a id='registertoggle' href=''>Registrieren</a>
			</form>
		</div>
		<div id='chat'>
			CHAT
		</div>
	</div>
</body>
</html>
";