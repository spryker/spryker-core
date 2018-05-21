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

    /**
     * @returns {Map<string:string>}
     */
    function getBusinessUnitList() {
        const idCompany = getCompanyId();
        if (!idCompany) {
            return {};
        }
        const companyUnitMap = $parentField.data('company_unit_map');

        return companyUnitMap[idCompany];
    }

    function cleanParents() {
        $parentField
            .children()
            .each(function() {
                const $option = $(this);
                if (!$option.val()) {
                    return;
                }
                $option.remove();
            });
    }

    function setParentNames() {
        cleanParents();
        const parentList = getBusinessUnitList();
        const fragment = document.createDocumentFragment();

        for (const idBusinessUnit in parentList) {
            if (!parentList.hasOwnProperty(idBusinessUnit)) {
                continue;
            }
            const BusinessUnitName = parentList[idBusinessUnit];

            let opt = document.createElement('option');
            opt.innerHTML = BusinessUnitName;
            opt.value = idBusinessUnit;
            fragment.appendChild(opt);
        }

        $parentField.append(fragment);
    }

    function addListenerOnCompany() {
        if ($parentField && $companyField) {
            $companyField.change(setParentNames);
        }
    }

    return {
        addListenerOnCompany: addListenerOnCompany,
    };
}

module.exports = {
    initialize: initialize,
};
