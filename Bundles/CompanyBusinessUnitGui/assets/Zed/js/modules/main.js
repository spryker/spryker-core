/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

$(document).ready( function () {
    const field = createIt();

    field.addListenerOnCompany();
});

function createIt() {
    return {

        $companyField: $('select#company-business-unit_fk_company'),
        $parentField: $('select#company-business-unit_fk_parent_company_business_unit'),

        /**
         * @returns Null|string
         */
        getCompanyId: function () {
            if (!this.$companyField) {
                return null;
            }
            const idCompany = this.$companyField.val();

            return idCompany;
        },

        /**
         * @returns {string: string}
         */
        getBusinessUnitList: function () {
            const idCompany = this.getCompanyId();
            if (!idCompany) {
                return {};
            }
            const companyUnitMap = this.$parentField.data('company_unit_map');
            const companyUnitNames = companyUnitMap[idCompany];

            return companyUnitNames;
        },

        cleanParents: function () {
            if (!this.$parentField) {
                return;
            }
            this.$parentField.children().each(function() {
                const $option = $(this);
                if (!$option.val()) {
                    return;
                }
                $option.remove();
            });
        },

        setParentNames: function () {
            if (!this.$parentField) {
                return;
            }

            this.cleanParents();
            const parentList = this.getBusinessUnitList();
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

            this.$parentField.append(fragment);
        },

        addListenerOnCompany: function () {
            if (!this.$companyField) {
                return;
            }
            const parentFieldHandler = this;

            this.$companyField.change(function () {
                parentFieldHandler.setParentNames();
            });
        },
    };
}
