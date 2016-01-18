/**
 *
 * ACL logic
 * @copyright: Spryker Systems GmbH
 *
 */

'use strict';

require('./helpers.js');

$(document).ready(function() {
    $('#group-table').on('click', 'a.display-roles', function(event){
        event.preventDefault();
        var idGroup = $(this).attr('id').replace('group-', '');
        SprykerAjax.getRolesForGroup(idGroup);
    });

    $('#users-in-group').on('click', 'a', function(event){
        event.preventDefault();
        var options = $(this).data('options');
        SprykerAjax.removeUserFromGroup(options);
    });
});
