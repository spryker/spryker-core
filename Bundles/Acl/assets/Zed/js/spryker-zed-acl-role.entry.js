/**
 * @deprecated not used any more use `data-depends-on-field` attribute instead.
 */

$(document).ready(function () {
    if ($('.js-select-dependable[data-depends-on-field=".js-select-dependable--bundle"]')) {
        return;
    }

    initializeDropdowns();

    $('.js-select-dependable.js-select-dependable--bundle').on('change', function (event) {
        handleBundleChange(event);
    });

    $('.js-select-dependable.js-select-dependable--controller').on('change', function (event) {
        handleControllerChange(event);
    });
});

function initializeDropdowns() {
    const dropdowns = getDropdowns();
    resetDropdowns([dropdowns.controller, dropdowns.action]);
    disablePlaceholders(dropdowns.all);
}

function getDropdowns() {
    const bundle = $('.js-select-dependable.js-select-dependable--bundle');
    const controller = $('.js-select-dependable.js-select-dependable--controller');
    const action = $('.js-select-dependable.js-select-dependable--action');
    return {
        bundle,
        controller,
        action,
        all: [bundle, controller, action],
    };
}

function handleBundleChange(event) {
    const dropdowns = getDropdowns();
    handleDropdownChange(event, `/acl/rules/controller-choices?bundle=`, dropdowns.controller);
    resetDropdowns([dropdowns.action]);
}

function handleControllerChange(event) {
    const dropdowns = getDropdowns();
    const bundle = $('.js-select-dependable.js-select-dependable--bundle').val();
    handleDropdownChange(event, `/acl/rules/action-choices?bundle=${bundle}&controller=`, dropdowns.action);
}

function resetDropdowns(dropdownsArray) {
    dropdownsArray.forEach((dropdown) => {
        const emptyOption = getEmptyOption(dropdown);
        dropdown.empty().append(emptyOption);
    });
}

function disablePlaceholders(dropdownsArray) {
    dropdownsArray.forEach((dropdown) => {
        const emptyOption = getEmptyOption(dropdown);
    });
}

function getEmptyOption(dropdown) {
    const firstDropdown = dropdown[0];
    const emptyOption = firstDropdown ? firstDropdown.querySelector('option[value=""]') : null;

    if (emptyOption) {
        emptyOption.disabled = true;
    }
    const option = new Option(emptyOption ? emptyOption.text : 'Select option', '', true, true);
    option.disabled = true;
    return option;
}

function handleDropdownAvailability(isDisabled) {
    const dropdowns = getDropdowns().all;
    dropdowns.forEach((dropdown) => dropdown.prop('disabled', isDisabled));
}

function handleDropdownChange(event, queryUrl, dropdown) {
    const value = event.target.value;
    sendRequest(queryUrl + value, dropdown);
}

function sendRequest(queryUrl, dropdown) {
    handleDropdownAvailability(true);

    $.ajax({
        url: queryUrl,
    }).done(function (data) {
        updateDropdownData(dropdown, data);
        handleDropdownAvailability(false);
    });
}

function updateDropdownData(dropdown, data) {
    const emptyOption = getEmptyOption(dropdown);
    dropdown.empty().append(emptyOption);
    for (const [key, value] of Object.entries(data)) {
        dropdown.append(new Option(key, value, false, false));
    }
    dropdown.prop('disabled', false);
}
