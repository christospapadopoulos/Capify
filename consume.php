<?php

require __DIR__.'/vendor/autoload.php';

echo "\n********** All employees **********\n";
$url = 'http://localhost:8080/api/employees.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
if (Constants::DEBUG) curl_setopt($ch, CURLOPT_VERBOSE, true);
$response = curl_exec($ch);
curl_close($ch);
var_dump($response);

echo "\n********** Employee by Id **********\n";
//$url = 'http://localhost:8080/api/employees.php?employeeId=1';
//$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_HTTPGET, true);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//if (Constants::DEBUG) curl_setopt($ch, CURLOPT_VERBOSE, true);
//$response = curl_exec($ch);
//curl_close($ch);
//var_dump($response);


//echo "\n********** Delete Employee by Id **********\n";
//$url = 'http://localhost:8080/api/employees.php?employeeId=2';
//$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//if (Constants::DEBUG) curl_setopt($ch, CURLOPT_VERBOSE, true);
//$response = curl_exec($ch);
//curl_close($ch);
//var_dump($response);

//echo "\n********** Add Employee **********\n";
//$data = array(
//    'employeeName'   => "Roger",
//    'employeeSalary' => "20000",
//    'employeeRole'   => "Call Operator",
//    'branchId'       => "1",
//);
//$url = 'http://localhost:8080/api/employees.php';
//$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//if (Constants::DEBUG) curl_setopt($ch, CURLOPT_VERBOSE, true);
//$response = curl_exec($ch);
//curl_close($ch);
//var_dump($response);


//echo "\n********** Edit Employee by Id **********\n";
//$data=array(
//    'employeeName'   => "Roger",
//    'employeeSalary' => "20000",
//    'employeeRole'   => "Call Operator",
//    'branchId'       => "1",
//);
//$url = 'http://localhost:8080/api/employees.php?employeeId=5';
//$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//if (Constants::DEBUG) curl_setopt($ch, CURLOPT_VERBOSE, true);
//$response = curl_exec($ch);
//curl_close($ch);
//var_dump($response);

?>