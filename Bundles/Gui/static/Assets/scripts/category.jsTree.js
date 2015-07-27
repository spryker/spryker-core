
/**
 * Category Bundle
 */
$(document).ready(function () {
    $('#jstree_category').jstree({
        'plugins': ['themes', 'ui', 'crrm', 'wholerow'],
        'checkbox' : {
            'keep_selected_style' : false
        },
        'core': {
            'data' : {
                'url' : function (node) {
                    return '/category/index/getTreeNodes?id_category=' + node.id;
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
    }).bind('select_node.jstree', function (e, data) {
        var selectedCategory = data.node.id,
            categoryAttributeTable = sprykerDataTables.table('#category_attribute_table'),
            urlTable = sprykerDataTables.table('#url_table');
        categoryAttributeTable.ajax.url('/category/index/categoryAttributeTable?id=' + selectedCategory).load();
        urlTable.ajax.url('/category/index/urlTable?id=' + selectedCategory).load();
    });

    $('#root_node_table').find('tbody').on('click', function(event) {
        window.alert('woohoo');
        $(oTable.fnSettings().aoData).each(function() {
            $(this.nTr).removeClass('row_selected');
        });
        $(event.target.parentNode).addClass('row_selected');
    });

    /* Init the table */
    oTable = $('#resultTable').dataTable();
});
