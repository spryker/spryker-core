/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

$(document).ready( function () {
    console.log('module start');

    unitParenField.addListenerOnCompany();
});

const unitParenField = {

    companyField: $('input#company-business-unit_id_company_business_unit') || {value: null},
    parentField: $('select#company-business-unit_fk_parent_company_business_unit'),

    /**
     * @returns Null|string
     */
    getCompanyId: function(){
        console.log('company id: ' + this.companyField.value)
        return this.companyField.value;
    },

    /**
     * @returns {string: string}
     */
    getBusinessUnitList: function(){
        const idCompany = this.getCompanyId();

        console.log('getBusinessUnitList company: ' + idCompany)
        if (!idCompany) {
            return {};
        }

        return {
            idCompany: 'some name',
        };
    },

    cleanParents: function(){
        console.log('cleanParents')
        if (!this.parentField) {
            return;
        }

        console.log('cleanParents parents id: ' + this.parentField.id)
        while (this.parentField.firstChild) {
            this.parentField.removeChild(this.parentField.firstChild);
        }
    },

    setParentNames: function(){

        console.log('setParentNames')

        if (!this.parentField) {
            return;
        }
        this.cleanParents();
        const parentList = this.getBusinessUnitList();
        const fragment = document.createDocumentFragment();

        parentList.forEach(function(parentBU, index) {
            console.log('setParentNames each: ' + parentBU);
            let opt = document.createElement('option');
            opt.innerHTML = parentBU;
            opt.value = parentBU;
            fragment.appendChild(opt);
        });

        this.parentField.appendChild(fragment);
    },

    addListenerOnCompany: function(){

        console.log('addListenerOnCompany')
        if (!this.companyField) {
            return;
        }

        this.companyField.change(function (){
            console.log('change')
            this.setParentNames();
        });
    },
};
