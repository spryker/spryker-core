'use strict';

function SprykerAjaxCallbacks() {
    /* HTML success code */
    var codeSuccess = 200;

    /* Alerted object, display alerts with model from bootstrap */
    this.alerter = new SprykerAlert();

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
    this.changeStatusMarkInGrid = function(ajaxResponse){
        if (ajaxResponse.code == codeSuccess) {
            $('#active-' + ajaxResponse.id).prop('checked', ajaxResponse.newStatus);
        } else {
            this.alerter.error(ajaxResponse.message);
        }
    }

    this.categoryDisplayNodeTree = function(ajaxResponse){
        $('#category-node-tree').removeClass('hidden');
        $('#jstree-container').html('<div id="jstree-category"></div>');
        $('#jstree-category').jstree({ 'core' : {
                'data' : ajaxResponse.data
            }
        });
    }
}
