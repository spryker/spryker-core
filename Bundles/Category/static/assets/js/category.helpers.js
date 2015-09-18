'use strict';

function showLoaderBar(){
    $('#category-loader').removeClass('hidden');
}

function closeLoaderBar(){
    $('#category-loader').addClass('hidden');
}

SprykerAjax.prototype.getCategoryTreeByIdNode = function(idNode) {
    var options = {
        'id-node': idNode
    };
    this
        .setUrl('/category/index/node')
        .setDataType('html')
        .ajaxSubmit(options, 'categoryDisplayNodeTree');
};

SprykerAjax.prototype.updateCategoryNodesOrder = function(serializedCategoryNodeItems){
    showLoaderBar();
    this.setUrl('/category/node/reorder').ajaxSubmit({
        'nodes': serializedCategoryNodeItems
    }, 'updateCategoryNodesOrder');
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

SprykerAjaxCallbacks.prototype.updateCategoryNodesOrder = function(ajaxResponse){
    closeLoaderBar();
    if (ajaxResponse.code == this.codeSuccess) {
        swal({
            title: "Success",
            text: ajaxResponse.message,
            type: "success"
        });
        return true;
    }

    swal({
        title: "Error",
        text: ajaxResponse.message,
        type: "error"
    });
};
