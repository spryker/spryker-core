/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

window.serializedList = {};

var categoryRequestHelper = require('./request-helper.js');
var STORE_SELECTOR_ID = '#category_store_relation_id_stores';
var STORE_FORM_NAME = 'category';
var STORE_SELECTOR_LOADER_CLASS_NAME = '.relation-selector-loader';
var STORE_SELECTOR_ACTION_URL_ATTRIBUTE = 'action-url';
var STORE_SELECTOR_ACTION_EVENT_ATTRIBUTE = 'action-event';
var STORE_SELECTOR_ACTION_FIELD_ATTRIBUTE = 'action-field';

var SELECTOR_ROOT_NODE_TABLE = '#root-node-table';
var SELECTOR_CATEGORY_NODE_TREE = '#category-node-tree';
var SELECTOR_GUI_TABLE_DATA_CATEGORY = '.gui-table-data-category';
var SELECTOR_NESTABLE = '#nestable';
var SELECTOR_SAVE_CATEGORIES_ORDER = '.save-categories-order';

/**
 * @return {void}
 */
var handleStoreSelector = function () {
    var storeSelector = $(STORE_SELECTOR_ID);
    var storeSelectorActionFieldName = storeSelector.attr(STORE_SELECTOR_ACTION_FIELD_ATTRIBUTE);
    var parentCategorySelector = $("[name='" + STORE_FORM_NAME + '[' + storeSelectorActionFieldName + "]']");

    var parentCategoryData = parentCategorySelector.select2('data');
    if (!parentCategoryData) {
        return;
    }

    var storeSelectorLoader = storeSelector.parent().find(STORE_SELECTOR_LOADER_CLASS_NAME);
    var storeSelectorActionUrl = storeSelector.attr(STORE_SELECTOR_ACTION_URL_ATTRIBUTE);
    var storeSelectorActionEvent = storeSelector.attr(STORE_SELECTOR_ACTION_EVENT_ATTRIBUTE);
    var parentCategoryId = parentCategoryData[0].id;
    if (!parentCategoryId) {
        storeSelector.prop('disabled', true);
    }

    parentCategorySelector.on(storeSelectorActionEvent, function (event) {
        var selectedCategoryId = $(this).select2('data')[0].id;

        if (selectedCategoryId) {
            $.ajax({
                url: storeSelectorActionUrl + '?id-category-node=' + selectedCategoryId,
                success: function (data) {
                    storeSelector.empty();

                    data.forEach(function (item) {
                        var optionItem = $('<option></option>')
                            .prop('value', item.id_store)
                            .prop('disabled', !item.is_active)
                            .text(item.name);

                        storeSelector.append(optionItem);
                    });

                    storeSelector.prop('disabled', false);
                },
                beforeSend: function () {
                    storeSelectorLoader.addClass('active');
                },
                complete: function () {
                    storeSelectorLoader.removeClass('active');
                },
            });

            return;
        }

        storeSelector.prop('disabled', true);
    });
};

$(document).ready(function () {
    var isFirstEventTriggered = false;

    var selectorRootNodeTable = $(SELECTOR_ROOT_NODE_TABLE);
    var selectorCategoryNodeTree = $(SELECTOR_CATEGORY_NODE_TREE);
    var selectorCategoryGuiTableDataCategory = $(SELECTOR_GUI_TABLE_DATA_CATEGORY);
    var selectorNestable = $(SELECTOR_NESTABLE);
    var selectorSaveCategoriesOrder = $(SELECTOR_SAVE_CATEGORIES_ORDER);

    selectorRootNodeTable.on('click', 'tbody tr', function () {
        categoryHelper.showLoaderBar();
        var idCategoryNode = $(this).children('td:first').text();
        SprykerAjax.getCategoryTreeByIdCategoryNode(idCategoryNode);
    });

    selectorCategoryNodeTree.on('click', '.category-tree', function (event) {
        event.preventDefault();
        categoryHelper.showLoaderBar();
        var idCategoryNode = $(this).attr('id').replace('node-', '');
        SprykerAjax.getCategoryTreeByIdCategoryNode(idCategoryNode);
    });

    selectorCategoryGuiTableDataCategory.dataTable({
        bFilter: false,
        createdRow: function (row, data, index) {
            if (isFirstEventTriggered !== false) {
                return;
            }

            categoryHelper.showLoaderBar();
            var idCategoryNode = data[0];
            SprykerAjax.getCategoryTreeByIdCategoryNode(idCategoryNode);
            isFirstEventTriggered = true;
        },
    });

    var updateOutput = function (e) {
        var list = e.length ? e : $(e.target);
        window.serializedList = window.JSON.stringify(list.nestable('serialize'));
    };

    selectorNestable
        .nestable({
            group: 1,
            maxDepth: 1,
        })
        .on('change', updateOutput);

    selectorSaveCategoriesOrder.click(function () {
        SprykerAjax.updateCategoryNodesOrder(serializedList);
    });

    handleStoreSelector();
});
