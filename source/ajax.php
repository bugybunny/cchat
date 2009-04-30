<?php
// +----------------------------------------------------------------------------------------------+
// | Projekt       cchat                                                                          |
// | Dateiname     ajax.php                                                                       |
// | Plattform     PHP 5.1 / Apache 2.0                                                           |
// |                                                                                              |
// | Autor         Marco Syfrig (syfm)                                                            |
// | Datum         2009-04-30                                                                     |
// |                                                                                              |
// | Beschreibung   			                                                      |
// |                                                                                              |
// | Version  Datum       Beschreibung                                                  Autor     |
// | -------  ----------  ------------                                                  -----     |
// | V1.00    2009-04-30  erstellt                                                      syfm      |
// |                                                                                              |
// +----------------------------------------------------------------------------------------------+
include 'config.inc.php';

session_start();
header('Content-type: text/json; charset=utf-8');

$data = json_decode($_POST['data'], true);

/* Nachrichten */
$data_answer['messages'][0]['sender'] = "Hans";
$data_answer['messages'][0]['message'] = "Hallo";
$data_answer['messages'][0]['time'] = 	floor(microtime(true) * 1000);
$data_answer['messages'][1]['sender'] = "Rolf";
$data_answer['messages'][1]['message'] = "Hallo Hans";
$data_answer['messages'][1]['time'] = floor(microtime(true) * 1000);

/* User die sich ein- oder ausgeloggt haben in der Zeit seit dem letzten Senden */
$data_answer['login'][0] = $data['login']['name']; 
$data_answer['logout'][0] = "Rolf"; 
$data_answer['logedin'][0] = true;
$data_answer['logedin'][1] = false;
/* echo json_encode($data_answer); */

?>