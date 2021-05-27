/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @see \Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyRoleChoiceFormType
 * @type {string}
 */
var companyFieldPath = 'select#company-user_fk_company';
/**
 * @see \Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyRoleChoiceFormType
 * @type {string}
 */
var roleFieldPath = 'company-user_company_role_collection';
/**
 * @see \Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyRoleFormDataProvider::OPTION_ATTRIBUTE_DATA
 * @type {string}
 */
var attributeIdCompany = 'id_company';
/**
 * @see \Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyRoleFormDataProvider::OPTION_IS_DEFAULT
 * @type {string}
 */
var attributeIsDefault = 'is_default';

var idCompanyUserFieldPath = 'company-user_id_company_user';
var roleSuggestUrl = '/company-role-gui/suggest';

var ajaxRequest;

function initialize() {
    var companyRoleField = new CompanyRoleFieldHandler();

    companyRoleField.init();
    companyRoleField.addListenerOnCompanyRole();
}

function CompanyRoleFieldHandler() {
    var $companyField = $(companyFieldPath);
    var $idCompanyUserFieldPathField = $('#' + idCompanyUserFieldPath);
    var $roleField = $('#' + roleFieldPath);

    function init() {
        setRoleNames();
    }

    function addListenerOnCompanyRole() {
        $companyField.on('change', getCompanyRoles);
    }

    function setRoleNames() {
        $roleField.find('input[type="checkbox"]').each(function (index, item) {
            toggleOption(item);
        });
    }

    function toggleOption(item) {
        var companyId = parseInt(getCompanyId());
        var $roleOption = $(item);

        if ($roleOption.data(attributeIsDefault)) {
            $roleOption.prop('checked', true);
        }
    }

    function getCompanyRoles() {
        if (ajaxRequest) {
            ajaxRequest.abort();
        }

        ajaxRequest = $.get(roleSuggestUrl, { idCompany: getCompanyId(), idCompanyUser: getCompanyUserId() }, function (
            companyRolesView,
        ) {
            $roleField.html(companyRolesView);
            setRoleNames();
        });
    }

    /**
     * @returns Null|{string}
     */
    function getCompanyId() {
        return $companyField.val();
    }

    /**
     * @returns Null|{string}
     */
    function getCompanyUserId() {
        return $idCompanyUserFieldPathField.val();
    }

    return {
        init: init,
        addListenerOnCompanyRole: addListenerOnCompanyRole,
    };
}

module.exports = {
    initialize: initialize,
};
