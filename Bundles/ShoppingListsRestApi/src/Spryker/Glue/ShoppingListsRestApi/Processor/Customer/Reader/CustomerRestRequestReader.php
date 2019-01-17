<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\Customer\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;

class CustomerRestRequestReader implements CustomerRestRequestReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function readCustomerResponseTransferFromRequest(RestRequestInterface $restRequest): CustomerResponseTransfer
    {
        $companyUserUuid = $restRequest->getHttpRequest()->headers->get(SharedShoppingListsRestApiConfig::X_COMPANY_USER_ID_HEADER_KEY);

        if (!$companyUserUuid) {
            return (new CustomerResponseTransfer())->addError(
                (new CustomerErrorTransfer())
                    ->setMessage(SharedShoppingListsRestApiConfig::RESPONSE_CODE_COMPANY_USER_NOT_FOUND)
            )->setIsSuccess(false);
        }

        $customerTransfer = (new CustomerTransfer())
            ->setCompanyUserTransfer(
                (new CompanyUserTransfer())
                    ->setUuid($companyUserUuid)
            );

        if ($restRequest->getUser()) {
            $customerTransfer->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());
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
