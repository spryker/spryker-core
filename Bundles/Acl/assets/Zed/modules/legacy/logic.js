'use strict';

require('vendor/spryker/spryker/Bundles/Gui/assets/Zed/modules/main');
require('./acl.helpers.js');

$(document).ready(function() {
    var spyAj = new SprykerAjax();

    $('#group-table').on('click', 'a.display-roles', function(event){
        event.preventDefault();
        var idGroup = $(this).attr('id').replace('group-', '');
        spyAj.getRolesForGroup(idGroup);
    });

    $('#users-in-group').on('click', 'a.remove-user-from-group', function(event){
        event.preventDefault();
        var options = $(this).data('options');
        spyAj.removeUserFromGroup(options);
    });
});
