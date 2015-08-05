'use strict';

function SprykerAjax() {
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
    }

    /**
     * @param newDataType
     * @returns {SprykerAjax}
     */
    self.setDataType = function(newDataType){
        self.dataType = newDataType;
        return self;
    }

    /**
     * makes Ajax call and then call a callback function with the response as parameter
     *
     * @param json object options
     * @param callbackFunction
     */
    self.ajaxSubmit = function(options, callbackFunction) {
        $.ajax({
            url: this.url,
            type: 'post',
            dataType: this.dataType,
            data: options
        })
        .done(function(response){
            var call = new SprykerAjaxCallbacks();
            return call[callbackFunction](response);
        });
    }

    /* change active  */
    self.changeActiveStatus = function(elementId) {
        var options = {
            id: elementId
        };
        self.ajaxSubmit(options, 'changeStatusMarkInGrid');
    }

    self.getCategoryTreeByCategoryId = function(idCategory) {
        var options = {
            id: idCategory
        };
        self.setUrl('/category/list/node').ajaxSubmit(options, 'categoryDisplayNodeTree');
    }

    self.getCategoryAttributes = function(idCategory) {
        var options = {
            id: idCategory
        }
        self
            .setUrl('/category/list/attributes')
            .setDataType('html')
            .ajaxSubmit(options, 'categoryDisplayAttributes')
        ;
    }

    self.getCategoryUrls = function(idCategory) {
        var options = {
            id: idCategory
        }
        self
            .setUrl('/category/list/urls')
            .setDataType('html')
            .ajaxSubmit(options, 'categoryDisplayUrls')
        ;
    }
}
