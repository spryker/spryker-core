'use strict';

$(document).ready(function() {
    var spyAj = new SprykerAjax();

    $('#group-table').on('click', 'a.display-roles', function(event){
        event.preventDefault();
        var idGroup = $(this).attr('id').replace('group-', '');
        spyAj.getRolesForGroup(idGroup);
    });
});
