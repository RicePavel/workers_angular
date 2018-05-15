 <?php
		 
		header('Content-Type: text/html; charset=utf-8'); 
                include_once 'functions.php';
                
                ?>
                

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
        <title></title>
        <style>
            .my_table {
               border: 1px solid #ddd;
               width: 50%;
               border-collapse: collapse;
            }
            .my_table th, .my_table td {
                padding: 8px;
                text-align: center;
                border: 1px solid #ddd;
            }
            
        </style>
        <!-- <link rel="stylesheet" type="text/css" href="./bootstrap-4.0.0-dist/css/bootstrap.css" > -->
        <!--<script type="text/javascript" src="./bootstrap-4.0.0-dist/js/bootstrap.js" />-->
        
    </head>
    <body>
        <?php   
            // put your code here
            
            
            
            function getWorkersListStatement($conn, $filter = false, $salary = '') {
                $str = 'SELECT * FROM workers ';
                if ($filter) {
                    $str .= ' where salary = :salary ';
                }
                $stmt = $conn->prepare($str);
                if ($filter) {
                    $stmt->bindParam('salary', $salary);
                }
                $stmt->execute();
                return $stmt;
            }
            
            function deleteWorker($conn, $id, &$errorArray) {
               $stmt = $conn->prepare('DELETE FROM workers where id= ?'); 
               $stmt->bindParam(1, $id, PDO::PARAM_INT);
               $ok = $stmt->execute();
               $errorArray = $stmt->errorInfo();
               return $ok;
            }
            
            function addWorker($conn, $name, $age, $salary, &$errorStr) {
                if (!is_numeric($age)) {
                    $errorStr = 'Возраст должен быть числом ';
                    return false;
                }
                if (!is_numeric($salary)) {
                    $errorStr = 'Зарплата должна быть числом';
                    return false;
                }
                $stmt = $conn->prepare('Insert into workers(name, age, salary) values(:name, :age, :salary)');
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':age', $age, PDO::PARAM_INT);
                $stmt->bindParam(':salary', $salary, PDO::PARAM_INT);
                $ok = $stmt->execute();
                if (!$ok) {
                    $errorStr = $stmt->errorInfo()[2];
                }
                return $ok;
            }
        
        ?>
        
        
        <?php
            
            $LOCATION = '/workers/index.php';
        
            $conn = getDbConnection();
            if (isset($_REQUEST['action'])) { 
                $action = $_REQUEST['action'];
                if ($action == 'delete') {
                    $id = $_REQUEST['del_id'];
                    $errorArray = [];
                    $ok = deleteWorker($conn, $id, $errorArray);
                    if ($ok) {
                        header('Location: '.$LOCATION);
                    } else {
                        echo('при попытке удаления произошла ошибка: ');
                        echo($errorArray[2]);
                        echo('<br/>');
                    }
                } else if ($action == 'add') {
                    $errorStr = '';
                    $ok = addWorker($conn, $_REQUEST['name'], $_REQUEST['age'], $_REQUEST['salary'], $errorStr);
                    if ($ok) {
                        header('Location: '.$LOCATION);
                    } else {
                        echo('при попытке добавления записи произошла ошибка: ');
                        echo($errorStr);
                        echo('<br/>');
                    }
                } else if ($action == 'multiple_delete') {
                   $errorStr = '';
                   if (isset($_REQUEST['id'])) {
                      $idArray = $_REQUEST['id'];
                      $ok = deleteSeveralWorkers($conn, $idArray, $errorStr);
                      if ($ok) {
                          header('Location: '.$LOCATION);
                      } else {
                          echo('при попытке удаления записей произошла ошибка: ');
                          echo($errorStr);
                          echo('<br/>');
                      }
                    }
                }
            }
        
        ?>
        
        
        <form method="POST" action="" >
            Имя: <input type="text" name="name" /> <br/>
            Возраст: <input type="text" name="age" /> <br/>
            Зарплата: <input type="text" name="salary" /> <br/>
            <input type="hidden" name="action" value="add" />
            <input type="submit" name="submit" value="Добавить нового работника" />
            
        </form>
        <br/><br/>
        
        <form>
            Зарплата: <input type="text" name="salary" value="<?=$_REQUEST['salary']?>" />
            <input type="hidden" name="filter" value="salary" />
            <input type="submit" name="submit" value="Выбрать" />
        </form>
        
        
        <table class="my_table">
        
            <tr>
                <th>id</th>
                <th>name</th>
                <th>age</th>
                <th>salary</th>
                <th>удаление</th>
                <th>редактирование</th>
                <th>удаление</th>
            </tr>
            
        <?php
        
            $filter = false;
            $salary = '';
            if (isset($_REQUEST['filter'])) {
                $salary = trim($_REQUEST['salary']);
                if ($salary != '') {
                    $filter = true;
                }
            }
        
            $stmt = getWorkersListStatement($conn, $filter, $salary);
            while ($row = $stmt->fetch()) {
                ?>
            <tr>
                <td><?=$row['id']?></td>
                <td><?=$row['name']?></td>
                <td><?=$row['age']?></td>
                <td><?=$row['salary']?></td>
                <td><a href="?action=delete&del_id=<?=$row['id']?>">удалить</a></td>
                <td><a href="change.php?id=<?=$row['id']?>">редактировать</a></td>
                <td> <input type="checkbox" form="multiple_delete_form" name="id[]" value="<?=$row['id']?>" /> </td>
            </tr>
                <?php
            }
        
        ?>
        
        </table>
            
        <form id="multiple_delete_form" method="POST" >
            <input type="hidden" name="action" value="multiple_delete" />
            <input type="submit" name="submit" value="Удалить"  />
        </form>
        
    </body>
</html>
