'use strict';

$(document).ready(function() {
    var spyAj = new SprykerAjax();
    var triggeredFirstEvent = false;

    $('#root-node-table').on('click', 'tr', function(){
        showLoaderBar();
        var idNode = $(this).children('td:first').text();
        spyAj.getCategoryTreeByIdNode(idNode);
    });

    $('#category-node-tree').on('click', '.category-tree', function(event){
        event.preventDefault();
        showLoaderBar();
        var idNode = $(this).attr('id').replace('node-', '');
        spyAj.getCategoryTreeByIdNode(idNode);
    });

    $('.gui-table-data-category').dataTable({
        "createdRow": function(row, data, index){
            if (triggeredFirstEvent === false) {
                showLoaderBar();
                var idCategory = data[0];
                spyAj.getCategoryTreeByIdNode(idCategory);
                triggeredFirstEvent = true;
            }
        }
    });

    var serializedList = {};
    var updateOutput = function(e) {
        var list = e.length ? e : $(e.target);
        serializedList = window.JSON.stringify(list.nestable('serialize'));
    };

    $('#nestable').nestable({
        group: 1,
        maxDepth: 1
    }).on('change', updateOutput);

    $('.save-categories-order').click(function(){
        spyAj.updateCategoryNodesOrder(serializedList);
    });

});
