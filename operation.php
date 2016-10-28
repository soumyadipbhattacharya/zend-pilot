<?php
// Setting default timezone to kolkata
date_default_timezone_set('Asia/Kolkata');

// Define some constant for application
defined('MINIUM_RECORD') or define('MINIUM_RECORD', 9);

use Object\Tool AS Tool;
use Object\Controller AS Entry;
require_once('Object/Tool.php');
require_once('Object/Controller.php');

// Assume emp id for now
$session = Tool::session();
if (isset($session['login']) && $session['login'] != '') {
    $empID = $session['login'];
    defined('EMPLOYEE_ID') or define('EMPLOYEE_ID', $empID);
} else {
    header("Location: http://".$_SERVER['HTTP_HOST']);
    exit();
}

$record = new Entry($empID);

// Check if data need to add
if (isset($_POST['to_do']) && $_POST['to_do'] == 'add_more') {
    $record->update($_POST);
} elseif (isset($_POST['to_do']) && $_POST['to_do'] == 'qc') {
    $record->update($_POST);
} else if (isset($_POST['new_record']) && $_POST['new_record'] == 'true') {
    $record->nextRecord($_POST);
}

//Tool::dump($record);

$task = $record->task;

// Setting the QC
if (OPERATION_TYPE == 'qc' ) {
    $qc = $record->qc;
}

$tid = 0;
foreach ($task as $value)
{
	if((empty($value->status)) || $value->status=="none"){
		$tid = $value->tid;
		break;
	}
}

$uid = $record->id;
$record_found = $record->id;

// Alert Section
$alert['system'] = $record->message;
$alert['flashMessage'] = Tool::flashMessage();

$alert_msg = '';

if(isset($alert['system']['alert'])) {
    $alert_msg .= $alert['system']['alert'].' ';
}

if(!empty($alert['flashMessage'])) {
    $alert_msg .= $alert['flashMessage'].' ';
}

if(!empty($alert_msg)){
    echo "<script>alert('".$alert_msg."');</script>";
}
// End of alert section
