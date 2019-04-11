<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Business\Expander;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CustomerIdentifierExpander implements CustomerIdentifierExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function expandCustomerIdentifier(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer {
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();

        if ($companyUserTransfer) {
            $customerIdentifierTransfer->setUuidCompanyUser($companyUserTransfer->getUuid());
            $customerIdentifierTransfer->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());
        }

        return $customerIdentifierTransfer;
    }
}
