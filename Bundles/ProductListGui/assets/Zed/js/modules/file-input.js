/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var inputFileRemoveFile = {
    removeBtnClasses: 'btn btn-sm btn-outline safe-submit btn-remove btn-remove-file js-remove-file',
    removeBtnInner: '<i class="fa fa-times"></i> Remove file',
    
    init: function () {
        this.$inputs = $('[type="file"]');
        this.mapEvents();
    },
    
    mapEvents: function () {
        var self = this;
        
        this.$inputs.on('change', function() {
            if(!$(this).next().length && !$(this).next().hasClass(self.removeBtnClasses)) {
                var $btn = self.createBtn();
                $(this).after($btn);
            }
        });
    },
    
    createBtn: function() {
        var $btn = $('<span>');
        
        $btn
            .addClass(this.removeBtnClasses)
            .append(this.removeBtnInner)
            .on('click', $.proxy(function(e) {
                this.removeFile($(e.currentTarget));
            }, this));
        
        return $btn;
    },
    
    removeFile: function($clickedBtn) {
        $clickedBtn.prev().val('');
        $clickedBtn.remove();
    }
};

$(document).ready(function() {
    inputFileRemoveFile.init();
});