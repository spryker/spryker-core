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
        for (const property in newConfig) {
            switch (property) {
                case 'toolbar':
                    updateToolbarOptions(baseConfig, newConfig);
                    break;
                case 'buttons':
                    if (baseConfig.hasOwnProperty('buttons') && newConfig.hasOwnProperty('buttons')) {
                        Object.assign(baseConfig.buttons, newConfig.buttons);
                    }
                    if (!baseConfig.hasOwnProperty('buttons')) {
                        baseConfig.buttons = newConfig.buttons;
                    }
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


const updateToolbarOptions = function (baseConfig, newConfig) {
    newConfig.toolbar.forEach(function (newToolbarOption) {
        const existingOptionIndex = baseConfig.toolbar.findIndex(function(defaultToolbarOption) {
            return newToolbarOption[0] === defaultToolbarOption[0];
        });

        if (existingOptionIndex) {
            const newToolbarOptionsArray = newToolbarOption[1].slice(0);
            const toolbarOptionGroup = baseConfig.toolbar[existingOptionIndex];
            const toolbarOptionsArray = toolbarOptionGroup[1];

            toolbarOptionsArray.push(newToolbarOptionsArray);
            return;
        }

        baseConfig.toolbar.push(newToolbarOption);
    });
};
