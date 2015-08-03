'use strict';

function SprykerAjax() {
    /** if ajax url is null, the action will be in the same page */
    this.url = null;

    this.setUrl = function(newUrl){
        this.url = newUrl;
        return this;
    }

    /**
     * makes Ajax call and then call a callback function with the response as parameter
     *
     * @param json object options
     * @param callbackFunction
     */
    this.ajaxSubmit = function(options, callbackFunction) {
        $.ajax({
            url: this.url,
            type: 'post',
            dataType: 'json',
            data: options
        })
        .done(function(response){
            var call = new SprykerAjaxCallbacks();
            return call[callbackFunction](response);
        });
    }

    /* change active  */
    this.changeActiveStatus = function(elementId) {
        var options = {
            id: elementId
        };
        this.ajaxSubmit(options, 'changeStatusMarkInGrid');
    }
}
