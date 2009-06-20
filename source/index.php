<?php
/**
 * Hauptseite des Chats (Login, Registrierung, Chat)
 * @author Jannis <jannis@gmx.ch>
 */

include_once "config.inc.php";
header('Content-type: text/html; charset=utf-8');

echo "
<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UFT-8'>
	<title>cchat ".CCHAT_VERSION."</title>
	<link rel='stylesheet' type='text/css' href='css/style.css'>
	<script type='text/javascript' src='javascript/mootools-core.js'></script>
	<script type='text/javascript' src='javascript/xhr.class.js'></script>
	<script type='text/javascript' src='javascript/login.class.js'></script>
	<script type='text/javascript' src='javascript/chat.class.js'></script>
	<script type='text/javascript' src='javascript/scripts.js'></script>
</head>
<body>
	<noscript><p>You need JavaScript to access cchat.</p></noscript>
	<div id='page'>
		<form id='login' action='' method='post'>
			<div>
				<label for='name'>Name</label>: <input id='name' type='text' name='name'><br>
				<label for='name'>Passwort</label>: <input id='password' type='password' name='password'>
			</div>
			<div id='register'>
				<label for='name'>Wiederholen</label>: <input id='password2' type='password' name='password2'><br>
				<label for='name'>E-Mail</label>: <input id='email' type='text' name='email'><br>
			</div>
			<div>
				<input id='loginsubmit' type='submit' value='Login'> <a id='registertoggle' href=''>Registrieren</a>
			</div>
		</form>
		<div id='chat'>
			<div id='chatmessages'></div>
			<div id='chatuser'>
				<p>User online:</p>
				<ul id='chatuserlist'></ul>
			</div>
			<form id='chatform' action='' method='post'>
				<p>
					<input id='chattext' type='text' name='text' size='100'>
					<input type='submit' value='Go'>
					<input id='chatlogout' type='button' name='logout' value='Logout'>
				</p>
			</form>
		</div>
	</div>
</body>
</html>
";