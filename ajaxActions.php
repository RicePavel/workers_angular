<?php

include_once 'functions.php';

$action = $_REQUEST['action'];
$conn = getDbConnection();

if ($action == 'change') {
    $name = $_REQUEST['name'];
    $age = $_REQUEST['age'];
    $salary = $_REQUEST['salary'];
    $id = $_REQUEST['id'];
    $errorString = '';
    $ok = changeWorker($conn, $id, $name, $age, $salary, $errorString);
    $response = ['ok' => $ok, 'id' => $id, 'name' => $name, 'salary' => $salary, 'age' => $age, 'errorString' => $errorString];
    echo(json_encode($response));
} else if ($action == 'delete') {
    $id = $_REQUEST['id'];
    $errorArray = [];
    $ok = deleteWorker($conn, $id, $errorArray);
    $errorString = '';
    if (!$ok) {
        $errorString = $errorArray[2];
    }
    $response = ['ok' => $ok, 'errorString' => $errorString];
    echo(json_encode($response));
} else if ($action == 'getList') {
    $salary = trim($_REQUEST['salary']);
    $filter = true;
    if ($salary == '') {
        $filter = false;
    }
    $stmt = getWorkersListStatement($conn, $filter, $salary);
    $response = [];
    while ($row = $stmt->fetch()) {
        $response[] = ['id' => $row['id'], 'name' => $row['name'], 'salary' => $row['salary'], 'age' => $row['age']];
    }
    echo(json_encode($response));
} else if ($action == 'multipleDelete') {
    $idArray = $_REQUEST['id'];
    $source = $_REQUEST['source'];
    if ($source == 'angularApp') {
        $idArray = explode(',', $idArray);
    }
    $errorString = '';
    $ok = deleteSeveralWorkers($conn, $idArray, $errorString);
    $response = ['ok' => $ok, 'errorString' => $errorString];
    echo(json_encode($response));
}


?>

