<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestUserTransfer;

class CustomerMapper implements CustomerMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapRestUserTransferToCustomerTransfer(RestUserTransfer $restUserTransfer, CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $companyUserTransfer = $this->mapRestUserTransferToCompanyUserTransfer(
            $restUserTransfer,
            new CompanyUserTransfer()
        );

        return $customerTransfer->setCustomerReference($restUserTransfer->getNaturalIdentifier())
            ->setCompanyUserTransfer($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function mapRestUserTransferToCompanyUserTransfer(
        RestUserTransfer $restUserTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer {
        return $companyUserTransfer->setIdCompanyUser($restUserTransfer->getIdCompanyUser())
            ->setUuid($restUserTransfer->getUuidCompanyUser())
            ->setFkCompany($restUserTransfer->getIdCompany())
            ->setFkCompanyBusinessUnit($restUserTransfer->getIdCompanyBusinessUnit());
    }
}
