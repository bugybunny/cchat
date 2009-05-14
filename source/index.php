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
	<script type='text/javascript' src='javascript/mootools-core.js'></script>
	<script type='text/javascript' src='javascript/script.js'></script>
</head>
<body>
	<div id='login'>
	 "; require "login.inc.php"; echo "
	</div>
	<div id='chat'>
	 "; require "chat.inc.php"; echo "
	</div>
</body>
</html>
";