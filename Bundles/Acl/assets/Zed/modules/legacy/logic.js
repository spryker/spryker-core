/**
 *
 * Spryker alert message manager
 * @copyright: Spryker Systems GmbH
 *
 */

'use strict';

require('vendor/spryker/spryker/Bundles/Gui/assets/Zed/modules/main');
require('./acl.helpers.js');

var SprykerAjax = require('vendor/spryker/spryker/Bundles/Gui/assets/Zed/modules/legacy/SprykerAjax');

$(document).ready(function() {
    $('#group-table').on('click', 'a.display-roles', function(event){
        event.preventDefault();
        var idGroup = $(this).attr('id').replace('group-', '');
        SprykerAjax.getRolesForGroup(idGroup);
    });

    $('#users-in-group').on('click', 'a.remove-user-from-group', function(event){
        event.preventDefault();
        var options = $(this).data('options');
        SprykerAjax.removeUserFromGroup(options);
    });
});
