/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('jstree');

var $treeContainer = $('#category-tree-container');
var $treeContent = $('#category-tree-content');
var $treeProgressBar = $('#category-tree-loader');
var $treeSearchField = $('#category-tree-search-field');
var $formProgressBar = $('#product-category-filter-form-loader');
var $iframe = $('#product-category-filter-iframe');


var ajaxRequest;
var currentlySelectedNodeId;
var treeSearchTimeout = false;

var config = {
    categoryTreeUrl: '/product-category-filter-gui/category-tree',
    productCategoryFilterUrl: '/product-category-filter-gui/product-category-filter',
    categoryTreeNodeTypes: {
        'default': {
            'icon': 'fa fa-folder'
        },
        'category': {
            'icon': 'fa fa-sitemap'
        },
        'edited-category': {
            'icon': 'fa fa-edit'
        }
    }
};

/**
 * @return {void}
 */
function initialize() {
    $treeSearchField.keyup(onTreeSearchKeyup);
}

/**
 * @param {int} idCategory
 * @param {int|null} selected
 * @param {boolean} skipFormLoad
 *
 * @return {void}
 */
function loadTree(idCategory, selected, skipFormLoad)  {
    $treeProgressBar.removeClass('hidden');
    $treeContainer.addClass('hidden');

    if (ajaxRequest) {
        ajaxRequest.abort();
    }

    ajaxRequest = $.get(config.categoryTreeUrl, {'id-root-node': idCategory}, createTreeLoadHandler(idCategory, selected, skipFormLoad))
        .always(function() {
            $treeProgressBar.addClass('hidden');
        });
}

/**
 * @return {void}
 */
function resetTree()  {
    if (ajaxRequest) {
        ajaxRequest.abort();
    }

    $treeContent.html('');
    resetForm();
}

/**
 * @param {int} idCategory
 * @param {int|null} selected
 * @param {boolean} skipFormLoad
 *
 * @returns {Function}
 */
function createTreeLoadHandler(idCategory, selected, skipFormLoad) {
    return function(response) {
        $treeContent.html(response);

        initJsTree();

        $treeContainer.removeClass('hidden');

        if (skipFormLoad) {
            selectNode(selected);
            setListeners(idCategory);
        } else {
            setListeners(idCategory);
            selectNode(selected);
        }
    }
}

/**
 * @return {void}
 */
function initJsTree() {
    $('#category-tree').jstree({
        'plugins': ['types', 'wholerow', 'dnd', 'search'],
        'types': config.categoryTreeNodeTypes,
        'dnd': {
            'is_draggable': false
        }
    });
}

/**
 * @param {int} idCategoryNode
 *
 * @return {void}
 */
function selectNode(idCategoryNode) {
    $('#category-tree').jstree(true).select_node(getNodeName((idCategoryNode ? idCategoryNode : 0)));
}

/**
 * @param {int} id
 *
 * @return string
 */
function getNodeName(id) {
    return 'category-node-' + id;
}

/**
 * @return {void}
 */
function setListeners() {
    $('#category-tree').on('select_node.jstree', function(e, data) {
        if(data.node.data.rootNode) {
            $('#category-tree').jstree(true).deselect_node(data.node);
            currentlySelectedNodeId = data.node.data.idCategory;
            resetForm();
            return;
        }

        var idCategory = data.node.data.idCategory;
        var nodesWithSameCategoryId = document.querySelectorAll('[data-id-category="' + idCategory + '"]');
        var nodeIds = [];

        for(var i=0; i < nodesWithSameCategoryId.length; i++) {
            nodeIds.push(nodesWithSameCategoryId[i].id)
        }

        $('#category-tree').jstree(true).select_node(nodeIds, true);
        if(currentlySelectedNodeId === idCategory) {
            return;
        }

        currentlySelectedNodeId = idCategory;
        loadForm(idCategory);
    });

    window.document.addEventListener(
        'categoryChanged',
        function(e) {
            $("[data-id-category='" + e.detail.idCategory + "']").children('a').children('i')
                .removeClass(config.categoryTreeNodeTypes.default.icon)
                .addClass(config.categoryTreeNodeTypes['edited-category'].icon);
            },
        false
    );

    window.document.addEventListener(
        'resetCategory', function(e) {
            $("[data-id-category='" + e.detail.idCategory + "']").children('a').children('i')
                .removeClass(config.categoryTreeNodeTypes['edited-category'].icon)
                .addClass(config.categoryTreeNodeTypes.default.icon);
        },
        false
    );
}

/**
 * @param {int} idCategoryNode
 *
 * @return {void}
 */
function loadForm(idCategoryNode)  {
    var data = {
        'id-category-node': idCategoryNode
    };
    var uri = config.productCategoryFilterUrl;
    var url = uri + '?' + $.param(data);

    $iframe.addClass('hidden');
    $formProgressBar.removeClass('hidden');

    $iframe.off('load').on('load', onIframeLoad);
    $iframe[0].contentWindow.location.replace(url);
}

/**
 * @return {void}
 */
function resetForm()  {
    $iframe.addClass('hidden');
}

/**
 * @return {void}
 */
function onIframeLoad() {
    changeIframeHeight();
    $formProgressBar.addClass('hidden');
    $iframe.removeClass('hidden');

    $($iframe[0].contentWindow).on('resize', changeIframeHeight);

    // tree reloading
    var treeReloader = $iframe.contents().find('#category-tree-reloader');
    if (treeReloader.length) {
        loadTree($(treeReloader[0]).data('idCategory'), $(treeReloader[0]).data('idSelectedTreeNode'), true);
    }
}

/**
 * @return {void}
 */
function changeIframeHeight() {
    var iframeContentHeight = $iframe[0].contentWindow.document.body.scrollHeight;
    $iframe.height(iframeContentHeight);
}

/**
 * @return {void}
 */
function onTreeSearchKeyup() {
    if(treeSearchTimeout) {
        clearTimeout(treeSearchTimeout);
    }
    treeSearchTimeout = setTimeout(function () {
        $('#category-tree').jstree(true).search($treeSearchField.val());
    }, 250);
}


/**
 * Open public methods
 */
module.exports = {
    initialize: initialize,
    load: loadTree,
    reset: resetTree
};
