/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('../../sass/main.scss');

$(document).ready(function() {

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

    /**
     * @param $select
     * @param term
     */
    function select2_search ($select, term) {
        $select.select2('open');

        // Get the search box within the dropdown or the selection
        // Dropdown = single, Selection = multiple
        var $search = $select.data('select2').dropdown.$search || $select.data('select2').selection.$search;
        // This is undocumented and may change in the future

        $search.val(term);
        $search.trigger('keyup');
    }

    /**
     * @param event
     */
    function addAnotherImageCollection(event) {
        event.preventDefault();

        var prototypeTemplate = $(event.target).closest('[data-image-collection-prototype]');

        var imageSet = $(event.target).closest('.image-set');
        var imageSetIndex = imageSet.data('imageSetIndex');
        var imageCollectionIndex = imageSet.find('.image-collection').length;

        var newOptionFormHTML = prototypeTemplate
            .data('imageCollectionPrototype')
            .replace(/__image_set_name__/g, imageSetIndex)
            .replace(/__name__/g, imageCollectionIndex);

        newOptionFormHTML = setSortOrderFieldValue(newOptionFormHTML);

        imageSet.find('.image-collection-container').append($(newOptionFormHTML));
    }

    /**
     * @param event
     */
    function addAnotherImageSet(event) {
        event.preventDefault();

        var prototypeTemplate = $(event.target).closest('[data-image-set-prototype][data-image-collection-prototype]');
        var imageSetIndex = prototypeTemplate.data('currentImageSetIndex') + 1;
        prototypeTemplate.data('currentImageSetIndex', imageSetIndex);

        var imageSetPrototype = prototypeTemplate
            .data('imageSetPrototype')
            .replace(/__image_set_name__/g, imageSetIndex);

        var imageSet = $(imageSetPrototype);
        imageSet.data('imageSetIndex', imageSetIndex);
        $(event.target).before(imageSet);

        var imageCollectionPrototype = prototypeTemplate
            .data('imageCollectionPrototype')
            .replace(/__image_set_name__/g, imageSetIndex)
            .replace(/__name__/g, 0);

        imageCollectionPrototype = setSortOrderFieldValue(imageCollectionPrototype);

        imageSet.find('.image-collection-container').append($(imageCollectionPrototype));
    }

    /**
     * @param imageCollectionPrototype
     */
    function setSortOrderFieldValue(imageCollectionPrototype) {
        imageCollectionPrototype = $($.parseHTML(imageCollectionPrototype));

        var sortOrderField = imageCollectionPrototype.find('[data-sort-order]');
        sortOrderField.val(sortOrderField.data('sort-order'));

        return imageCollectionPrototype;
    }

    /**
     * @param event
     */
    function deleteImageSet(event) {
        event.preventDefault();

        $(this).closest('.image-set').remove();
    }

    /**
     * @param event
     */
    function deleteImageCollection(event) {
        event.preventDefault();

        $(this).closest('.image-collection').remove();
    }

    /**
     * Init image set index
     */
    $('.image-set').each(function(i, imageSet) {
        var imageSetIndex = $(imageSet).closest('[data-image-set-prototype]').find('.image-set').index(imageSet);

        $(imageSet).data('imageSetIndex', imageSetIndex);
    });
    $('.image-set-container').each(function(i, imageSetContainer) {
        var currentImageSetIndex = $(imageSetContainer).find('.image-set').length - 1;

        $(imageSetContainer).data('currentImageSetIndex', currentImageSetIndex);
    });

    /**
     * Register global event listeners
     */
    $('body')
        .on('click', '.add-another-image-set', addAnotherImageSet)
        .on('click', '.add-another-image-collection', addAnotherImageCollection)
        .on('click', '.remove-image-set', deleteImageSet)
        .on('click', '.remove-image-collection', deleteImageCollection);

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
});
