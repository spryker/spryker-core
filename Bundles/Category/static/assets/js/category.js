'use strict';

$(document).ready(function() {
    var sprykerAjax = new SprykerAjax();
    var triggeredFirstEvent = false;

    $('#root-node-table').on('click', 'tbody.tr', function(){
        showLoaderBar();
        var idCategoryNode = $(this).children('td:first').text();
        sprykerAjax.getCategoryTreeByIdCategoryNode(idCategoryNode);
    });

    $('#category-node-tree').on('click', '.category-tree', function(event){
        event.preventDefault();
        showLoaderBar();
        var idCategoryNode = $(this).attr('id').replace('node-', '');
        sprykerAjax.getCategoryTreeByIdCategoryNode(idCategoryNode);
    });

    $('.gui-table-data-category').dataTable({
        "createdRow": function(row, data, index){
            if (triggeredFirstEvent === false) {
                showLoaderBar();
                var idCategoryNode = data[0];
                sprykerAjax.getCategoryTreeByIdCategoryNode(idCategoryNode);
                triggeredFirstEvent = true;
            }
        }
    });

    serializedList = {}; //has to be global
    var updateOutput = function(e) {
        var list = e.length ? e : $(e.target);
        serializedList = window.JSON.stringify(list.nestable('serialize'));
    };

    $('#nestable').nestable({
        group: 1,
        maxDepth: 1
    }).on('change', updateOutput);

    $('.save-categories-order').click(function(){
        sprykerAjax.updateCategoryNodesOrder(serializedList);
    });

});
