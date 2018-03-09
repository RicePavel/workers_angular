<?php

include_once 'functions.php';

$name = $_REQUEST['name'];
$age = $_REQUEST['age'];
$salary = $_REQUEST['salary'];

$conn = getDbConnection();
$errorStr = '';
$id = 0;
$ok = addWorker($conn, $name, $age, $salary, $errorStr, $id);

$response = ['ok' => $ok, 'errorStr' => $errorStr, 'id' => $id, 'name' => $name, 'age' => $age, 'salary' => $salary];

echo(json_encode($response));

?>

