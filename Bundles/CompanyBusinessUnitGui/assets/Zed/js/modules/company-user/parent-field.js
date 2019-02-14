/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @see \Spryker\Zed\CompanyUserGui\Communication\Form\CompanyUserForm
 * @type {string}
 */
const companyFieldPath = 'select#company-user_fk_company';

/**
 * @see \Spryker\Zed\CompanyUserBusinessUnitGui\Communication\Form\CompanyUserBusinessUnitChoiceFormType
 * @type {string}
 */
const parentFieldPath = 'select#company-user_fk_company_business_unit';

/**
 * @type {string}
 */
const parentAllOptionsFieldId = 'all-options';

/**
 * @see \Spryker\Zed\CompanyUserBusinessUnitGui\Communication\Form\DataProvider\CompanyUserBusinessUnitFormDataProvider::OPTION_ATTRIBUTE_DATA
 * @type {string}
 */
const attributeIdCompany = 'id_company';

function initialize() {
    const companyField = new CompanyFieldHandler();

    companyField.cloneOptions();
    companyField.addListenerOnCompany();
}

function CompanyFieldHandler() {
    const $companyField = $(companyFieldPath);
    const $parentField = $(parentFieldPath);

    function addListenerOnCompany() {
        if (isApplicable()) {
            setParentNames();
            $companyField.change(setParentNames);
        }
    }

    function cloneOptions() {
        $('<div id="' + parentAllOptionsFieldId + '" class="hidden"></div>').html($parentField.html()).insertAfter($parentField);
    }

    /**
     * @returns {bool}
     */
    function isApplicable() {
        return $parentField.length && $companyField.length;
    }

    function setParentNames() {
        restoreAllParentOptions();
        $parentField.children().each(toggleOption);

        blinkParentField();
    }

    function toggleOption() {
        const companyId = getCompanyId();
        const $parentOption = $(this);

        if (!$parentOption.val()) {
            $parentField.attr('disabled', true);

            return;
        }

        if (!companyId || $parentOption.data(attributeIdCompany) == companyId) {
            return;
        }

        $parentField.attr('disabled', false);
        $parentOption.remove();
    }

    /**
     * @returns NaN|{string}
     */
    function getCompanyId() {
        return parseInt($companyField.val());
    }

    function restoreAllParentOptions() {
        const $parentAllOptionsField = $('#' + parentAllOptionsFieldId);

        $parentField.html($parentAllOptionsField.html());
    }

    function blinkParentField() {
        $parentField.effect("highlight", {}, 3000);
    }

    return {
        addListenerOnCompany: addListenerOnCompany,
        cloneOptions: cloneOptions,
    };
}

module.exports = {
    initialize: initialize,
};
