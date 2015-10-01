'use strict';

function showLoaderBar(){
    $('#category-loader').removeClass('hidden');
}

function closeLoaderBar(){
    $('#category-loader').addClass('hidden');
}

SprykerAjax.prototype.getCategoryTreeByIdCategoryNode = function(idCategoryNode) {
    var options = {
        'id-category-node': idCategoryNode
    };
    this
        .setUrl('/category/index/node')
        .setDataType('html')
        .ajaxSubmit(options, 'displayCategoryNodesTree');
};

SprykerAjax.prototype.updateCategoryNodesOrder = function(serializedCategoryNodes){
    showLoaderBar();
    this.setUrl('/category/node/reorder').ajaxSubmit({
        'nodes': serializedCategoryNodes
    }, 'updateCategoryNodesOrder');
};

/*
 * @param ajaxResponse
 * @returns string
 */
SprykerAjaxCallbacks.prototype.displayCategoryNodesTree = function(ajaxResponse){
    $('#category-node-tree').removeClass('hidden');
    $('#categories-list').html(ajaxResponse);
    closeLoaderBar();
    $('[data-toggle="tooltip"]').tooltip();
};

SprykerAjaxCallbacks.prototype.updateCategoryNodesOrder = function(ajaxResponse){
    closeLoaderBar();
    if (ajaxResponse.code === this.codeSuccess) {
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
