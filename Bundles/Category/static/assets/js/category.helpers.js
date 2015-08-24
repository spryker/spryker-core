'use strict';

function showLoaderBar(){
    $('#category-loader').removeClass('hidden');
}

function closeLoaderBar(){
    $('#category-loader').addClass('hidden');
}

SprykerAjax.prototype.getCategoryTreeByCategoryId = function(idCategory) {
    var options = {
        'id-category': idCategory
    };
    this
        .setUrl('/category/index/node')
        .setDataType('html')
        .ajaxSubmit(options, 'categoryDisplayNodeTree');
};

/*
 * @param ajaxResponse
 * @returns string
 */
SprykerAjaxCallbacks.prototype.categoryDisplayNodeTree = function(ajaxResponse){
    $('#category-node-tree').removeClass('hidden');
    $('#categories-list').html(ajaxResponse);
    closeLoaderBar();
};
