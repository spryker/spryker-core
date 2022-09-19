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
var initFormattedNumber = require('./libs/formatted-number-input');
var initFormattedMoney = require('./libs/formatted-money-input');
var select2combobox = require('./libs/select2-combobox');

var dataTablesSearchDelay = function () {
    var dataTablesWrapper = $('.dataTables_wrapper');
    dataTablesWrapper.each(function (index, wrapper) {
        var searchInput = $(wrapper).find('input[type="search"]');
        var dataTable = $(wrapper).find('.gui-table-data');
        var dataTableApi = dataTable.dataTable().api();
        var timeOutId = 0;

        if (searchInput.length && dataTable.length) {
            searchInput.unbind().bind('input', function (e) {
                var self = this;

                clearTimeout(timeOutId);
                timeOutId = setTimeout(function () {
                    dataTableApi.settings()[0].jqXHR.abort();
                    dataTableApi.search(self.value).draw();
                }, 1000);
                return;
            });
        }
    });
};

var editorInit = function () {
    $('.html-editor').each(function () {
        var $textarea = $(this);
        var textareaConfigName = $textarea.data('editor-config');

        var config = editorConfig.getGlobalConfig(textareaConfigName);

        if (!config) {
            config = editorConfig.getConfig();
        }

        $textarea.summernote(config);
    });
};

$(document).ready(function () {
    // editor
    editorInit();

    /* Data tables custom error handling */
    dataTable.setTableErrorMode('none');

    /* Draw data tables */
    $('.gui-table-data').on('error.dt', dataTable.onError).dataTable(dataTable.defaultConfiguration);

    $('.gui-table-data').on('draw.dt', function () {
        var windowWidth = $(document).width(),
            windowHeight = $(document).height(),
            $toggleWrap = $(this).find('.dropdown'),
            $toggleDropdown;

        $toggleWrap.on('show.bs.dropdown', function () {
            $toggleDropdown = $(this).find('.dropdown-menu');

            var $button = $(this).find('.dropdown-toggle'),
                buttonWidth = $button.width(),
                buttonHeight = $button.height(),
                buttonTopOffset = $button.offset().top,
                buttonLeftOffset = $button.offset().left,
                dropdownWidth = $toggleDropdown.width(),
                dropdownHeight = $toggleDropdown.height(),
                requiredWidth = buttonLeftOffset + dropdownWidth,
                requiredHeight = buttonTopOffset + buttonHeight + dropdownHeight,
                dropdownPositionStyles = {
                    top: buttonTopOffset + buttonHeight + 5 + 'px',
                    left: buttonLeftOffset + 'px',
                    display: 'block',
                    zIndex: '10000',
                };

            if (requiredWidth >= windowWidth) {
                dropdownPositionStyles.left = buttonLeftOffset + buttonWidth - dropdownWidth + 'px';
            }

            if (requiredHeight >= windowHeight) {
                dropdownPositionStyles.top = buttonTopOffset - dropdownHeight - 11 + 'px';
            }

            $('body').append($toggleDropdown.css(dropdownPositionStyles).detach());
        });

        $toggleWrap.on('hidden.bs.dropdown', function () {
            $(this).append($toggleDropdown.removeAttr('style').detach());
        });
    });

    /* Draw data tables without search */
    $('.gui-table-data-no-search').on('error.dt', dataTable.onError).dataTable(dataTable.noSearchConfiguration);

    /* All elements with the same class will have the same height */
    $('.fix-height').sprykerFixHeight();

    $('.spryker-form-autocomplete').each(function (key, value) {
        var autoCompletedField = $(value);
        if (autoCompletedField.data('url') === 'undefined') {
            return;
        }

        if (autoCompletedField.hasClass('ui-autocomplete')) {
            autoCompletedField.autocomplete('destroy');
        }

        autoCompletedField.autocomplete({
            source: autoCompletedField.data('url'),
            minLength: 3,
        });
    });

    $('.table-dependency tr').hover(
        function () {
            $(this).addClass('warning');
        },
        function () {
            $(this).removeClass('warning');
        },
    );
    $('.table-dependency .btn-xs').hover(
        function () {
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
        },
        function () {
            $(this).addClass('btn-default');
            $(this).removeClass('btn-primary');
        },
    );

    $('.dropdown-toggle').dropdown();

    $('.more-history').click(function (e) {
        e.preventDefault();
        var idProductItem = $(this).data('id');
        var $history = $('#history_details_' + idProductItem);
        var $button = $('#history-btn-' + idProductItem);
        var isHidden = $history.hasClass('hidden');

        $history.toggleClass('hidden', !isHidden);
        $button.toggleClass('is-hidden', !isHidden);
        $button.toggleClass('is-shown', isHidden);
    });

    /* Init Select2 combobox */
    select2combobox();

    /* Init tabs */
    $('.tabs-container').each(function (index, item) {
        new Tabs(item, dataTable.onTabChange);
    });

    /* Init translation copy fields */
    new TranslationCopyFields();

    /* Init iboxes */
    new Ibox();

    safeChecks.addSafeSubmitCheck();
    safeChecks.addSafeDatetimeCheck();

    initFormattedNumber();
    initFormattedMoney();
});

$(window).on('load', function () {
    dataTablesSearchDelay();
});
