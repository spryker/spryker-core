/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

var SprykerAjaxCallbacks = require('./SprykerAjaxCallbacks');

module.exports = new function() {
    var self = this;

    /** if ajax url is null, the action will be in the same page */
    self.url = null;

    self.dataType = 'json';

    /**
     * @param newUrl
     * @returns {SprykerAjax}
     */
    self.setUrl = function(newUrl){
        self.url = newUrl;
        return self;
    };

    /**
     * @param newDataType
     * @returns {SprykerAjax}
     */
    self.setDataType = function(newDataType){
        self.dataType = newDataType;
        return self;
    };

    self.ajaxSubmit = function(options, callbackFunction, parameters, isGet) {
        var callType = (!!isGet) ? 'get' : 'post';
        return $.ajax({
            url: this.url,
            type: callType,
            dataType: this.dataType,
            data: options
        })
        .done(function(response){
            if (typeof callbackFunction === 'function') {
                return callbackFunction(response, parameters);
            } else if (typeof callbackFunction === 'string') {
                return SprykerAjaxCallbacks[callbackFunction](response, parameters);
            } else {
                return response;
            }
        });
    };

    /* change active  */
    self.changeActiveStatus = function(elementId) {
        var options = {
            id: elementId
        };
        self.ajaxSubmit(options, 'changeStatusMarkInGrid');
    };
};
