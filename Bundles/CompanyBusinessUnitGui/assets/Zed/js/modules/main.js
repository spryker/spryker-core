/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

$(document).ready( function () {
    console.log('module start');

    const field = createIt();
    document.bm13kk = field;
    document.$bm13kk = $;

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
            console.log('get company')

            if (!this.$companyField) {
                return null;
            }

            console.log('get company id: ' + this.$companyField.value)
            return this.$companyField.value;
        },

        /**
         * @returns {string: string}
         */
        getBusinessUnitList: function () {
            console.log('getBusinessUnitList')

            const idCompany = this.getCompanyId();

            console.log('getBusinessUnitList company: ' + idCompany)
            if (!idCompany) {
                return {};
            }

            return {
                idCompany: 'some name',
            };
        },

        cleanParents: function () {
            console.log('cleanParents')

            if (!this.$parentField) {
                return;
            }

            console.log('cleanParents parents id: ' + this.$parentField.val())

            this.$parentField.children().each(function() {
                console.log(this)
                const $option = $(this)

                if (!$option.val()) {
                    return;
                }

                $option.remove();
            });
        },

        setParentNames: function () {
            console.log('setParentNames')

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

                console.log('setParentNames indes: ' + idBusinessUnit);
                console.log('setParentNames each: ' + BusinessUnitName);

                let opt = document.createElement('option');
                opt.innerHTML = BusinessUnitName;
                opt.value = idBusinessUnit;
                fragment.appendChild(opt);
            }

            this.$parentField.append(fragment);
        },

        addListenerOnCompany: function () {
            console.log('addListenerOnCompany')

            if (!this.$companyField) {
                return;
            }
            console.log('addListenerOnCompany has field')
            console.log(this.$companyField)
            console.log(this.$companyField.change)

            const parentFieldHandler = this;

            // @TODO why this.$companyField.change() not working?
            // this.$companyField.addEventListener('change', function () {
            this.$companyField.change(function () {
                console.log('change')

                parentFieldHandler.setParentNames();
            });
        },
    };
}
