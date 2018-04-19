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

var ajaxRequest;
var treeSearchTimeout = false;

var config = {
    fileDirectoryTreeUrl: '/file-manager-gui/directories-tree/load',
    fileDirectoryNodeFormUrlPrefix: '/file-manager-gui/node/',
    fileDirectoryTreeHierarchyUpdateUrl: '/file-manager-gui/directories-tree/update-hierarchy',
    fileDirectoryTreeNodeTypes: {
        'default': {
            'icon': 'fa fa-folder'
        }
    }
};

/**
 * @return {void}
 */
function initialize() {
    $treeOrderSaveBtn.on('click', onTreeSaveOrderClick);
    $treeSearchField.keyup(onTreeSearchKeyup);

    initJsTree();

    // Enable save order button on tree change
    $(document).bind('dnd_stop.vakata', function() {
        $treeOrderSaveBtn.removeAttr('disabled');
    });
}

/**
 * @returns {Function}
 */
function createTreeLoadHandler() {
    return function(response) {
        $treeContent.html(response);

        initJsTree();
        $treeContainer.removeClass('hidden');
    }
}

/**
 * @return {void}
 */
function initJsTree() {
    $('#file-directory-files-list').load('/file-manager-gui/files');

    $('#file-directory-tree').jstree({
        'core': {
            'check_callback': function (op, node, par, pos, more) {
                // disable drop on root level
                if (more && more.dnd && (op === 'move_node' || op === 'copy_node')) {
                    return !!more.ref.data.idFileDirectoryNode;
                }

                return true;
            }
        },
        'plugins': ['wholerow', 'dnd', 'search'],
        'types': config.fileDirectoryTreeNodeTypes,
        'dnd': {
            'is_draggable': function(items) {
                var idFileDirectoryNode = items[0].data.idFileDirectoryNode;
                return !!idFileDirectoryNode;
            }
        }
    }).on("changed.jstree", function (e, data) {
        var filesTable = $('#file-directory-files-list').find('table').first();
        filesTable.DataTable().ajax.url( '/file-manager-gui/files/table?file-directory-id=' + data.node.data.idFileDirectoryNode ).load();
        $('#add-file-link').attr('href', '/file-manager-gui/add-file?file-directory-id=' + data.node.data.idFileDirectoryNode);
    });

    $treeProgressBar.removeClass('hidden');
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

    $.post(config.fileDirectoryTreeHierarchyUpdateUrl, params, function(response) {
        window.sweetAlert({
            title: response.success ? "Success" : "Error",
            text: response.message,
            type: response.success ? "success" : "error"
        });

        $treeOrderSaveBtn.attr('disabled', 'disabled');
    }).always(function() {
        $treeUpdateProgressBar.addClass('hidden');
    });
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
            'file_directory': {
                'id_file_directory': childNode.data.idFileDirectoryNode,
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
    initialize: initialize
};
