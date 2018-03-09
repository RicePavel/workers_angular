
$( document ).ready(function() {
    
    $('body').on('click', '.multipleDeleteCheckbox', function() {
        var length = $('#workersTable .multipleDeleteCheckbox:checked').length;
        var submit = $('#multiple_delete_form submit');
        if (length > 0) {
            submit.addClass('btn-primary');
            submit.removeClass('btn-default');
        } else {
            submit.addClass('btn-default'); 
            submit.removeClass('btn-primary');
        }
    });
    
});

function showErrorMessage(message) {
    var alert = $('#elementContainer .alert-danger').clone();
    alert.find('.content').html(message);
    $('#topAlertContainer').append(alert);
    alert.show();
    setRemoveByTimeout(alert);
}

function showErrorMessageInForm(message) {
    var alert = $('#elementContainer .alert-danger').clone();
    alert.find('.content').html(message);
    $('#myModal .alertFormContainer').append(alert);
    alert.show();
    setRemoveByTimeout(alert);
}

function showSuccessMessage(message) {
    var alert = $('#elementContainer .alert-success').clone();
    alert.find('.content').html(message);
    $('#topAlertContainer').append(alert);
    alert.show();
    setRemoveByTimeout(alert);
}

function setRemoveByTimeout(element) {
    setTimeout(function() {
        element.remove();
    }, 2000);
}

function hideModalWindow() {
    $('#myModal').modal('hide');
}


