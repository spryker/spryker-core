<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Business;

use Generated\Shared\Transfer\CompanyUserIdentifierTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface OauthPermissionFacadeInterface
{
    /**
     * Specification:
     *  - Expands the CustomerIdentifierTransfer with permissions collection if idCompanyUser is set up in CustomerIdentifierTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function expandCustomerIdentifierWithPermissions(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer;

    /**
     * Specification:
     *  - Expands the CompanyUserIdentifierTransfer with permissions collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserIdentifierTransfer $companyUserIdentifierTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserIdentifierTransfer
     */
    public function expandCompanyUserIdentifierWithPermissions(
        CompanyUserIdentifierTransfer $companyUserIdentifierTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserIdentifierTransfer;

    /**
     * Specification:
     * - Filters user identifier array to remove configured in
     * \Spryker\Zed\OauthPermission\OauthPermissionConfig::getOauthUserIdentifierFilterKeys() keys.
     *
     * @api
     *
     * @param array $userIdentifier
     *
     * @return array
     */
    public function filterOauthUserIdentifier(array $userIdentifier): array;
}
