/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var editor = require('ZedGuiEditorConfiguration');
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

$(document).ready(function() {
    // editor
    $('.html-editor').summernote(editor.getConfig());

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
        var obj = $(value);
        if (obj.data('url') === 'undefined') {
            return;
        }
        obj.autocomplete({
            source: obj.data('url'),
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
        if ($(element).data('autocomplete-url')) {
            var autocompleteUrl = $(this).data('autocomplete-url');

            $(element).select2({
                ajax: {
                    url: autocompleteUrl,
                    dataType: 'json',
                    delay: 500,
                    cache: true,
                },
                minimumInputLength: 3
            });
        } else {
            $(element).select2();
        }
    })

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
