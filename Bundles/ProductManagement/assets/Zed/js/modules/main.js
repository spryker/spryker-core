/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

require('ZedGui');
require('../../sass/main.scss');

$(document).ready(function() {

    $('.spryker-form-select2combobox:not([class=".tags"]):not([class=".ajax"])').select2({

    });

    $('.spryker-form-select2combobox.tags:not([class=".ajax"])').select2({
        tags: true
    });

    $('.spryker-form-select2combobox.ajax:not([class=".tags"])').select2({
        tags: false,
        ajax: {
            url: 'http://zed.de.spryker.dev/product-management/attributes/autocomplete/',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var p = {
                    q: params.term,
                    page: params.page,
                    id: this.attr('id_attribute')
                };

                return p;
            },
            processResults: function (data, params) {
                //{"id_attribute":1,"values":[{"id_product_management_attribute_value":1,"fk_locale":66,"value":"intel-atom-quad-core","translation":"Intel Atom Z3560 Quad-Core US"}]}
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                var values = [];
                for (var a=0; a<data.values.length; a++) {
                    var item = data.values[a];

                    values.push({
                        id: item.id_product_management_attribute_value,
                        text: item.translation || item.value
                    });
                }

                return {
                    results: values,
                    pagination: {
                        more: (params.page * 30) < data.total || 0
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 1
    });

    $('.spryker-form-select2combobox.ajax.tags').select2({
        tags: true,
        ajax: {
            url: 'http://zed.de.spryker.dev/product-management/attributes/autocomplete/',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var p = {
                    q: params.term,
                    page: params.page,
                    id: this.attr('id_attribute')
                };

                return p;
            },
            processResults: function (data, params) {
                //{"id_attribute":1,"values":[{"id_product_management_attribute_value":1,"fk_locale":66,"value":"intel-atom-quad-core","translation":"Intel Atom Z3560 Quad-Core US"}]}
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                var values = [];
                for (var a=0; a<data.values.length; a++) {
                    var item = data.values[a];

                    values.push({
                        id: item.id_product_management_attribute_value,
                        text: item.translation || item.value
                    });
                }

                return {
                    results: values,
                    pagination: {
                        more: (params.page * 30) < data.total || 0
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 1
    });


    $('.attribute_metadata_checkbox').each(function() {
        var $item = $(this);
        var $input = $item
            .parents('.attribute_metadata_row')
            .find('.attribute_metadata_value');

        if (!$item.prop('disabled')) {
            $input.prop('disabled', !$item.prop('checked'));
        }
    });

    $('.attribute_metadata_checkbox')
        .off('click')
        .on('click', function() {
            var $item = $(this);
            var input = $item
                .parents('.attribute_metadata_row')
                .find('.attribute_metadata_value');

            input.prop('disabled', !$item.prop('checked'));
            input.focus();
        });




});