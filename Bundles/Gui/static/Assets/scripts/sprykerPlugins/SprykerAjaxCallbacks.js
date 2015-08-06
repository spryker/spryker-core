'use strict';

function SprykerAjaxCallbacks() {
    var self = this;

    /* HTML success code */
    self.codeSuccess = 200;

    /* Alerted object, display alerts with model from bootstrap */
    self.alerter = new SprykerAlert();

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
     */
    self.categoryDisplayNodeTree = function(ajaxResponse){
        $('#category-node-tree').removeClass('hidden');
        $('#jstree-container').html('<div id="jstree-category"></div>');
        $('#jstree-category').jstree({
            'core' : {
                'data' : ajaxResponse.data
            }
        });
        self.spyAj.getCategoryAttributes(ajaxResponse.idCategory);
        self.spyAj.getCategoryUrls(ajaxResponse.idCategory);
    }

    /**
     *
     * @param ajaxResponse
     */
    self.categoryDisplayAttributes = function(ajaxResponse){
        $('#attributes-table').removeClass('hidden');
        $('#category-attributes').html(ajaxResponse);
    }

    /**
     * @param ajaxResponse
     */
    self.categoryDisplayUrls = function(ajaxResponse){
        $('#url-table').removeClass('hidden');
        $('#category-urls').html(ajaxResponse);
        closeLoaderBar();
    }
}
