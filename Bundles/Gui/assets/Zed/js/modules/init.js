/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var editorConfig = require('ZedGuiEditorConfiguration');
var Tabs = require('./libs/tabs');
var TranslationCopyFields = require('./libs/translation-copy-fields');
var Ibox = require('./libs/ibox');
var dataTable = require('./libs/data-table');
var safeChecks = require('./libs/safe-checks');

var dataTablesSearchDelay = function() {
    var dataTablesWrapper = $('.dataTables_wrapper');
    dataTablesWrapper.each(function(index, wrapper) {
        var searchInput = $(wrapper).find('input[type="search"]');
        var dataTable = $(wrapper).find('.gui-table-data');
        var dataTableApi = dataTable.dataTable().api();
        var timeOutId = 0;

        if(searchInput.length && dataTable.length) {
            searchInput
            .unbind()
            .bind("input", function(e) {
                var self = this;

                clearTimeout(timeOutId);
                timeOutId = setTimeout(function() {
                    dataTableApi.search(self.value).draw();
                }, 1000);
                return;
            });
        }
    });
}

var editorInit = function() {
    $('.html-editor').each(function() {
        var $textarea = $(this);
        var textareaConfigName = $textarea.data('editor-config');

        var config = editorConfig.getGlobalConfig(textareaConfigName);

        if (!config) {
            config = editorConfig.getConfig();
        }

        $textarea.summernote(config);
    });
};

$(document).ready(function() {
    // editor
    editorInit();

    /* Data tables custom error handling */
    dataTable.setTableErrorMode('none');

    /* Draw data tables */
    $('.gui-table-data')
        .on('error.dt', dataTable.onError)
        .dataTable(dataTable.defaultConfiguration);

    /* Draw data tables without search */
    $('.gui-table-data-no-search')
        .on('error.dt', dataTable.onError)
        .dataTable(dataTable.noSearchConfiguration);

    /* All elements with the same class will have the same height */
    $('.fix-height').sprykerFixHeight();

    $('.spryker-form-autocomplete').each(function(key, value) {
        var autoCompletedField = $(value);
        if (autoCompletedField.data('url') === 'undefined') {
            return;
        }

        if (autoCompletedField.hasClass('ui-autocomplete')) {
            autoCompletedField.autocomplete('destroy');
        }

        autoCompletedField.autocomplete({
            source: autoCompletedField.data('url'),
            minLength: 3
        });
    });

    $('.table-dependency tr').hover(
        function(){
            $(this).addClass('warning');
        },
        function(){
            $(this).removeClass('warning');
        }
    );
    $('.table-dependency .btn-xs').hover(
        function(){
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
        },
        function(){
            $(this).addClass('btn-default');
            $(this).removeClass('btn-primary');
        }
    );

    $('.dropdown-toggle').dropdown();

    $('.spryker-form-select2combobox').each(function(index, element) {
        var select2InitOptions = {};
        var selectElement = $(element);

        if (selectElement.data('autocomplete-url')) {
            select2InitOptions = {
                ajax: {
                    url: selectElement.data('autocomplete-url'),
                    dataType: 'json',
                    delay: 500,
                    cache: true,
                    data: function(params) {
                        params.page = params.page || 1;

                        return params;
                    }
                },
                minimumInputLength: 3
            };

            selectElement.on('select2:unselecting', function(e) {
                var idSelected = e.params.args.data.id;
                var selectedValues = selectElement.val();

                selectElement.val(selectedValues.filter(function(value) {
                    return value !== ('' + idSelected);
                })).trigger('change');
            });
        }

        selectElement.select2(select2InitOptions);
    });

    /* Init tabs */
    $('.tabs-container').each(function(index, item){
        new Tabs(item, dataTable.onTabChange);
    });

    /* Init translation copy fields */
    new TranslationCopyFields();

    /* Init iboxes */
    new Ibox();

    safeChecks.addSafeSubmitCheck();
    safeChecks.addSafeDatetimeCheck();
});

$(window).on('load', function() {
    dataTablesSearchDelay();
});
