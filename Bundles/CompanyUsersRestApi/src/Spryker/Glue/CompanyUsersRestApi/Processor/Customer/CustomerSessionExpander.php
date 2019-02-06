<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\Customer;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomerSessionExpander implements CustomerSessionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expand(CustomerTransfer $customerTransfer, RestRequestInterface $restRequest): CustomerTransfer
    {
        if (!$restRequest->getUser()) {
            return $customerTransfer;
        }

        $restUserIdentifier = $restRequest->getUser()->getRestUserIdentifierTransfer();

        if ($restUserIdentifier->getIdCompanyUser() !== null && $restUserIdentifier->getIdCompany() !== null) {
            $companyUserTransfer = (new CompanyUserTransfer())
                ->setIdCompanyUser((int)$restUserIdentifier->getIdCompanyUser())
                ->setFkCompany((int)$restUserIdentifier->getIdCompany());

            $customerTransfer->setCompanyUserTransfer($companyUserTransfer);
        }

        return $customerTransfer;
    }
}
