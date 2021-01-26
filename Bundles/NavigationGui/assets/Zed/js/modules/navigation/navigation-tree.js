/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('jstree');

var $treeContainer = $('#navigation-tree-container');
var $treeContent = $('#navigation-tree-content');
var $treeSearchField = $('#navigation-tree-search-field');
var $treeProgressBar = $('#navigation-tree-loader');
var $formProgressBar = $('#navigation-node-form-loader');
var $treeUpdateProgressBar = $('#navigation-tree-update-loader');
var $treeOrderSaveBtn = $('#navigation-tree-save-btn');
var $iframe = $('#navigation-node-form-iframe');

var ajaxRequest;
var treeSearchTimeout = false;

var config = {
    navigationTreeUrl: '/navigation-gui/tree',
    navigationNodeFormUrlPrefix: '/navigation-gui/node/',
    navigationTreeHierarchyUpdateUrl: '/navigation-gui/tree/update-hierarchy',
    navigationTreeNodeTypes: {
        default: {
            icon: 'fa fa-folder',
        },
        navigation: {
            icon: 'fa fa-list',
        },
        cms_page: {
            icon: 'fa fa-file-o',
        },
        category: {
            icon: 'fa fa-sitemap',
        },
        link: {
            icon: 'fa fa-link',
        },
        external_url: {
            icon: 'fa fa-external-link',
        },
    },
};

/**
 * @return {void}
 */
function initialize() {
    $treeOrderSaveBtn.on('click', onTreeSaveOrderClick);
    $treeSearchField.keyup(onTreeSearchKeyup);

    // Enable save order button on tree change
    $(document).bind('dnd_stop.vakata', function () {
        $treeOrderSaveBtn.removeAttr('disabled');
    });
}

/**
 * @param {int} idNavigation
 * @param {int|null} selected
 * @param {boolean} skipFormLoad
 *
 * @return {void}
 */
function loadTree(idNavigation, selected, skipFormLoad) {
    $treeProgressBar.removeClass('hidden');
    $treeContainer.addClass('hidden');

    if (ajaxRequest) {
        ajaxRequest.abort();
    }

    ajaxRequest = $.get(
        config.navigationTreeUrl,
        { 'id-navigation': idNavigation },
        createTreeLoadHandler(idNavigation, selected, skipFormLoad),
    ).always(function () {
        $treeProgressBar.addClass('hidden');
    });
}

/**
 * @return {void}
 */
function resetTree() {
    if (ajaxRequest) {
        ajaxRequest.abort();
    }

    $treeContent.html('');
    resetForm();
}

/**
 * @param {int} idNavigation
 * @param {int|null} selected
 * @param {boolean} skipFormLoad
 *
 * @returns {Function}
 */
function createTreeLoadHandler(idNavigation, selected, skipFormLoad) {
    return function (response) {
        $treeContent.html(response);

        initJsTree();

        $treeContainer.removeClass('hidden');

        if (skipFormLoad) {
            selectNode(selected);
            setNodeSelectListener(idNavigation);
        } else {
            setNodeSelectListener(idNavigation);
            selectNode(selected);
        }
    };
}

/**
 * @return {void}
 */
function initJsTree() {
    $('#navigation-tree').jstree({
        core: {
            check_callback: function (op, node, par, pos, more) {
                // disable drop on root level
                if (more && more.dnd && (op === 'move_node' || op === 'copy_node')) {
                    return !!more.ref.data.idNavigationNode;
                }

                return true;
            },
        },
        plugins: ['types', 'wholerow', 'dnd', 'search'],
        types: config.navigationTreeNodeTypes,
        dnd: {
            is_draggable: function (items) {
                var idNavigationNode = items[0].data.idNavigationNode;
                return !!idNavigationNode;
            },
        },
    });
}

/**
 * @param {int} idNavigationNode
 *
 * @return {void}
 */
function selectNode(idNavigationNode) {
    var nodeToSelect = 'navigation-node-' + (idNavigationNode ? idNavigationNode : 0);
    $('#navigation-tree').jstree(true).select_node(nodeToSelect);
}

/**
 * @param {int} idNavigation
 *
 * @return {void}
 */
function setNodeSelectListener(idNavigation) {
    $('#navigation-tree').on('select_node.jstree', function (e, data) {
        var idNavigationNode = data.node.data.idNavigationNode;

        loadForm(idNavigation, idNavigationNode);
    });
}

/**
 * @param {int} idNavigation
 * @param {int} idNavigationNode
 *
 * @return {void}
 */
function loadForm(idNavigation, idNavigationNode) {
    var data = {
        'id-navigation': idNavigation,
        'id-navigation-node': idNavigationNode,
    };
    var uri = config.navigationNodeFormUrlPrefix;
    if (idNavigationNode) {
        uri += 'update';
    } else {
        uri += 'create';
    }
    var url = uri + '?' + $.param(data);

    $iframe.addClass('hidden');
    $formProgressBar.removeClass('hidden');

    $iframe.off('load').on('load', onIframeLoad);
    $iframe[0].contentWindow.location.replace(url);
}

/**
 * @return {void}
 */
function resetForm() {
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
    var treeReloader = $iframe.contents().find('#navigation-tree-reloader');
    if (treeReloader.length) {
        loadTree($(treeReloader[0]).data('idNavigation'), $(treeReloader[0]).data('idSelectedTreeNode'), true);
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
    if (treeSearchTimeout) {
        clearTimeout(treeSearchTimeout);
    }
    treeSearchTimeout = setTimeout(function () {
        $('#navigation-tree').jstree(true).search($treeSearchField.val());
    }, 250);
}

/**
 * @return {void}
 */
function onTreeSaveOrderClick() {
    $treeUpdateProgressBar.removeClass('hidden');

    var jstreeData = $('#navigation-tree').jstree(true).get_json();
    var params = {
        'navigation-tree': {
            navigation: {
                id_navigation: jstreeData[0].data.idNavigation,
            },
            nodes: getNavigationNodesRecursively(jstreeData[0]),
        },
    };

    $.post(config.navigationTreeHierarchyUpdateUrl, params, function (response) {
        window.sweetAlert({
            title: response.success ? 'Success' : 'Error',
            text: response.message,
            type: response.success ? 'success' : 'error',
        });

        $treeOrderSaveBtn.attr('disabled', 'disabled');
    }).always(function () {
        $treeUpdateProgressBar.addClass('hidden');
    });
}

/**
 * @param {Object} jstreeNode
 *
 * @returns {Array}
 */
function getNavigationNodesRecursively(jstreeNode) {
    var nodes = [];

    $.each(jstreeNode.children, function (i, childNode) {
        var navigationNode = {
            navigation_node: {
                id_navigation_node: childNode.data.idNavigationNode,
                position: i + 1,
            },
            children: getNavigationNodesRecursively(childNode),
        };

        nodes.push(navigationNode);
    });

    return nodes;
}

/**
 * Open public methods
 */
module.exports = {
    initialize: initialize,
    load: loadTree,
    reset: resetTree,
};
