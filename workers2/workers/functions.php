<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

            function getDbConnection() {
                
                /*
                $username = 'workersUser';
                $password = 'qwerty';
                $conn = new PDO('mysql:host=localhost;dbname=workers', $username, $password);
                */
                 
                $username = 'test';
                $password = 'qwerty';
                $conn = new PDO('mysql:host=localhost;dbname=task_management', $username, $password);
                 
                /*
                $username = 'u34712_workers';
                $password = 'qwerty123';
                $conn = new PDO('mysql:host=localhost;dbname=u34712_workers', $username, $password);
                 * 
                 */
                
                $conn->query("SET NAMES 'utf8'");
                $conn->query("SET CHARACTER SET 'utf8'");
                $conn->query("SET SESSION collation_connection = 'utf8_general_ci'");
                return $conn;
            }
            
            /**
             * 
             * @param type $conn
             * @param type $id
             * @return PDOStatement
             */
            function getWorkerStatement($conn, $id) {
                $stmt = $conn->prepare('SELECT * FROM workers WHERE id = ?');
                $stmt->bindParam(1, $id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt;
            }
            
            
            
            /**
             * 
             * @param type $conn
             * @param type $id
             * @param type $name
             * @param type $age
             * @param type $salary
             * @return boolean
             */
            function changeWorker($conn, $id, $name, $age, $salary, &$errorString) {
                if (!is_numeric($age)) {
                    $errorString = 'Возраст должен быть числом ';
                    return false;
                }
                if (!is_numeric($salary)) {
                    $errorString = 'Зарплата должна быть числом';
                    return false;
                }
                $stmt = $conn->prepare('update workers set name = :name, age = :age, salary = :salary where id = :id');
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':age', $age);
                $stmt->bindParam(':salary', $salary);
                $ok = $stmt->execute();
                if (!$ok) {
                    $errorString = $stmt->errorInfo()[2];
                }
                return $ok;
            }
            
            function deleteSeveralWorkers($conn, $idArray, &$errorString) {
                foreach ($idArray as $id) {
                    $stmt = $conn->prepare('DELETE FROM workers where id = ?');
                    $stmt->bindParam(1, $id, PDO::PARAM_INT);
                    $ok = $stmt->execute();
                    if (!$ok) {
                      $errorArray = $stmt->errorInfo();  
                      break;
                    }
                }
                return $ok;
            }

?>
