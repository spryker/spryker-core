/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('jstree');

var $treeContainer = $('#file-directory-tree-container');
var $treeContent = $('#file-directory-tree-content');
var $treeSearchField = $('#file-directory-tree-search-field');
var $treeProgressBar = $('#file-directory-tree-loader');
var $formProgressBar = $('#file-directory-node-form-loader');
var $treeUpdateProgressBar = $('#file-directory-tree-update-loader');
var $treeOrderSaveBtn = $('#file-directory-tree-save-btn');
var $iframe = $('#file-directory-node-form-iframe');

var ajaxRequest;
var treeSearchTimeout = false;

var config = {
    fileDirectoryTreeUrl: '/file-directory-gui/tree',
    fileDirectoryNodeFormUrlPrefix: '/file-directory-gui/node/',
    fileDirectoryTreeHierarchyUpdateUrl: '/navigation-gui/tree/update-hierarchy',
    fileDirectoryTreeNodeTypes: {
        'default': {
            'icon': 'fa fa-folder'
        },
        'navigation': {
            'icon': 'fa fa-list'
        },
        'cms_page': {
            'icon': 'fa fa-file-o'
        },
        'category': {
            'icon': 'fa fa-sitemap'
        },
        'link': {
            'icon': 'fa fa-link'
        },
        'external_url': {
            'icon': 'fa fa-external-link'
        }
    }
};

/**
 * @return {void}
 */
function initialize() {
    $treeOrderSaveBtn.on('click', onTreeSaveOrderClick);
    $treeSearchField.keyup(onTreeSearchKeyup);

    // Enable save order button on tree change
    $(document).bind('dnd_stop.vakata', function() {
        $treeOrderSaveBtn.removeAttr('disabled');
    });
}

/**
 * @param {int} idFileDirectory
 * @param {int|null} selected
 * @param {boolean} skipFormLoad
 *
 * @return {void}
 */
function loadTree(idFileDirectory, selected, skipFormLoad)  {
    $treeProgressBar.removeClass('hidden');
    $treeContainer.addClass('hidden');

    if (ajaxRequest) {
        ajaxRequest.abort();
    }

    ajaxRequest = $.get(config.fileDirectoryTreeUrl, {'id-file-directory': idFileDirectory}, createTreeLoadHandler(idFileDirectory, selected, skipFormLoad))
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
 * @param {int} idFileDirectory
 * @param {int|null} selected
 * @param {boolean} skipFormLoad
 *
 * @returns {Function}
 */
function createTreeLoadHandler(idFileDirectory, selected, skipFormLoad) {
    return function(response) {
        $treeContent.html(response);

        initJsTree();

        $treeContainer.removeClass('hidden');

        if (skipFormLoad) {
            selectNode(selected);
            setNodeSelectListener(idFileDirectory);
        } else {
            setNodeSelectListener(idFileDirectory);
            selectNode(selected);
        }
    }
}

/**
 * @return {void}
 */
function initJsTree() {
    $('#file-directory-tree').jstree({
        'core': {
            'check_callback': function (op, node, par, pos, more) {
                // disable drop on root level
                if (more && more.dnd && (op === 'move_node' || op === 'copy_node')) {
                    return !!more.ref.data.idNavigationNode;
                }

                return true;
            }
        },
        'plugins': ['types', 'wholerow', 'dnd', 'search'],
        'types': config.fileDirectoryTreeNodeTypes,
        'dnd': {
            'is_draggable': function(items) {
                var idFileDirectoryNode = items[0].data.idFileDirectoryNode;
                return !!idFileDirectoryNode;
            }
        }
    });
}

/**
 * @param {int} idFileDirectoryNode
 *
 * @return {void}
 */
function selectNode(idFileDirectoryNode) {
    var nodeToSelect = 'file-directory-node-' + (idFileDirectoryNode ? idFileDirectoryNode : 0);
    $('#file-directory-tree').jstree(true).select_node(nodeToSelect);
}

/**
 * @param {int} idFileDirectory
 *
 * @return {void}
 */
function setNodeSelectListener(idFileDirectory) {
    $('#file-directory-tree').on('select_node.jstree', function(e, data){
        var idFileDirectoryNode = data.node.data.idFileDirectoryNode;

        loadForm(idFileDirectory, idFileDirectoryNode);
    });
}

/**
 * @param {int} idFileDirectory
 * @param {int} idFileDirectoryNode
 *
 * @return {void}
 */
function loadForm(idFileDirectory, idFileDirectoryNode)  {
    var data = {
        'id-file-directory': idFileDirectory,
        'id-file-directory-node': idFileDirectoryNode
    };
    var uri = config.fileDirectoryNodeFormUrlPrefix;
    if (idFileDirectory) {
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
    var treeReloader = $iframe.contents().find('#file-directory-tree-reloader');
    if (treeReloader.length) {
        loadTree($(treeReloader[0]).data('idFileDirectory'), $(treeReloader[0]).data('idSelectedTreeNode'), true);
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
        $('#file-directory-tree').jstree(true).search($treeSearchField.val());
    }, 250);
}

/**
 * @return {void}
 */
function onTreeSaveOrderClick(){
    $treeUpdateProgressBar.removeClass('hidden');

    var jstreeData = $('#file-directory-tree').jstree(true).get_json();
    var params = {
        'file-directory-tree': {
            'file-directory': {
                'id_file_directory': jstreeData[0].data.idFileDirectory
            },
            'nodes': getFileDirectoryNodesRecursively(jstreeData[0])
        }
    };

}

/**
 * @param {Object} jstreeNode
 *
 * @returns {Array}
 */
function getFileDirectoryNodesRecursively(jstreeNode) {
    var nodes = [];

    $.each(jstreeNode.children, function(i, childNode) {
        var fileDirectoryNode = {
            'file_directory_node': {
                'id_file_directory_node': childNode.data.idFileDirectoryNode,
                'position': (i + 1)
            },
            'children': getFileDirectoryNodesRecursively(childNode)
        };

        nodes.push(fileDirectoryNode);
    });

    return nodes;
}


/**
 * Open public methods
 */
module.exports = {
    initialize: initialize,
    load: loadTree,
    reset: resetTree
};
