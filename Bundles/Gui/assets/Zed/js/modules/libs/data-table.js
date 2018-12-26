'use strict';

function getLanguage() {

    var locale = $('#locale').val()

    if (locale.indexOf('de') != -1) {
        return 'German';
    }

    return 'English';
}

function getTranslation(language) {

    var translations = {
        'English': require('./i18n/English.json'),
        'German': require('./i18n/German.json')
    }

    if(translations.hasOwnProperty(language)) {
        return translations[language]
    }

    return translations['English']
}

var language = getLanguage()

var defaultConfiguration = {
    scrollX: 'auto',
    autoWidth: false,
    language: getTranslation(language)
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

function getNavigatorLanguage() {
    if (navigator.languages && navigator.languages.length) {
        return navigator.languages[0];
    } else {
        return navigator.language || 'en_EN';
    }
}

module.exports = {
    defaultConfiguration: defaultConfiguration,
    noSearchConfiguration: noSearchConfiguration,
    setTableErrorMode: setTableErrorMode,
    onTabChange: onTabChange,
    onError: onError
};