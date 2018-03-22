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
    $('.spryker-form-select2combobox').select2();

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
