'use strict';

function GroupModal(elementId) {
    var self = this;
    self.content = null;

    self.init = function(){
        self.content = $('<ul/>', {
            id: 'group-body-list'
        });
    };

    self.addGroupRoleElement = function(role){
        $('<li/>', {
            class: 'role-item',
            text: role.Name
        }).appendTo(self.content);
    };

    self.showModal = function(){
        var modalAlert = new SprykerAlert();
        modalAlert.custom(self.content, 'Roles in Group');
    };

    self.init();
}
