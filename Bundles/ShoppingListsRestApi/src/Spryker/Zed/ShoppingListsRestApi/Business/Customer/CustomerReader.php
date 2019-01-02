<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\Customer;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser\CompanyUserReaderInterface;

class CustomerReader implements CustomerReaderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser\CompanyUserReaderInterface
     */
    protected $companyUserReader;

    /**
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser\CompanyUserReaderInterface $companyUserFacade
     */
    public function __construct(CompanyUserReaderInterface $companyUserFacade)
    {
        $this->companyUserReader = $companyUserFacade;
    }

    /**
     * @param string $customerReference
     * @param string $companyUserUuid
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function findCustomerByCustomerReferenceAndCompanyUserUuid(string $customerReference, string $companyUserUuid): CustomerResponseTransfer
    {
        $companyUserResponseTransfer = $this->companyUserReader->findCompanyUserByUuid($companyUserUuid, $customerReference);

        if ($companyUserResponseTransfer->getIsSuccessful() === false) {
            return $this->mapCompanyUserResponseMessagesToCustomerResponseErrors(
                $companyUserResponseTransfer,
                new CustomerResponseTransfer()
            );
        }

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($customerReference)
            ->setCompanyUserTransfer($companyUserResponseTransfer->getCompanyUser());

        return (new CustomerResponseTransfer())
            ->setIsSuccess(true)
            ->setCustomerTransfer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function mapCompanyUserResponseMessagesToCustomerResponseErrors(
        CompanyUserResponseTransfer $companyUserResponseTransfer,
        CustomerResponseTransfer $customerResponseTransfer
    ): CustomerResponseTransfer {
        $customerResponseTransfer->setIsSuccess(false);

        foreach ($companyUserResponseTransfer->getMessages() as $responseMessageTransfer) {
            $customerResponseTransfer->addError(
                (new CustomerErrorTransfer())
                    ->setMessage($responseMessageTransfer->getText())
            );
        }

        return $customerResponseTransfer;
    }
}
