<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCryptographyExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CompanyUserIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

/**
 * Used to wrap the authorization validators.
 */
interface AuthorizationValidatorInterface
{
    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\CompanyUserIdentifierTransfer $companyUserIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function set(
        CustomerTransfer $customerTransfer,
        CompanyUserIdentifierTransfer $companyUserIdentifierTransfer
    ): CustomerTransfer;
}
