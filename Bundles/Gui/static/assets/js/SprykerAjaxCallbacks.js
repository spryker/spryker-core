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
    };

    /**
     *
     * @param ajaxResponse
     * @returns string
     */
    self.categoryDisplayNodeTree = function(ajaxResponse){
        $('#category-node-tree').removeClass('hidden');
        $('#categories-list').html(ajaxResponse);
        closeLoaderBar();
    };
}
