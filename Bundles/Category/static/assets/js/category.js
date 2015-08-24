'use strict';

$(document).ready(function() {
    var spyAj = new SprykerAjax();

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
});
