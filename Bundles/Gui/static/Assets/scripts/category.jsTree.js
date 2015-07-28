/**
 * Category Bundle
 */
var rootTable,
    categoryDataTables = [],
    categoryTreeInitialised = false,

    rootTableSettings = {
        bFilter: false,
        'initComplete': function(settings, json) {
            //console.log(settings);
            //console.log(json);

            $('#root_node_table tbody').on('click', 'tr', function (e) {
                e.preventDefault();
                var table = rootTable.table('#root_node_table'),
                    idRootCategory = $(this).children('td:first').text();

                if (categoryTreeInitialised) {
                    $('#jstree_category').jstree(true).settings.core.data.url = '/category/index/getTreeNodes?id_category=' + idRootCategory;
                    $('#jstree_category').jstree(true).refresh();
                } else {
                    categoryTreeInitialised = true;
                    $('#jstree_category').jstree({
                        'plugins': ['themes', 'ui', 'crrm', 'wholerow'],
                        'checkbox' : {
                            'keep_selected_style' : false
                        },
                        'core': {
                            'data' : {
                                'url' : function (node) {
                                    return '/category/index/getTreeNodes?id_category=' + idRootCategory;
                                },
                                'data' : function (node) {
                                    return { 'id' : node.id };
                                },
                                success: function (data) {
                                    "use strict";
                                    console.log(data);
                                    return data;
                                }
                            },
                            'themes': {
                                'name': 'spryker'
                            },
                            'check_callback' : function (operation, node, node_parent, node_position, more) {
                                // operation can be 'create_node', 'rename_node', 'create_node', 'move_node' or 'copy_node'
                                // in case of 'rename_node' node_position is filled with the new node name
                                return $.inArray(
                                    operation,
                                    ['create_node', 'rename_node', 'move_node', 'copy_node', 'delete_node']
                                );
                            }
                        }
                    }).bind('loaded.jstree', function (e, data) {
                        data.instance.select_node('ul > li:first');
                        $('#category-node-tree').removeClass('hidden');
                        $('#attributes-table').removeClass('hidden');
                        $('#url-table').removeClass('hidden');
                    }).bind('select_node.jstree', function (e, data) {
                        var selectedCategoryNode = data.node.id,
                            categoryAttributeTable,
                            urlTable,
                            categoryDataTables;
                        console.log('selected');
                        categoryDataTables = $('.gui-table-data-category-lazy').DataTable();
                        categoryAttributeTable = categoryDataTables.table('#category_attribute_table');
                        urlTable = categoryDataTables.table('#url_table');
                        $('#root_node_table').on( 'draw.dt', function () {
                            $('#root_node_table').find('tbody').on('click', function(event) {
                                $(oTable.fnSettings().aoData).each(function() {
                                    $(this.nTr).removeClass('row_selected');
                                });
                                $(event.target.parentNode).addClass('row_selected');
                            });
                        } );
                        categoryAttributeTable.ajax.url('/category/index/categoryAttributeTable?id_node=' + selectedCategoryNode).load();
                        urlTable.ajax.url('/category/index/urlTable?id_node=' + selectedCategoryNode).load();
                    });
                }
            } );

        }
    };

$(document).ready(function () {
    rootTable = $('.gui-table-data-category').DataTable(rootTableSettings);
});
