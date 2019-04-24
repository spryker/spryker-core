const getEditorConfig = function (defaultConfig, newConfig) {
    for (const optionItem in newConfig) {
        switch (optionItem) {
            case 'toolbar':
                updateToolbarOptions(defaultConfig, newConfig);
                break;
            default:
                defaultConfig[optionItem] = newConfig[optionItem];
        }
    }

    return defaultConfig;
};

const updateToolbarOptions = function (defaultConfig, newConfig) {
    newConfig.toolbar.forEach(function (newToolbarOption) {
        const existingOptionIndex = defaultConfig.toolbar.findIndex(function(defaultToolbarOption, index) {
            return newToolbarOption[0] === defaultToolbarOption[0];
        });

        if (existingOptionIndex) {
            const newToolbarOptionsArray = newToolbarOption[1].slice(0);
            const toolbarOptionGroup = defaultConfig.toolbar[existingOptionIndex];
            const toolbarOptionsArray = toolbarOptionGroup[1];

            toolbarOptionsArray.push(newToolbarOptionsArray);
            return;
        }

        defaultConfig.toolbar.push(newToolbarOption);
    });
};


module.exports = getEditorConfig;
