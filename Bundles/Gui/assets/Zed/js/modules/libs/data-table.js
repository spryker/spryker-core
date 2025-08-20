'use strict';

function getLocale() {
    var locale = document.documentElement.dataset.applicationLocale;
    if (typeof locale === 'string') {
        return locale.split('_')[0].split('-')[0];
    }
    return 'en';
}

function getTranslation(locale) {
    try {
        return require('./i18n/' + locale + '.json');
    } catch (e) {
        return require('./i18n/en.json');
    }
}

var locale = getLocale();

var translationObj = getTranslation(locale);

var defaultConfiguration = {
    scrollX: true,
    language: translationObj,
    dom:
        "<'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'alt-row'<'alt-row__left'l><'alt-row__center'p>>",
};

var noSearchConfiguration = {
    bFilter: false,
    bInfo: false,
    scrollX: true,
};

function setTableErrorMode(errorMode) {
    $.fn.dataTable.ext.errMode = errorMode || 'none';
}

function onTabChange(tabId) {
    var $tab = $(tabId);
    var $dataTables = $tab.find('.gui-table-data, .gui-table-data-no-search');

    if (!$dataTables.data('initialized')) {
        $dataTables.data('initialized', true).DataTable().draw();
    }

    $dataTables.DataTable().columns.adjust();
}

function onError(e, settings, techNote, message) {
    var debugMessage = '';

    if (DEV) {
        debugMessage = '<br/><br/><small><u>Debug message:</u><br/> ' + message + '</small>';
    }

    window.sweetAlert({
        title: 'Error',
        text:
            'Something went wrong. Please <a href="javascript:window.location.reload()">refresh</a> the page or try again later.' +
            debugMessage,
        html: true,
        type: 'error',
    });
}

module.exports = {
    defaultConfiguration: defaultConfiguration,
    noSearchConfiguration: noSearchConfiguration,
    setTableErrorMode: setTableErrorMode,
    onTabChange: onTabChange,
    onError: onError,
};
