'use strict';

$(document).ready(function() {
    var spyAj = new SprykerAjax();
    var triggeredFirstEvent = false;

    $('#root-node-table').on('click', 'tr', function(){
        showLoaderBar();
        var idCategory = $(this).children('td:first').text();
        spyAj.getCategoryTreeByCategoryId(idCategory);
    });

    $('#category-node-tree').on('click', '.category-tree', function(event){
        event.preventDefault();
        showLoaderBar();
        var idCategory = $(this).attr('id').replace('categ-', '');
        spyAj.getCategoryTreeByCategoryId(idCategory);
    });

    $('.gui-table-data-category').dataTable({
        "createdRow": function(row, data, index){
            if (triggeredFirstEvent === false) {
                showLoaderBar();
                var idCategory = data[0];
                spyAj.getCategoryTreeByCategoryId(idCategory);
                triggeredFirstEvent = true;
            }
        }
    });

});
