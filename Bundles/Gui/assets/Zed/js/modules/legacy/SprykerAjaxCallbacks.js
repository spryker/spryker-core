/**
 * 
 * Spryker ajax callbacks manager
 * @copyright: Spryker Systems GmbH
 *
 */

'use strict';

module.exports = new function() {
    var self = this;

    /* HTML success code */
    self.codeSuccess = 200;

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
};
