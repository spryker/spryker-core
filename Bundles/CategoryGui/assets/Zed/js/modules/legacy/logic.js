/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

window.serializedList = {};

var categoryHelper = require('./helpers.js');
var STORE_SELECTOR_ID = '#category_store_relation_id_stores';
var STORE_SELECTOR_LOADER_CLASS_NAME = '.store-seletor-loader';
var STORE_SELECTOR_ACTION_URL_ATTRIBUTE = 'action-url';
var STORE_SELECTOR_ACTION_EVENT_ATTRIBUTE = 'action-event';
var STORE_SELECTOR_ACTION_FIELD_ATTRIBUTE = 'action-field';

$(document).ready(function () {
    var triggeredFirstEvent = false;

    $('#root-node-table').on('click', 'tbody tr', function () {
        categoryHelper.showLoaderBar();
        var idCategoryNode = $(this).children('td:first').text();
        SprykerAjax.getCategoryTreeByIdCategoryNode(idCategoryNode);
    });

    $('#category-node-tree').on('click', '.category-tree', function (event) {
        event.preventDefault();
        categoryHelper.showLoaderBar();
        var idCategoryNode = $(this).attr('id').replace('node-', '');
        SprykerAjax.getCategoryTreeByIdCategoryNode(idCategoryNode);
    });

    $('.gui-table-data-category').dataTable({
        bFilter: false,
        createdRow: function (row, data, index) {
            if (triggeredFirstEvent === false) {
                categoryHelper.showLoaderBar();
                var idCategoryNode = data[0];
                SprykerAjax.getCategoryTreeByIdCategoryNode(idCategoryNode);
                triggeredFirstEvent = true;
            }
        },
    });

    var updateOutput = function (e) {
        var list = e.length ? e : $(e.target);
        window.serializedList = window.JSON.stringify(list.nestable('serialize'));
    };

    $('#nestable')
        .nestable({
            group: 1,
            maxDepth: 1,
        })
        .on('change', updateOutput);

    $('.save-categories-order').click(function () {
        SprykerAjax.updateCategoryNodesOrder(serializedList);
    });

    var storeSelector = $(STORE_SELECTOR_ID);
    var storeSelectorLoader = storeSelector.parent().find(STORE_SELECTOR_LOADER_CLASS_NAME);
    var storeSelectorActionUrl = storeSelector.attr(STORE_SELECTOR_ACTION_URL_ATTRIBUTE);
    var storeSelectorActionEvent = storeSelector.attr(STORE_SELECTOR_ACTION_EVENT_ATTRIBUTE);
    var storeSelectorActionFieldName = storeSelector.attr(STORE_SELECTOR_ACTION_FIELD_ATTRIBUTE);
    var createCategoryFormName = storeSelector.closest('form')[0].name;
    var parentCategorySelector = $(`[name='${createCategoryFormName}[${storeSelectorActionFieldName}]']`);
    var parentCategoryId = parentCategorySelector.select2('data')[0].id;

    if (!parentCategoryId) {
        storeSelector.attr('disabled', true);
    }

    parentCategorySelector.on(storeSelectorActionEvent, function (event) {
        var selectedCategoryId = $(this).select2('data')[0].id;

        if (selectedCategoryId) {
            $.ajax({
                url: `${storeSelectorActionUrl}?id-category-node=${selectedCategoryId}`,
                success: function(data) {
                    storeSelector.empty();

                    data.forEach(function (item) {
                        var optionTemplate = `<option value="${item.id_store}">${item.name}</option>`;

                        if (!item.is_active) {
                            optionTemplate = `<option disabled value="${item.id_store}">${item.name}</option>`
                        }

                        storeSelector.append(optionTemplate);
                        storeSelector.attr('disabled', false);
                    })
                },
                beforeSend: function(){
                    storeSelectorLoader.addClass('active');
                },
                complete: function(){
                    storeSelectorLoader.removeClass('active');
                }
            });

            return;
        }

        storeSelector.attr('disabled', true);
    })
});
