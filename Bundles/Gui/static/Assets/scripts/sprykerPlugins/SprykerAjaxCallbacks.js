'use strict';

function SprykerAjaxCallbacks() {
    var self = this;

    /* HTML success code */
    self.codeSuccess = 200;

    /**
     * @type {SprykerAjax}
     */
    self.spyAj = new SprykerAjax();

    /**
     * Response:
     * <code>
     *  {
     *      "code": 200,
     *      "newStatus": true|false,
     *      "id": 1,
     *      "message": "message if something went wrong"
     *  }
     * </code>
     * @param ajaxResponse
     */
    self.changeStatusMarkInGrid = function(ajaxResponse){
        if (ajaxResponse.code == self.codeSuccess) {
            $('#active-' + ajaxResponse.id).prop('checked', ajaxResponse.newStatus);
        } else {
            self.alerter.error(ajaxResponse.message);
        }
    }

    /**
     * @param ajaxResponse
     * @returns {ajaxReponse}
     */
    self.categoryDisplayNodeTree = function(ajaxResponse){
        $('#category-node-tree').removeClass('hidden');
        $('#jstree-container').html('<div id="jstree-category"></div>');
        $('#jstree-category').jstree({
            'core' : {
                'data' : ajaxResponse.data
            }
        });
        return ajaxResponse;
    }

    /**
     * @param ajaxResponse
     */
    self.categoryDisplayAttributes = function(ajaxResponse){
        $('#category-tabs').removeClass('hidden');
        $('#category-attributes').html(ajaxResponse);
    }

    /**
     * @param ajaxResponse
     */
    self.categoryDisplayUrls = function(ajaxResponse){
        $('#category-tabs').removeClass('hidden');
        $('#category-urls').html(ajaxResponse);
        closeLoaderBar();
    }

    /**
     * @param ajaxResponse
     */
    self.categoryDisplayProducts = function(ajaxResponse){
        $('#category-tabs').removeClass('hidden');
        $('#category-products').html(ajaxResponse);
        closeLoaderBar();
    }
}
