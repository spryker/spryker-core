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
