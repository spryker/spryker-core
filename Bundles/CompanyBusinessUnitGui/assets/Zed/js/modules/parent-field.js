/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @see \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitForm
 * @type {string}
 */
const companyFieldPath = 'select#company-business-unit_fk_company';
/**
 * @see \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitForm
 * @type {string}
 */
const parentFieldPath = 'select#company-business-unit_fk_parent_company_business_unit';
/**
 * @see \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitFormDataProvider::OPTION_ATTRIBUTE_DATA
 * @type {string}
 */
const attributeIdCompany = 'id_company';

function initialize() {
    const companyField = new CompanyFieldHandler();

    companyField.addListenerOnCompany();
}

function CompanyFieldHandler() {
    const $companyField = $(companyFieldPath);
    const $parentField = $(parentFieldPath);

    function addListenerOnCompany() {
        if (apllyeble()) {
            setParentNames();
            $companyField.change(setParentNames);
        }
    }

    /**
     * @returns {bool}
     */
    function apllyeble() {
        return $parentField.length && $companyField.length;
    }

    function setParentNames() {
        $parentField.children().each(toggleOption);

        blinkParentField();
    }

    function toggleOption() {
        const companyId = parseInt(getCompanyId());
        const $parentOption = $(this);

        if (!$parentOption.val()) {
            return;
        }

        if (!companyId) {
            $parentOption.hide();
        } else if ($parentOption.data(attributeIdCompany) === companyId) {
            $parentOption.show();
        } else {
            $parentOption.hide();
        }
    }

    /**
     * @returns Null|{string}
     */
    function getCompanyId() {
        return $companyField.val();
    }

    function blinkParentField() {
        $parentField.effect("highlight", {}, 3000);
    }

    return {
        addListenerOnCompany: addListenerOnCompany,
    };
}

module.exports = {
    initialize: initialize,
};
