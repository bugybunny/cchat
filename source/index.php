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
			<label for='text'>Text</label>: <input type='text' length='30' id='text' name='text'>
			<input type='submit' value='senden'>
		</p>
		<p>
			<label for='data'>Antwort</label>:<br>
			<textarea rows='20' cols='50' id='data' name='data'></textarea>
		</p>
		
		<p>
			<input type='submit' id='aktualisieren' name='aktualisieren' value='aktualisieren'>
		</p>
	</form>
</body>
</html>";
?>