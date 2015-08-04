'use strict';


$('#root_node_table').on('click', 'tr', function(){

    var idCategory = $(this).children('td:first').text();
    console.log(idCategory);

    var spyAj = new SprykerAjax();
    spyAj.getCategoryTreeByCategoryId(idCategory);

    //$('#jstree_category').jstree({ 'core' : {
    //    'data' : [
    //        {
    //            'text' : 'Main category',
    //            'state' : {
    //                'opened' : true,
    //                'selected' : true
    //            },
    //            'children' : [
    //                'Child 1',
    //                'Child 2',
    //                'Child 3',
    //                'Child 4',
    //                'Child 5',
    //                'Child 6'
    //            ]
    //        }
    //    ]
    //} });
});
