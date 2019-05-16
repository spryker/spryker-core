/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

module.exports = {
    getGlobalConfig: function(configName) {
        return Boolean(configName &&
            window.editorConfiguration &&
            window.editorConfiguration[configName]) ? window.editorConfiguration[configName] : null;
    },
    mergeConfigs: function (baseConfig, newConfig) {
        for (var property in newConfig) {
            switch (property) {
                case 'toolbar':
                    updateToolbarOptions(baseConfig, newConfig);
                    break;
                case 'buttons':
                    if (baseConfig.hasOwnProperty('buttons') && newConfig.hasOwnProperty('buttons')) {
                        $.extend(baseConfig.buttons, newConfig.buttons);
                    }
                    if (!baseConfig.hasOwnProperty('buttons')) {
                        baseConfig.buttons = newConfig.buttons;
                    }
                    break;
                case 'popover':
                    var defaultPopoverOptions = $.summernote.options.popover;
                    var extendedOptions = $.extend(defaultPopoverOptions, newConfig.popover);

                    baseConfig.popover = extendedOptions;
                    break;
                default:
                    baseConfig[property] = newConfig[property];
            }
        }

        return baseConfig;
    },
    getConfig: function(content) {
    	content = content || '';

        return {
        	height: 300,
            maxHeight: 600,
            inputText: content,
            focus: true,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['picture', 'link', 'video', 'table', 'hr']],
                ['misc', ['undo', 'redo', 'codeview']],
                ['custom', []]
            ]
        };
    }
};

if (!Array.prototype.findIndex) {
    Array.prototype.findIndex = function(predicate) {
        if (this === null) {
            throw new TypeError('Array.prototype.findIndex called on null or undefined');
        }
        if (typeof predicate !== 'function') {
            throw new TypeError('predicate must be a function');
        }
        var list = Object(this);
        var length = list.length >>> 0;
        var thisArg = arguments[1];
        var value;

        for (var i = 0; i < length; i++) {
            value = list[i];
            if (predicate.call(thisArg, value, i, list)) {
                return i;
            }
        }
        return -1;
    };
}

var updateToolbarOptions = function (baseConfig, newConfig) {
    newConfig.toolbar.forEach(function (newToolbarOption) {
        var existingOptionIndex = baseConfig.toolbar.findIndex(function(defaultToolbarOption) {
            return newToolbarOption[0] === defaultToolbarOption[0];
        });

        if (existingOptionIndex) {
            var newToolbarOptionsArray = newToolbarOption[1].slice(0);
            var toolbarOptionGroup = baseConfig.toolbar[existingOptionIndex];
            var toolbarOptionsArray = toolbarOptionGroup[1];

            toolbarOptionsArray.push(newToolbarOptionsArray);
            return;
        }

        baseConfig.toolbar.push(newToolbarOption);
    });
};
