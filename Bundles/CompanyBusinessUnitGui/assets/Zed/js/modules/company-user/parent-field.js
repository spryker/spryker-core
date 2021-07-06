/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @see \Spryker\Zed\CompanyUserGui\Communication\Form\CompanyUserForm
 * @type {string}
 */
var companyFieldPath = 'select#company-user_fk_company';

/**
 * @see \Spryker\Zed\CompanyUserBusinessUnitGui\Communication\Form\CompanyUserBusinessUnitChoiceFormType
 * @type {string}
 */
var companyBusinessUnitFieldPath = 'select#company-user_fk_company_business_unit';

function initialize() {
    var companyBusinessUnitField = new companyBusinessUnitFieldHandler();

    companyBusinessUnitField.init();
    companyBusinessUnitField.toogleCompanyBusinessUnitVisibility();
}

function companyBusinessUnitFieldHandler() {
    var $companyField = $(companyFieldPath);
    var $companyBusinessUnitField = $(companyBusinessUnitFieldPath);

    function toogleCompanyBusinessUnitVisibility() {
        var isDisabled = !$companyField.val();

        $(companyBusinessUnitFieldPath).prop('disabled', isDisabled);
    }

    function init() {
        $companyBusinessUnitField.select2({
            ajax: {
                url: $companyBusinessUnitField.attr('data-url'),
                delay: 250,
                dataType: 'json',
                cache: true,
                data: function (params) {
                    var query = {
                        suggestion: params.term,
                        idCompany: $companyField.val(),
                    };

                    return query;
                },
            },
            minimumInputLength: 2,
            multiple: false,
        });

        $companyField.on('change', function () {
            toogleCompanyBusinessUnitVisibility();
            $companyBusinessUnitField.val(null).trigger('change');
        });
    }

    return {
        toogleCompanyBusinessUnitVisibility: toogleCompanyBusinessUnitVisibility,
        init: init,
    };
}

module.exports = {
    initialize: initialize,
};
