/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

$(document).ready( function () {
    const companyField = $('input#company-business-unit_id_company_business_unit') || {value: null};
    const parentField = $('select#company-business-unit_fk_parent_company_business_unit');

    /**
     * @returns Null|string
     */
    function getCompanyId() {
        alert ('company id: ' + companyField.value)
        return companyField.value;
    }

    /**
     * @returns {string: string}
     */
    function getBusinessUnitList() {
        const idCompany = getCompanyId();

        alert ('getBusinessUnitList company: ' + idCompany)
        if (!idCompany) {
            return {};
        }

        return {
            idCompany: 'some name',
        };
    }

    function cleanParents() {
        alert ('cleanParents')
        if (!cleanParents) {
            return;
        }

        alert ('cleanParents parents: ' + cleanParents.id)
        while (parentField.firstChild) {
            parentField.removeChild(parentField.firstChild);
        }
    }

    function setParentNames() {

        alert ('setParentNames')

        if (!parentField) {
            return;
        }
        cleanParents();
        const parentList = getBusinessUnitList();
        const fragment = document.createDocumentFragment();

        parentList.forEach(function(parentBU, index) {
            alert('setParentNames each: ' + )
            let opt = document.createElement('option');
            opt.innerHTML = parentBU;
            opt.value = parentBU;
            fragment.appendChild(opt);
        });

        parentField.appendChild(fragment);
    }

    function addListenerOnCompany() {

        alert ('addListenerOnCompany')
        if (!companyField) {
            return;
        }

        companyField.change(function () {
            alert ('change')
            setParentNames();
        });
    }

    addListenerOnCompany();
});
