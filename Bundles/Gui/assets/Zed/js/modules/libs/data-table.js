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

function setTableErrorMode(errorMode) {
    $.fn.dataTable.ext.errMode = errorMode || 'none';
}

function onTabChange (tabId) {
    var $tab = $(tabId);
    var $dataTables = $tab.find('.gui-table-data, .gui-table-data-no-search');

    if (!$dataTables.data('initialized')) {
        $dataTables.data('initialized', true).DataTable().draw();
    }
}

function onError (e, settings, techNote, message) {
    var debugMessage = '';

    if (DEV) {
        debugMessage = '<br/><br/><small><u>Debug message:</u><br/> ' + message + '</small>';
    }

    window.sweetAlert({
        title: 'Error',
        text: 'Something went wrong. Please <a href="javascript:window.location.reload()">refresh</a> the page or try again later.' + debugMessage,
        html: true,
        type: 'error'
    });
}

module.exports = {
    defaultConfiguration: defaultConfiguration,
    noSearchConfiguration: noSearchConfiguration,
    setTableErrorMode: setTableErrorMode,
    onTabChange: onTabChange,
    onError: onError
};
