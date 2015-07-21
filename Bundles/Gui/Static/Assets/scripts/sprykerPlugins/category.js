
/**
 * Category Bundle testing
 */
$(document).ready(function () {
    $(function() {
        $('#jstree_category').jstree({
            'plugins': ['themes', 'ui', 'crrm', 'wholerow'],
            'checkbox' : {
                "keep_selected_style" : false
            },
            'core': {
                'data' : {
                    'url' : function (node) {
                        return '/category/index/getTreeNodes';
                    },
                    'data' : function (node) {
                        return { 'id' : node.id };
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
        });
    });
    $('#jstree_category').on('changed.jstree', function (e, data) {
        console.log(data.selected);
    });
    $(function () {
        $("#createNodeBtn").click(function () {
            $("#demo1").jstree("create");
        });
        $("#create_2").click(function () {
            $("#demo1").jstree("create","#phtml_1","first","Enter a new name");
        });
        $("#demo1").jstree({
            "ui" : {
                "initially_select" : [ "phtml_2" ]
            },
            "core" : { "initially_open" : [ "phtml_1" ] },
            "plugins" : [ "themes", "html_data", "ui", "crrm" ]
        });
    });

});
