/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
require('../../sass/main.scss');

/**
 * @param data
 * @param params
 * @returns {{results: *, pagination: {more: (boolean|number)}}}
 */
function processAjaxResult(data, params) {
    //{"id_attribute":1,"values":[{"id_product_management_attribute_value":1,"fk_locale":66,"value":"intel-atom-quad-core","translation":"Intel Atom Z3560 Quad-Core US"}]}
    // parse the results into the format expected by Select2
    // since we are using custom formatting functions we do not need to
    // alter the remote JSON data, except to indicate that infinite
    // scrolling can be used
    params.page = params.page || 1;

    return {
        results: data.values,
        pagination: {
            more: (params.page * 30) < data.total || 0
        }
    };
}

function removeActionHandler() {
    var $link = $(this);
    var id = $link.data('id');
    var action = $link.data('action');
    var dataTable = $('#productAttributesTable').DataTable();

    dataTable.row($link.parents('tr')).remove().draw();

    return false;
}

function updateKV() {
    $('.kv_attribute_autocomplete').each(function(key, value) {
        var input = $(value);
        var id = input.attr('id_attribute') || null;
        var locale_code = input.attr('locale_code') || null;

        input.autocomplete({
            minLength: 0,
            source: function(request, response) {
                $.ajax({
                    url: '/product-management/attribute/suggest/',
                    dataType: "json",
                    data: {
                        q: request.term,
                        id: id,
                        locale_code: locale_code
                    },
                    success: function(data) {
                        response($.map(data.values, function (item) {
                            return {
                                label: item.text,
                                value: item.id
                            };
                        }));
                    }
                });
            },
            select: function(event, ui) {
                var input = $(this);
                input.val(ui.item.label);
                input.attr('data-value', ui.item.value);

                return false;
            },
            focus: function(event, ui) {
                var input = $(this);
                input.val(ui.item.label);
                input.attr('data-value', ui.item.value);

                return false;
            }
        });
    });
}


function loadMetaAttributes() {
    var json = $('#metaAttributesJson').html();
    var data = JSON.parse(json);

    return data;
}

function loadAttributeValues() {
    var json = $('#productAttributeValuesJson').html();
    var data = JSON.parse(json);

    return data;
}

function loadLocales() {
    var json = $('#localesJson').html();
    var data = JSON.parse(json);

    return data;
}

$(document).ready(function() {

    var attributesValues = loadAttributeValues();
    var metaAttributes = loadMetaAttributes();
    var localeCollection = loadLocales();

    console.log(
        'locales', localeCollection,
        'meta', metaAttributes,
        'attributeValues', attributesValues
    );

    $('.spryker-form-select2combobox:not([class=".tags"]):not([class=".ajax"])').select2({

    });

    $('.spryker-form-select2combobox.tags:not([class=".ajax"])').select2({
        tags: true
    });

    $('.spryker-form-select2combobox.ajax:not([class=".tags"])').select2({
        tags: false,
        preLoaded: false,
        ajax: {
            url: '/product-management/attribute/suggest/',
            dataType: 'json',
            delay: 250,
            cache: true,
            data: function (params) {
                var p = {
                    q: params.term,
                    page: params.page,
                    id: this.attr('id_attribute'),
                    locale_code: this.attr('locale_code')
                };

                return p;
            },
            processResults: processAjaxResult
        },
        minimumInputLength: 1
    });

    $('.spryker-form-select2combobox.ajax.tags').select2({
        tags: true,
        ajax: {
            url: '/product-management/attribute/suggest/',
            dataType: 'json',
            delay: 250,
            cache: true,
            preLoaded: false,
            data: function (params) {
                var p = {
                    q: params.term,
                    page: params.page,
                    id: this.attr('id_attribute'),
                    locale_code: this.attr('locale_code')
                };

                return p;
            },
            processResults: processAjaxResult
        },
        minimumInputLength: 1
    });

    $('.spryker-form-select2combobox').select2({
        tags: true
    });

    $('#addButton').on('click', function() {
        var input = $('#attribute_form_key');
        var dataTable = $('#productAttributesTable');
        var idAttribute = input.attr('data-value');
        var key = input.val().trim();

        key = key.replace(/([^a-z0-9\_\-\:]+)/gi, '').toLowerCase();

        if (key === '' || !idAttribute) {
            alert('Please select attribute key first');
            return false;
        }

        var dataToAdd = [];
        dataToAdd.push(key);
        for (var i=0; i<localeCollection.length; i++) {
            var localeCode = localeCollection[i];
            if (localeCode === '_') {
                localeCode = null;
            }

            dataToAdd.push('<input type="text" class="spryker-form-autocomplete form-control ui-autocomplete-input kv_attribute_autocomplete" value="" id_attribute="'+idAttribute+'" locale_code="'+localeCode+'">');
        }
        dataToAdd.push('<a data-id="' + key + '" href="#" class="btn btn-xs remove-item">Remove</a>');

        dataTable.DataTable().
            row.
            add(dataToAdd)
            .draw();

        $('.remove-item').off('click');
        $('.remove-item').on('click', removeActionHandler);

        updateKV();

        return false;
    });

    $('.remove-item').off('click');
    $('.remove-item').on('click', removeActionHandler);

    updateKV();

    loadAttributeValues();

    $('#attribute_form_key').autocomplete({
        minLength: 0,
        source: function(request, response) {
            $.ajax({
                url: '/product-attribute-gui/suggest/keys',
                dataType: "json",
                data: {
                    q: request.term,
                },
                success: function(data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.value,
                            value: item.id
                        };
                    }));
                }
            });
        },
        select: function(event, ui) {
            var input = $(this);
            input.val(ui.item.label);
            input.attr('data-value', ui.item.value);

            return false;
        },
        focus: function(event, ui) {
            var input = $(this);
            input.val(ui.item.label);
            input.attr('data-value', ui.item.value);

            return false;
        }
    });

});
