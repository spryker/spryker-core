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
            defaultConfig.toolbar[existingOptionIndex][1].push(newToolbarOption[1].slice(0));
            return;
        }

        defaultConfig.toolbar.push(newToolbarOption);
    });
};


module.exports = getEditorConfig;
