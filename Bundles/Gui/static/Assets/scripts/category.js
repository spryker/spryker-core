'use strict';

$(document).ready(function() {
    var spyAj = new SprykerAjax();

    $('#root_node_table').on('click', 'tr', function(){
        showLoaderBar();
        var idCategory = $(this).children('td:first').text();
        spyAj.getCategoryTreeByCategoryId(idCategory);
    });
});
