/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SELECTOR_CATEGORY_LOADER = '#category-loader';
var SELECTOR_CATEGORY_NODE_TREE = '#category-node-tree';
var SELECTOT_CATEGORY_LIST = '#categories-list';

var showLoaderBar = function () {
    $(SELECTOR_CATEGORY_LOADER).removeClass('hidden');
};

var closeLoaderBar = function () {
    $(SELECTOR_CATEGORY_LOADER).addClass('hidden');
};

/**
 * @param idCategoryNode
 *
 * @return {void}
 */
SprykerAjax.getCategoryTreeByIdCategoryNode = function (idCategoryNode) {
    var options = {
        'id-category-node': idCategoryNode,
    };
    this.setUrl('/category/index/node').setDataType('html').ajaxSubmit(options, 'displayCategoryNodesTree');
};

/**
 * @param ajaxResponse
 *
 * @return {void}
 */
SprykerAjax.updateCategoryNodesOrder = function (serializedCategoryNodes) {
    showLoaderBar();
    this.setUrl('/category/node/reorder').ajaxSubmit(
        {
            nodes: serializedCategoryNodes,
        },
        'updateCategoryNodesOrder',
    );
};

/**
 * @param ajaxResponse
 *
 * @return {void}
 */
SprykerAjaxCallbacks.displayCategoryNodesTree = function (ajaxResponse) {
    $(SELECTOR_CATEGORY_NODE_TREE).removeClass('hidden');
    $(SELECTOT_CATEGORY_LIST).html(ajaxResponse);
    closeLoaderBar();
};

/**
 * @param ajaxResponse
 *
 * @return {boolean}
 */
SprykerAjaxCallbacks.updateCategoryNodesOrder = function (ajaxResponse) {
    closeLoaderBar();

    var isSuccessResponse = ajaxResponse.code === this.codeSuccess;
    var alertTitle = isSuccessResponse ? 'Success' : 'Error';
    var alertType = isSuccessResponse ? 'success' : 'error';
    swal({
        title: alertTitle,
        text: ajaxResponse.message,
        type: alertType,
    });

    return isSuccessResponse;
};

module.exports = {
    showLoaderBar: showLoaderBar,
    closeLoaderBar: closeLoaderBar,
};
