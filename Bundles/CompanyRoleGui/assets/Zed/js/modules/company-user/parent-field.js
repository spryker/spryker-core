/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @see \Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyRoleChoiceFormType
 * @type {string}
 */
const companyFieldPath = 'select#company-user_fk_company';
/**
 * @see \Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyRoleChoiceFormType
 * @type {string}
 */
const roleFieldPath = 'company-user_company_role_collection';
/**
 * @see \Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyRoleFormDataProvider::OPTION_ATTRIBUTE_DATA
 * @type {string}
 */
const attributeIdCompany = 'id_company';
/**
 * @see \Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyRoleFormDataProvider::OPTION_IS_DEFAULT
 * @type {string}
 */
const attributeIsDefault = 'is_default';

function initialize() {

    const companyField = new CompanyFieldHandler();

    companyField.init();
    companyField.addListenerOnCompany();
}

function CompanyFieldHandler() {
    const $companyField = $(companyFieldPath);
    const $roleField = $('#' + roleFieldPath);

    function init() {
        setRoleNames();
    }

    function addListenerOnCompany() {
        $companyField.on('change', setRoleNames);
    }

    function setRoleNames() {
        $roleField.find('input[type="checkbox"]').each(function(index, item) {
            toggleOption(item);
        });
    }

    function toggleOption(item) {
        const companyId = parseInt(getCompanyId());
        const $roleOption = $(item);

        if ($roleOption.data(attributeIdCompany) === companyId) {
            if ($roleOption.data(attributeIsDefault)) {
                $roleOption.prop("checked", true);
            }

            $roleOption.parent().show();
        } else {
            $roleOption.removeAttr("checked");
            $roleOption.parent().hide();
        }
    }

    /**
     * @returns Null|{string}
     */
    function getCompanyId() {
        return $companyField.val();
    }

    return {
        init: init,
        addListenerOnCompany: addListenerOnCompany,
    };
}

module.exports = {
    initialize: initialize,
};
