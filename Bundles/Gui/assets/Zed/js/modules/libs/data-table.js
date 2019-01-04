'use strict';

function getLocale() {

    var locale = $('#locale').val()

    return locale.split('_')[0].split('-')[0];
}

function getTranslation(language) {

    var translations = {}

    var languages = ['en', 'de']

    for (var i=0; i<languages.length; i++) {
        translations[languages[i]] = require('./i18n/' + languages[i] + '.json')
    }

    if(translations.hasOwnProperty(language)) {
        return translations[language]
    }

    return translations['en']
}

var language = getLocale()

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