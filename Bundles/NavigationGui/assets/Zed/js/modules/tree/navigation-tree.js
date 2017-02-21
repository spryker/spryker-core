/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

// var navigationNodeForm = require('./navigation-node-form');
require('jstree');

var treeProgressBar = $('#navigation-tree-loader');
var targetElement = $('#navigation-tree-container');

/**
 * @param {int} idNavigation
 * @param {int|null} selected
 * @param {bool} skipFormLoad
 *
 * @return {void}
 */
function loadTree(idNavigation, selected, skipFormLoad)
{
    treeProgressBar.removeClass('hidden');

    var url = '/navigation-gui/tree/?id-navigation=' + idNavigation;

    $.get(url, $.proxy(function(targetElement, response) {
        targetElement.html(response);

        // tree init
        var tree = $('#jstree1').jstree({
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
            'types': {
                'default': {
                    'icon': 'fa fa-folder'
                },
                'navigation': {
                    'icon': 'fa fa-list'
                },
                'cms': {
                    'icon': 'fa fa-file-o'
                },
                'category': {
                    'icon': 'fa fa-sitemap'
                },
                'external_url': {
                    'icon': 'fa fa-external-link'
                }
            },
            'dnd': {
                'is_draggable': function(items) {
                    var idNavigationNode = items[0].data.idNavigationNode;
                    return !!idNavigationNode;
                }
            }
        });

        if (skipFormLoad) {
            selectNode(selected);
            setNodeSelectListener(idNavigation);
        } else {
            setNodeSelectListener(idNavigation);
            selectNode(selected);
        }

    }, null, targetElement))
    .always(function() {
        treeProgressBar.addClass('hidden');
    });
}

/**
 * @return {void}
 */
function resetTree()
{
    targetElement.html('');
    resetForm();
}

function selectNode(idNavigationNode) {
    var nodeToSelect = 'navigation-node-' + (idNavigationNode ? idNavigationNode : 0);
    $('#jstree1').jstree(true).select_node(nodeToSelect);
}

function setNodeSelectListener(idNavigation) {
    $('#jstree1').on('select_node.jstree', function(e, data){
        var idNavigationNode = data.node.data.idNavigationNode;

        loadForm(idNavigation, idNavigationNode);
    });
}




var formProgressBar = $('#navigation-node-form-loader');
var iframe = $('#navigation-node-form-iframe');

/**
 * @param {int} idNavigation
 * @param {int} idNavigationNode
 *
 * @return {void}
 */
function loadForm(idNavigation, idNavigationNode)
{
    iframe.hide();
    formProgressBar.removeClass('hidden');

    var baseUri = '/navigation-gui/node/';
    if (idNavigationNode) {
        baseUri += 'update';
    } else {
        baseUri += 'create';
    }

    var data = {
        'id-navigation': idNavigation,
        'id-navigation-node': idNavigationNode
    };
    var url = baseUri + '?' + $.param(data);

    iframe.attr('src', url);
}

/**
 * @return {void}
 */
function resetForm()
{
    iframe.hide();
}

iframe.on('load', function(){
    formProgressBar.addClass('hidden');
    iframe.show();

    // set iframe height on load
    var iframeContentHeight = iframe[0].contentWindow.document.body.scrollHeight;
    iframe.height(iframeContentHeight);

    // tree reloading
    var treeReloader = iframe.contents().find('#navigation-tree-reloader');
    if (treeReloader.length) {
        // console.log($(treeReloader[0]).data('idNavigation'), $(treeReloader[0]).data('selectedTreeNode'));
        loadTree($(treeReloader[0]).data('idNavigation'), $(treeReloader[0]).data('selectedTreeNode'), true);
    }
});







// search input
var timeout = false;
$('#navigation-tree-search-field').keyup(function () {
    if(timeout) {
        clearTimeout(timeout);
    }
    timeout = setTimeout(function () {
        var term = $('#navigation-tree-search-field').val();
        $('#jstree1').jstree(true).search(term);
    }, 250);
});

// click save
$('#navigation-tree-save-btn').on('click', function(){
    var json = $('#jstree1').jstree(true).get_json();
    console.log(json);
    // TODO: save tree order
});

module.exports = {
    load: loadTree,
    reset: resetTree
};
