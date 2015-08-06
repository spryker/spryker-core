'use strict';

$(document).ready(function() {
    var spyAj = new SprykerAjax();

    $('#root_node_table').on('click', 'tr', function(){
        var idCategory = $(this).children('td:first').text();
        spyAj.getCategoryTreeByCategoryId(idCategory);
    });

    $('#jstree-container').on('click', '.jstree-anchor', function(){
        var categoryName = $(this).contents()[1].data;
        spyAj.getCategoryTreeByCategoryName(categoryName);
    });
});
