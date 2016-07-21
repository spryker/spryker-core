/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

require('ZedGui');
require('../../sass/main.scss');

$(document).ready(function() {

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

    function select2_search ($select, term) {
        $select.select2('open');

        // Get the search box within the dropdown or the selection
        // Dropdown = single, Selection = multiple
        var $search = $select.data('select2').dropdown.$search || $select.data('select2').selection.$search;
        // This is undocumented and may change in the future

        $search.val(term);
        $search.trigger('keyup');
    }

    $('.spryker-form-select2combobox:not([class=".tags"]):not([class=".ajax"])').select2({

    });

    $('.spryker-form-select2combobox.tags:not([class=".ajax"])').select2({
        tags: true
    });

    $('.spryker-form-select2combobox.ajax:not([class=".tags"])').select2({
        tags: false,
        preLoaded: false,
        ajax: {
            url: 'http://zed.de.spryker.dev/product-management/attributes/suggest/',
            dataType: 'json',
            delay: 250,
            cache: true,
            data: function (params) {
                var p = {
                    q: params.term,
                    page: params.page,
                    id: this.attr('id_attribute')
                };

                return p;
            },
            processResults: processAjaxResult
        },
        minimumInputLength: 1
    })
        .on("DISALBED_select2:open", function (e) {

            var $select = $($(this).data('target'));
            console.log('select', $select.data);

            //$select.select2('data', null)
            //select v4 - wtf
/*
            $select.empty();
            $select.html('').select2({data: [{id: '', text: ''}]});
            $select.html('').select2({data: [
                {id: '', text: ''},
                {id: '1', text: 'Facebook'},
                {id: '2', text: 'Youtube'},
                {id: '3', text: 'Instagram'},
                {id: '4', text: 'Pinterest'}]
            });
*/
            debugger;
            var $search = $select.data('select2').dropdown.$search || $select.data('select2').selection.$search;
            // This is undocumented and may change in the future

            $search.val(term);
            $search.trigger('keyup');
            return;



            console.log('open', e, this);
            var id = $(this).attr('id_attribute');
            var self = $(this);
            if (self.attr('preLoaded')) return;

            $.ajax('http://zed.de.spryker.dev/product-management/attributes/suggest/', {
                dataType: 'json',
                data: 'q=&page=1&id=' + id
            }).done(function(data) {
                var processedResult = processAjaxResult(data, {});
                console.log(processedResult.results);
                self.select2({'data': processedResult.results});
                self.attr('preLoaded', true);
            });
        });

    $('.spryker-form-select2combobox.ajax.tags').select2({
        tags: true,
        ajax: {
            url: 'http://zed.de.spryker.dev/product-management/attributes/suggest/',
            dataType: 'json',
            delay: 250,
            cache: true,
            preLoaded: false,
            data: function (params) {
                var p = {
                    q: params.term,
                    page: params.page,
                    id: this.attr('id_attribute')
                };

                return p;
            },
            processResults: processAjaxResult
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


    $('.attribute_autocomplete').each(function(key, value) {
        var obj = $(value);
        if (obj.data('url') === 'undefined') {
            //return;
        }

        var id = obj.attr('id_attribute') || null;

        obj.autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: 'http://zed.de.spryker.dev/product-management/attributes/suggest/',
                    dataType: "json",
                    data: {
                        q: request.term,
                        id: id
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
            //source: obj.data('url'),
            minLength: 0
        });
    });

});