'use strict';

var defaultConfiguration = {
    scrollX: 'auto',
    autoWidth: false
};

var noSearchConfiguration = {
    bFilter: false,
    bInfo: false,
    scrollX: 'auto',
    autoWidth: false
};

function onTabChange (tabId) {
    var $tab = $(tabId);
    var $dataTables = $tab.find('.gui-table-data, .gui-table-data-no-search');

    if (!$dataTables.data('initialized')) {
        $dataTables.data('initialized', true).DataTable().draw();
    }
}

module.exports = {
    defaultConfiguration: defaultConfiguration,
    noSearchConfiguration: noSearchConfiguration,
    onTabChange: onTabChange
};
