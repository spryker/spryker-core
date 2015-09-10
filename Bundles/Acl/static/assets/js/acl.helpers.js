'use strict';

var memoize = new GroupModalMemoization();

function spinnerCreate(elementId){
    var container = $('<div/>', {
        class: 'sk-spinner sk-spinner-circle'
    });
    for (var I = 1; I<=12; I++) {
        var circle = $('<div>', {
            class: 'sk-circle sk-circle' + I
        }).appendTo(container);
    }
    $(elementId).html(container);
}

function spinnerClear(){
    $('.group-spinner-container').html('');
}


/**
 * @param int idGroup
 */
SprykerAjax.prototype.getRolesForGroup = function(idGroup) {
    var options = {
        'id-group': idGroup
    };
    if (memoize.hasMember(idGroup)) {
        var ajaxCallbacks = new SprykerAjaxCallbacks();
        ajaxCallbacks.displayGroupRoles(memoize.getMember(idGroup));
    } else {
        spinnerCreate('#group-spinner-' + idGroup);
        this
            .setUrl('/acl/group/roles')
            .ajaxSubmit(options, 'displayGroupRoles');
    }
};

SprykerAjax.prototype.removeUserFromGroup = function(options){
    var ajaxOptions = {
        "id-group": parseInt(options.idGroup),
        "id-user": parseInt(options.idUser)
    };
    if (!confirm('Are you sure you want to detele this user from this group ?')) {
        return false;
    }
    if (ajaxOptions.idGroup < 1 || ajaxOptions.idUser < 1) {
        var spyAlert = new SprykerAlert();
        spyAlert.error('User Id and Group Id cannot be null');
        return false;
    }
    this.setUrl('/acl/group/remove-user-from-group').ajaxSubmit(ajaxOptions, 'removeUserRowFromGroupTable');
};

/*
 * @param ajaxResponse
 * @returns string
 */
SprykerAjaxCallbacks.prototype.displayGroupRoles = function(ajaxResponse){
    if (ajaxResponse.code == this.codeSuccess) {
        if (ajaxResponse.data.length > 0) {
            var groupModal = new GroupModal('#modal-body');
            if (!memoize.hasMember(ajaxResponse.idGroup)) {
                memoize.saveMember(ajaxResponse.idGroup, ajaxResponse);
            }
            ajaxResponse.data.forEach(function(role){
                groupModal.addGroupRoleElement(role);
            });
            groupModal.showModal();
        }
    }
    spinnerClear();
};

SprykerAjaxCallbacks.prototype.removeUserRowFromGroupTable = function(ajaxResponse){
    if (ajaxResponse.code == this.codeSuccess) {
        var tableRow = $('#row-' + ajaxResponse['id-user'] + '-' + ajaxResponse['id-group']).closest('tr');
        tableRow.css({
            'background': '#a00',
            'color': '#fff'
        });
        tableRow.fadeOut('slow', function(){
            tableRow.remove();
        });
        return false;
    }

    var spyAlert = new SprykerAlert();
    spyAlert.error(ajaxResponse.message);
};
