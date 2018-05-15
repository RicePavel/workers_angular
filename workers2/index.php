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
               width: 50% !important;
               border-collapse: collapse;
               margin: 0 0 0 20px;
            }
            .my_table th, .my_table td {
                padding: 8px;
                text-align: center;
                border: 1px solid #ddd;
            }
            
            .alertContainer {
                margin: 20px 20px 20px 550px;
                width: 500px;
            }
            
            #addForm {
                width: 500px;
                margin: 20px 0 0 20px;
                float: left;
            }
            #validationForm {
                margin: 0 0 10px 20px;
            }
            .multipleDeleteButton {
                margin: 0 0 0 20px;
            }
            
        </style>
        <link rel="stylesheet" type="text/css" href="./bootstrap-3.3.7-dist/css/bootstrap.min.css" >
        
        <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="./bootstrap-3.3.7-dist/js/bootstrap.js" > </script>
        <script type="text/javascript" src="index.js" > </script>
        
        
        
        
        
    </head>
    <body>
        
        <div id="elementContainer" style="display: none;">
            <div class="alert alert-danger" role="alert">
                <span class='content'></span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success" role="alert">
                <span class='content'></span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <table>
            <tr class='tableRow'>
                <td class='id'></td>
                <td class='name'></td>
                <td class='age'></td>
                <td class='salary' ></td>
                <td class='delete' ><a href="" class="deleteLink" >удалить</a></td>
                <td class='change' ><a href="" data-toggle="modal" data-target="#myModal" class="changeLink">редактировать</a></td>
                <td class='multiple_delete' > <input type="checkbox" class="multipleDeleteCheckbox" form="multiple_delete_form" name="id[]" value="" /> </td>
            </tr>
            </table>
            
        </div>
        
        <?php   
            // put your code here
            
        ?>
        
        
        <?php
            
            $LOCATION = '/workers2/index.php';
        
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
        
        <form class="form-horizontal" method="POST" action="" id="addForm" >
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label">Имя</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="name" id="inputName"/>
                </div>
            </div>
            <div class="form-group">
                <label for="inputAge" class="col-sm-2 control-label">Возраст</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="age" id="inputAge" />
                </div>
            </div>
            <div class="form-group">
                <label for="inputSalary" class="col-sm-2 control-label">Зарплата</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="salary" id="inputSalary" />
                </div>
            </div>
            <input type="hidden" name="action" value="add" />
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" name="submit" value="Добавить нового работника" class="btn btn-primary" />
                </div>
            </div>
        </form>
        
        <div class="alertContainer"> </div>
        <div style="clear: both;"> </div>
        
        <br/><br/>
        
        <form class="form-inline" id="validationForm" >
            <div class="form-group">
                <label for="salary">Зарплата</label>
                <input type="text" name="salary" id="salary" class="form-control salaryInput" value="<?=$_REQUEST['salary']?>" />
            </div>
            <input type="hidden" name="filter" value="salary" />
            <input type="submit" name="submit" value="Выбрать" class="btn btn-default" />
        </form>
        
        
        <table class="my_table table table-bordered" id='workersTable' >
        
            <tr class="trHead">
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
            <tr data-id="<?=$row['id']?>" data-name="<?=$row['name']?>" data-age="<?=$row['age']?>" data-salary="<?=$row['salary']?>" >
                <td><?=$row['id']?></td>
                <td><?=$row['name']?></td>
                <td><?=$row['age']?></td>
                <td><?=$row['salary']?></td>
                <td><a href="?action=delete&del_id=<?=$row['id']?>" class="deleteLink">удалить</a></td>
                <td>
                    <a href="change.php?id=<?=$row['id']?>" data-toggle="modal" data-target="#myModal" class="changeLink" >редактировать</a>
                </td>
                <td> <input type="checkbox" class="multipleDeleteCheckbox" form="multiple_delete_form" name="id[]" value="<?=$row['id']?>" /> </td>
            </tr>
                <?php
            }
        
        ?>
        
        </table>
            
        <form id="multiple_delete_form" method="POST" >
            <input type="hidden" name="action" value="multiple_delete" />
            <input type="submit" name="submit" value="удалить выбранные записи" class="btn btn-default multipleDeleteButton" />
        </form>
        
        
        
        
        
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Изменение записи</h4>
                    </div>
                    <div class="modal-body">
                      
                        <form class="form-horizontal" method="POST" action="" id="changeForm" >
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Имя</label>
                                <div class="col-sm-10">
                                    <input class="form-control inputName" type="text" name="name" id="inputName" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputAge" class="col-sm-2 control-label">Возраст</label>
                                <div class="col-sm-10">
                                    <input class="form-control inputAge" type="text" name="age" id="inputAge" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputSalary" class="col-sm-2 control-label">Зарплата</label>
                                <div class="col-sm-10">
                                    <input class="form-control inputSalary" type="text" name="salary" id="inputSalary" />
                                </div>
                            </div>
                            <input type="hidden" name="id" value="" class="inputId" />
                            <input type="hidden" name="action" value="change" />
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" name="submit" value="Изменить" class="btn btn-primary" />
                                </div>
                            </div>
                        </form>
                        
                        <div class="alertFormContainer"></div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        
    </body>
</html>
