<?php
session_start();

if($_SESSION['login'] == ""){
	header("Location: https://".$_SERVER['HTTP_HOST']);
	exit();
}

include ('db.php');
// Define what kind of operation is needed
defined('OPERATION_TYPE') or define('OPERATION_TYPE', 'entry');


include('common/header_new.php');
include_once('operation.php');

use Object\Tool AS Tool;
//Tool::dump($record);


if((empty($record_found)) || $record_found==""){
    include 'no_task.php';
} else {
    include 'task.php';
}

?>