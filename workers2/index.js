
$( document ).ready(function() {
    $('#addForm').submit(function() {
        
        var values = $(this).serialize();

        $.ajax({
            url: 'addWorker_ajax.php',
            type: 'post',
            data: values,
            success: function(response) {
                var obj = $.parseJSON(response);
                if (obj.ok === true) {
                    var message = 'работник успешно добавлен ';
                    var alertElement = $('#elementContainer .alert-success').clone();
                    alertElement.children('.content').html(message);
                    var alertContainer = $('.alertContainer');
                    alertContainer.html('');
                    alertContainer.append(alertElement); 
                    setRemoveByTimeout(alertElement);
                    
                    var rowElement = getTableRow(obj.id, obj.name, obj.age, obj.salary);
                    $('#workersTable').append(rowElement);
                    
                    $('#addForm input[type=text]').val('');
                } else {
                    var message = 'ошибка: ' + obj.errorStr;
                    var alertElement = $('#elementContainer .alert-danger').clone();
                    alertElement.children('.content').html(message);
                    var alertContainer = $('.alertContainer');
                    alertContainer.html('');
                    alertContainer.append(alertElement); 
                    setRemoveByTimeout(alertElement);
                }
            },
            error: function() {
                alert('error');
            }
        });

        return false;
    });
    
    $('#changeForm').submit(function() {
        var values = $(this).serialize();
        $.ajax({
            url: 'ajaxActions.php',
            type: 'post',
            data: values,
            success: function(response) {
                var obj = $.parseJSON(response);
                if (obj.ok) {
                    $('#myModal').modal('hide');
                    var id = obj.id;
                    var name = obj.name;
                    var age = obj.age;
                    var salary = obj.salary;
                    var tableRow = getTableRow(id, name, age, salary);
                    var oldRow = $('#workersTable').find('[data-id='+ id + ']');
                    oldRow.replaceWith(tableRow);
                } else {
                    var alertElement = getAlertDanger();
                    var message = 'ошибка: ' + obj.errorString;
                    alertElement.children('.content').html(message);
                    $('#myModal .alertFormContainer').append(alertElement);
                }
            }
        });
        return false;
    });
    
    $('body').on('click', '.changeLink', function() {
    //$('.changeLink').click(function() {
        $('#myModal').modal('show');
        var link = $(this);
        var tr = link.closest('tr');
        var id = tr.attr('data-id');
        var name = tr.attr('data-name');
        var age = tr.attr('data-age');
        var salary = tr.attr('data-salary');
        var form = $('#myModal #changeForm');
        form.find('.inputName').val(name);
        form.find('.inputId').val(id);
        form.find('.inputAge').val(age);
        form.find('.inputSalary').val(salary);
        $('#myModal').find('.alertFormContainer').html('');
        return false;
    });
    
    $('body').on('click', '.deleteLink', function() {
    //$('.deleteLink').click(function() {
        if (confirm('подтвердите удаление')) {
           var tr = $(this).closest('tr');
           var id = tr.attr('data-id');
           var values= 'action=delete&id=' + id;
           $.ajax({
            url: 'ajaxActions.php',
            type: 'post',
            data: values,
            success: function(response) {
               var obj = $.parseJSON(response);
               if (obj.ok) {
                   tr.remove();
               } else {
                   var message = 'произошла ошибка: ' + obj.errorString;
                   showAlertDangerInTop(message);
               }
            }
            }); 
        }
        return false;
    });
    
    $('#validationForm').submit(function() {
        var salary = $(this).find('.salaryInput').val();
        var values = 'action=getList&salary=' + salary;
        $.ajax({
            url: 'ajaxActions.php',
            type: 'post',
            data: values,
            success: function(response) {
               var obj = $.parseJSON(response);
               $('#workersTable tr').not('.trHead').remove();
               for (var key in obj) {
                   var entry = obj[key];
                   var tableRow = getTableRow(entry.id, entry.name, entry.age, entry.salary);
                   $('#workersTable').append(tableRow);
               }
            }
        });
        return false;
    });
    
    $('body').on('click', '.multipleDeleteCheckbox', function() {
        var length = $('#workersTable .multipleDeleteCheckbox:checked').length;
        var submit = $('#multiple_delete_form input[type=submit]');
        if (length > 0) {
            submit.addClass('btn-primary');
            submit.removeClass('btn-default');
        } else {
            submit.addClass('btn-default'); 
            submit.removeClass('btn-primary');
        }
    });
    
    $('#multiple_delete_form').submit(function() {
        if (confirm('подтвердите удаление')) {
            var ids = [];
            $('#workersTable .multipleDeleteCheckbox:checked').each(function(index, element) {
                ids.push($(element).val());
            });
            var data = {'id[]' : ids};
            data.action = 'multipleDelete';
            $.ajax({
                url: 'ajaxActions.php',
                type: 'post',
                data: data,
                success: function(response) {
                    var obj = $.parseJSON(response);
                    if (obj.ok) {
                       for (var key in ids) {
                           var id = ids[key];
                           $('#workersTable tr[data-id='+ id + ']').remove();
                       }
                    } else {
                        var message = 'ошибка: ' + obj.errorString;
                        showAlertDangerInTop(message);
                    }
                }
            });
        }
        return false;
    });
    
    function showAlertDangerInTop(message) {
        var alertContainer = $('.alertContainer');
        var alertDanger = getAlertDanger();
        alertDanger.children('.content').html(message);
        alertContainer.append(alertDanger);
    }
    
    function getAlertDanger() {
        return $('#elementContainer .alert-danger').clone();
    }
    
    function getTableRow(id, name, age, salary) {
        var rowElement = $('#elementContainer .tableRow').clone();
        rowElement.find('.id').html(id);
        rowElement.find('.name').html(name);
        rowElement.find('.age').html(age);
        rowElement.find('.salary').html(salary);
        
        rowElement.find('.delete a').attr('href', '?action=delete&del_id=' + id);
        rowElement.find('.change a').attr('href', 'change.php?id=' + id);
        rowElement.find('.multiple_delete input').val(id);
        
        rowElement.attr('data-id', id);
        rowElement.attr('data-name', name);
        rowElement.attr('data-age', age);
        rowElement.attr('data-salary', salary);
        
        return rowElement;
    }
    
    function setRemoveByTimeout(element) {
        setTimeout(function() {
            element.remove();
        }, 2000);
    }
    
});

