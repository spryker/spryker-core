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
            url: 'http://zed.de.spryker.dev/product-management/attribute/suggest/',
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
    });

    $('.spryker-form-select2combobox.ajax.tags').select2({
        tags: true,
        ajax: {
            url: 'http://zed.de.spryker.dev/product-management/attribute/suggest/',
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
        var $checkbox = $(this);
        var $input = $checkbox
            .parents('.attribute_metadata_row')
            .find('.attribute_metadata_value');

        if (!$checkbox.prop('checked') && !$checkbox.prop('disabled')) {
            $input.prop('readonly', !$checkbox.prop('checked'));

            if ($input.hasClass('spryker-form-select2combobox')) {
                $input.prop('disabled', !$checkbox.prop('checked'));
            }
        }
    });

    $('.attribute_metadata_checkbox')
        .off('click')
        .on('click', function() {
            var $checkbox = $(this);
            var $input = $checkbox
                .parents('.attribute_metadata_row')
                .find('.attribute_metadata_value');

            $input.prop('readonly', !$checkbox.prop('checked'));

            if ($input.hasClass('spryker-form-select2combobox')) {
                $input.prop('disabled', !$checkbox.prop('checked'));

                if ($checkbox.prop('checked')) {
                    //fixes focus issues
                    setTimeout(function() {
                        $input.select2('focus');
                    }, 0);
                }
            } else {
                $input.focus();
            }
        });


    $('.kv_attribute_autocomplete').each(function(key, value) {
        var input = $(value);
        var id = input.attr('id_attribute') || null;

        input.autocomplete({
            minLength: 0,
            source: function(request, response) {
                $.ajax({
                    url: 'http://zed.de.spryker.dev/product-management/attribute/suggest/',
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

    $(".kv_autocomplete_form").submit(function(e) {
        var form = $(this);
        $('.kv_attribute_autocomplete').each(function(key, value) {
            var $input = $(this);
            var hidden = $input.next();
            var inputValue = $input.attr('data-value');
            var name = hidden.attr('name');
                hidden.val(inputValue);
        });
    });

    $('.add-another-image').click(function(event) {
        event.preventDefault();

        var $target = $(event.target);
        var $parent1 = $target.parent();
        var $parent2 = $parent1.parent();
        var prototypeTemplate = $parent2.find('div.image_set_list');

        var valueCount = prototypeTemplate.data('valuecount');
        var newOptionFormHTML = prototypeTemplate.data('prototype');
            newOptionFormHTML = newOptionFormHTML.replace(/__name__/g, valueCount);
        var newOptionForm = $(jQuery.parseHTML(newOptionFormHTML)[0]);
        newOptionForm.attr('class', 'sep_down');

        prototypeTemplate.parent(prototypeTemplate).append(newOptionForm);

        valueCount++;
        prototypeTemplate.data('valuecount', valueCount);
    });

    $('.add-another-image-set').click(function(event) {
        event.preventDefault();

        var $target = $(event.target);
        var $parent1 = $target.parent();
        var $parent2 = $parent1.parent();
        var $parent3 = $parent2.parent();


        var prototypeTemplate = $parent3.find('div.image_set_block');
        var valueCount = prototypeTemplate.data('valuecount');
        var newOptionFormHTML = prototypeTemplate.data('prototype');
        newOptionFormHTML = newOptionFormHTML.replace(/__image_set_name__/g, valueCount);
        var newOptionForm = $(jQuery.parseHTML(newOptionFormHTML)[0]);
        valueCount++;
        prototypeTemplate.data('valuecount', valueCount);


        var prototypeList = prototypeTemplate.find('div.image_set_list');

        console.log('prototypeList', prototypeList);


        $parent3.append(newOptionForm);
    });

    $('.slick_demo_1').slick({
        dots: true
    });

});