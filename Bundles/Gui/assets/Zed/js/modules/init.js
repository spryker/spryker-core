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
var timeoutId = 0;
import { Dropzone } from './libs/dropzone';
import { FormSubmitter } from './libs/form-submitter';
import { DatePicker } from './libs/datepicker';
import { ImageUploader } from './libs/image-uploader';
import { Highlight } from './libs/highlight';
import { DownloadAction } from './libs/download-action';
import { CopyAction } from './libs/copy-action';
import FormWithExternalFields from './form-with-external-fields';

var dataTablesSearchDelay = function () {
    var dataTablesWrapper = $('.dataTables_wrapper');
    dataTablesWrapper.each(function (index, wrapper) {
        var searchInput = $(wrapper).find('input[type="search"]');
        var dataTable = $(wrapper).find('.gui-table-data');
        var dataTableApi = dataTable.dataTable().api();

        if (searchInput.length && dataTable.length) {
            searchInput.unbind().bind('input', function (e) {
                var self = this;

                clearTimeout(timeoutId);
                timeoutId = setTimeout(function () {
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

    $('.gui-table-data').on('init.dt', function (e, settings) {
        const wrapper = $(e.target).closest('.dataTables_wrapper');
        const searchInput = wrapper.find('.dataTables_filter input[type="search"]');
        searchInput.attr('data-qa', 'table-search');
    });

    $('.gui-table-data').on('draw.dt', function (e, settings) {
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
        var api = new $.fn.dataTable.Api(settings);
        var tableBody = $(e.target).find('tbody');
        var searchRow = '<tr';

        if ($(e.target).find('tr').slice(-1)[0].className === 'even') {
            searchRow += ' class="odd"';
        } else {
            searchRow += ' class="even"';
        }

        searchRow += '>';

        var isSearchable = false;

        api.columns().every(function (columnIndex) {
            if (typeof api.ajax.params() === 'undefined') {
                return;
            }

            searchRow += '<td>';
            if (api.ajax.params()['columns'][columnIndex]['searchable']) {
                searchRow +=
                    '<input type="text" style="width:80%" class="form-control input-sm column-search" placeholder="Search" data-column-index="' +
                    columnIndex +
                    '" value="' +
                    api.ajax.params()['columns'][columnIndex]['search']['value'] +
                    '"/>';

                isSearchable = true;
            }
            searchRow += '</td>';
        });
        searchRow += '</tr>';

        if (isSearchable === true) {
            tableBody.append(searchRow);

            tableBody
                .find('input')
                .off('keyup change click')
                .on('keyup', function (e) {
                    var self = this;

                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(function () {
                        api.settings()[0].jqXHR.abort();
                        api.columns(parseInt(self.getAttribute('data-column-index')))
                            .search(self.value, false, false)
                            .draw();
                    }, 1000);
                });
        }

        document.querySelectorAll('.paginate_button.disabled > a').forEach((element) => {
            element.setAttribute('tabindex', '-1');
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

    if (window.spryker?.isBootstrapVersionLatest) {
        $('a[data-toggle="tab"]').on('click', function (event) {
            event.preventDefault();
        });
    } else {
        $('.dropdown-toggle').dropdown();
    }

    /* Init translation copy fields */
    new TranslationCopyFields();

    /* Init iboxes */
    new Ibox();

    safeChecks.addSafeSubmitCheck();
    safeChecks.addSafeDatetimeCheck();

    initFormattedNumber();
    initFormattedMoney();

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    new DatePicker().init();
    new Dropzone();
    new FormSubmitter();
    new ImageUploader();
    new FormWithExternalFields();
    new Highlight();
    new CopyAction();
    new DownloadAction();
});

$(window).on('load', function () {
    dataTablesSearchDelay();
});
