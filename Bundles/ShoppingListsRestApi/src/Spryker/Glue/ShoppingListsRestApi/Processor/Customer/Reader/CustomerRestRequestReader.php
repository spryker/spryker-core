<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\Customer\Reader;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomerRestRequestReader implements CustomerRestRequestReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function readCustomerResponseTransferFromRequest(RestRequestInterface $restRequest): CustomerResponseTransfer
    {
        $customerTransfer = new CustomerTransfer();

        if ($restRequest->getRestUser()) {
            $customerTransfer->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier());
            $customerTransfer->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());
            $customerTransfer->setCompanyUserTransfer(
                (new CompanyUserTransfer())
                    ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser())
                    ->setUuid($restRequest->getRestUser()->getUuidCompanyUser())
                    ->setCompany((new CompanyTransfer())->setIdCompany($restRequest->getRestUser()->getIdCompany()))
                    ->setFkCompany($restRequest->getRestUser()->getIdCompany())
                    ->setCompanyBusinessUnit((new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($restRequest->getRestUser()->getIdCompanyBusinessUnit()))
                    ->setFkCompanyBusinessUnit($restRequest->getRestUser()->getIdCompanyBusinessUnit())
            );
        }

        return (new CustomerResponseTransfer())->setCustomerTransfer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerErrorTransfer[] $errors
     *
     * @return string[]
     */
    public function mapCustomerResponseErrorsToErrorsCodes(array $errors): array
    {
        $errorCodes = [];

        foreach ($errors as $error) {
            $errorCodes[] = $error->getMessage();
        }

        return $errorCodes;
    }
}
