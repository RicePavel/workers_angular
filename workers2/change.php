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
        
    </head>
    <body>
        <?php   
            
        
        ?>
        
        
        <?php
            
            $LOCATION = '/workers2/index.php';
        
            $conn = getDbConnection();
            
            if (isset($_REQUEST['submit'])) {
                $action = $_REQUEST['action'];
                if ($action == 'change') {
                    $errorString = '';
                    $ok = changeWorker($conn, $_REQUEST['id'], $_REQUEST['name'], $_REQUEST['age'], $_REQUEST['salary'], $errorString);
                    if ($ok) {
                        header('Location: '.$LOCATION);
                    } else {
                        echo('ошибка: ' . $errorString);
                    }
                }
            }
            
            
            $id = $_REQUEST['id'];
            $stmt = getWorkerStatement($conn, $id);
            $name = '';
            $age = '';
            $salary = '';
            while ($row = $stmt->fetch()) {
                $id = $row['id'];
                $name = $row['name'];
                $age = $row['age'];
                $salary = $row['salary'];
            }
        
        ?>
        
        
        <form method="POST" action="" >
            Имя: <input type="text" name="name" value='<?=$name?>' /> <br/>
            Возраст: <input type="text" name="age" value='<?=$age?>' /> <br/>
            Зарплата: <input type="text" name="salary" value='<?=$salary?>' /> <br/>
            <input type="hidden" name="action" value="change" />
            <input type="hidden" name="id" value="<?=$id?>" />
            <input type="submit" name="submit" value="Изменить" />
        </form>
        
        
        </table>
            
    </body>
</html>
