/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

const companyFieldPath = 'select#company-business-unit_fk_company';
const parentFieldPath = 'select#company-business-unit_fk_parent_company_business_unit';

function initialize() {
    const companyField = new CompanyFieldHandler();

    companyField.addListenerOnCompany();
}

function CompanyFieldHandler() {
    const $companyField = $(companyFieldPath);
    const $parentField = $(parentFieldPath);

    /**
     * @returns Null|string
     */
    function getCompanyId() {
        return $companyField.val();
    }

    function blinkParentField() {
        $parentField.effect("highlight", {}, 3000);
    }

    function toggleOption() {
        console.log('toggleOption')
        const companyId = getCompanyId();
        const $parentOption = $(this);
        console.log($parentOption.data('id_company'))
        console.log(typeof $parentOption.data('id_company'))
        console.log(companyId)
        console.log(typeof companyId)
        console.log($parentOption.data('id_company') === companyId)

        if (!$parentOption.val()) {
            return;
        }

        if ($parentOption.data('id_company') == companyId) {
            $parentOption.show();
        } else {
            $parentOption.hide();
        }
    }

    function setParentNames() {
        $parentField.children().each(toggleOption);

        blinkParentField();
    }

    function addListenerOnCompany() {
        setParentNames();

        if ($parentField && $companyField) {
            $companyField.change(setParentNames);
        }
    }

    return {
        addListenerOnCompany: addListenerOnCompany,
        $companyField: $companyField,
        $parentField: $parentField,
        jQuery: $,
    };
}

module.exports = {
    initialize: initialize,
    bm13kk: CompanyFieldHandler,
};
