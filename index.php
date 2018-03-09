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
<html ng-app="myApp" >
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
            a {
                cursor: pointer;
            }
            
        </style>
        <link rel="stylesheet" type="text/css" href="./bootstrap-3.3.7-dist/css/bootstrap.min.css" >
        
        <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="./bootstrap-3.3.7-dist/js/bootstrap.js" > </script>
        <script type="text/javascript" src="index.js" > </script>
        
        <script type="text/javascript" src="./angular/angular.min.js" ></script>
        <script type="text/javascript" src="./js/my_angular.js" ></script>
        <script type="text/javascript" src="./js/controllers.js" ></script>
        <script type="text/javascript" src="./js/services.js" ></script>
        
    </head>
    <body>
       
        <div ng-controller="workersController" >
        
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
        </div>
           
        
        <form class="form-horizontal" id="addForm" name="addWorkerForm" >
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label">Имя</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="name" id="inputName" ng-model="newWorker.name" required />
                </div>
            </div>
            <div class="form-group">
                <label for="inputAge" class="col-sm-2 control-label">Возраст</label>
                <div class="col-sm-10">
                    <input class="form-control" type="number" name="age" id="inputAge" ng-model="newWorker.age" required />
                </div>
            </div>
            <div class="form-group">
                <label for="inputSalary" class="col-sm-2 control-label">Зарплата</label>
                <div class="col-sm-10">
                    <input class="form-control" type="number" name="salary" id="inputSalary" ng-model="newWorker.salary" required />
                </div>
            </div>
            <input type="hidden" name="action" value="add" />
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <!-- <input type="submit" name="submit" value="Добавить нового работника" ng-click="add(newWorker, addWorkerform)" class="btn btn-primary" /> -->
                    <button type="submit" ng-click="add(newWorker, addWorkerForm)" class="btn btn-primary" >Добавить нового работника</button>
                </div>
            </div>
        </form>
        
        <div class="alertContainer" id="topAlertContainer"> 
            
        </div>
        <div style="clear: both;"> </div>
        
        <br/><br/>
        
        <form class="form-inline" id="validationForm" >
            <div class="form-group">
                <label for="salary">Зарплата</label>
                <input type="text" name="salary" id="salary" class="form-control salaryInput" ng-model="salaryForSearch" />
            </div>
            <input type="hidden" name="filter" value="salary" />
            <submit type="submit" name="submit" class="btn btn-default" ng-click="searchBySalary()" >Выбрать</submit>
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
            <tr ng-repeat="worker in workers" data-id="{{worker.id}}" data-name="{{worker.name}}" data-age="{{worker.age}}" data-salary="{{worker.salary}}" >
                <td>{{worker.id}}</td>
                <td>{{worker.name}}</td>
                <td>{{worker.age}}</td>
                <td>{{worker.salary}}</td>
                <td><a class="deleteLink" ng-click="deleteWorker(worker.id)" >удалить</a></td>
                <td>
                    <a ng-click="showChangeWorkerForm(worker)" data-toggle="modal" data-target="#myModal" class="changeLink" >редактировать</a>
                </td>
                <td> <input type="checkbox" class="multipleDeleteCheckbox" form="multiple_delete_form" name="id[]" value="{{worker.id}}" ng-model="workersForDelete[worker.id]" /> </td>
            </tr>
        </table>
        
        
        <form id="multiple_delete_form">
            <input type="hidden" name="action" value="multiple_delete" />
            <submit type="submit" name="submit" class="btn btn-default multipleDeleteButton" ng-click="deleteMultipleWorker()" >удалить выбранные записи</submit>
        </form>
              
        
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Изменение записи</h4>
                    </div>
                    <div class="modal-body">
                      
                        <form class="form-horizontal" id="changeForm" name="changeForm" >
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Имя</label>
                                <div class="col-sm-10">
                                    <input class="form-control inputName" type="text" name="name" id="inputName" value="" ng-model="workerForChange.name" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputAge" class="col-sm-2 control-label">Возраст</label>
                                <div class="col-sm-10">
                                    <input class="form-control inputAge" type="text" name="age" id="inputAge" ng-model="workerForChange.age" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputSalary" class="col-sm-2 control-label">Зарплата</label>
                                <div class="col-sm-10">
                                    <input class="form-control inputSalary" type="text" name="salary" id="inputSalary" ng-model="workerForChange.salary" required />
                                </div>
                            </div>
                            <input type="hidden" name="id" value="" class="inputId" ng-model="workerForChange.id" />
                            <input type="hidden" name="action" value="change" />
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" name="submit" value="Изменить" class="btn btn-primary" ng-click="changeWorker(changeForm)" />
                                </div>
                            </div>
                        </form>
                        
                        <div class="alertFormContainer">
                            
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        
        </div>
        
    </body>
</html>
