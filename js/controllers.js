/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

myApp.controller('workersController', function($scope, $http) {
    
    updateWorkers($scope, $http);
    $scope.workersForDelete = [];
    
    $scope.add = function(newWorker, addWorkerForm) {
        if (addWorkerForm.$valid) {
            sendPostHeader($http);
            $http.post('addWorker_ajax.php', Object.toparams(newWorker)).then(function success(response){
               var data = response.data;
               if (data.ok) {
                   showSuccessMessage('сотрудник добавлен');
                   updateWorkers($scope, $http);
                   $('#addForm input').val('');
               } else {
                   showErrorMessage(data.errorStr);
               }
            }, function error(response) {
                alert('error');
            });
            
        }
    }
    
    $scope.deleteWorker = function(id) {
        if (confirm('подтвердите удаление')) {
            var url = 'ajaxActions.php?action=delete&id=' + id;
            $http({method: 'GET', url: url}).then(function success(response){
                var data = response.data;
                if (data.ok) {
                    updateWorkers($scope, $http); 
                } else {
                    showErrorMessage(data.errorString);
                }
            }, function error(response) {

            });
        }
    }
    
    $scope.deleteMultipleWorker = function() {
        var workersForDelete = $scope.workersForDelete;
        var ids = [];
        for (var key in workersForDelete) {
            if (workersForDelete[key] ===true) {
                ids.push(key);
            }
        }
        var url = 'ajaxActions.php?action=multipleDelete';
        var data = {id: ids, source: 'angularApp'};
        sendPostHeader($http);
        var dataString = Object.toparams(data);
        $http({method: 'POST', url: url, data: dataString}).then(function success(response) {
            updateWorkers($scope, $http);
        });
    }
    
    $scope.showChangeWorkerForm = function(worker) {
        $scope.workerForChange = Object.assign({}, worker);
    }
    
    $scope.changeWorker = function(changeForm) {
        if (changeForm.$valid) {
            sendPostHeader($http); 
            $http.post('ajaxActions.php?action=change', Object.toparams($scope.workerForChange)).then(function success(response) {
                var data = response.data;
                if (data.ok) {
                    hideModalWindow();
                    updateWorkers($scope, $http);
                } else {
                    showErrorMessageInForm(data.errorString);
                }
            }, function error(response) {
                
            })
        }
    }
    
    $scope.searchBySalary = function() {
       updateWorkersBySalary($scope, $http, $scope.salaryForSearch); 
    }
    
});

function updateWorkersBySalary($scope, $http, salary) {
    var url = 'ajaxActions.php?action=getList&salary=' + salary;
    $http({method: 'GET', url: url}).then(function success(response){
        $scope.workers = response.data;
    }, function error(response) {
        alert('2');
    });
}

function updateWorkers($scope, $http) {
    $http({method: 'GET', url:'ajaxActions.php?action=getList'}).then(function success(response){
        $scope.workers = response.data;
    }, function error(response) {
        alert('2');
    });
}

function sendPostHeader($http) {
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
}

Object.toparams = function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
};
