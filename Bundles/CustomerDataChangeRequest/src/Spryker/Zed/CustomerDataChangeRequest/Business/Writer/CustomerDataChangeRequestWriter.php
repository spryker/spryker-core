<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Business\Writer;

use Generated\Shared\Transfer\CustomerDataChangeRequestConditionsTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Generated\Shared\Transfer\CustomerDataChangeResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Shared\CustomerDataChangeRequest\Enum\CustomerDataChangeRequestStatusEnum;
use Spryker\Shared\CustomerDataChangeRequest\Enum\CustomerDataChangeRequestTypeEnum;
use Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestEntityManagerInterface;
use Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestRepositoryInterface;

class CustomerDataChangeRequestWriter implements CustomerDataChangeRequestWriterInterface
{
    /**
     * @param \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestEntityManagerInterface $customerDataChangeRequestEntityManager
     * @param \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestRepositoryInterface $customerDataChangeRequestRepository
     * @param array<\Spryker\Zed\CustomerDataChangeRequest\Business\Customer\Strategy\ConfirmCustomerDataChangeRequestStrategyInterface> $customerDataChangeRequestStrategies
     */
    public function __construct(
        protected CustomerDataChangeRequestEntityManagerInterface $customerDataChangeRequestEntityManager,
        protected CustomerDataChangeRequestRepositoryInterface $customerDataChangeRequestRepository,
        protected array $customerDataChangeRequestStrategies
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string $verificationToken
     *
     * @return void
     */
    public function saveEmailChangeRequest(CustomerTransfer $customerTransfer, string $verificationToken): void
    {
        $customerDataChangeRequestCollectionTransfer = $this->customerDataChangeRequestRepository->get(
            (new CustomerDataChangeRequestCriteriaTransfer())
                ->setCustomerDataChangeRequestConditions(
                    (new CustomerDataChangeRequestConditionsTransfer())
                        ->addIdCustomer($customerTransfer->getIdCustomerOrFail())
                        ->addStatus(CustomerDataChangeRequestStatusEnum::PENDING->value)
                        ->addType(CustomerDataChangeRequestTypeEnum::EMAIL->value),
                ),
        );

        $this->customerDataChangeRequestEntityManager->saveEmailCustomerDataChangeRequest(
            (new CustomerDataChangeRequestTransfer())
                ->setType(CustomerDataChangeRequestTypeEnum::EMAIL->value)
                ->setData($customerTransfer->getEmail())
                ->setVerificationToken($verificationToken)
                ->setIdCustomer($customerTransfer->getIdCustomer())
                ->setStatus(CustomerDataChangeRequestStatusEnum::PENDING->value),
        );

        foreach ($customerDataChangeRequestCollectionTransfer->getCustomerDataChangeRequests() as $customerDataChangeRequestTransfer) {
            $this->customerDataChangeRequestEntityManager->saveEmailCustomerDataChangeRequest(
                $customerDataChangeRequestTransfer->setStatus(CustomerDataChangeRequestStatusEnum::COMPLETED->value),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeResponseTransfer
     */
    public function changeCustomerData(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): CustomerDataChangeResponseTransfer
    {
        foreach ($this->customerDataChangeRequestStrategies as $customerDataChangeRequestStrategy) {
            if ($customerDataChangeRequestStrategy->isApplicable($customerDataChangeRequestTransfer)) {
                return $customerDataChangeRequestStrategy->execute($customerDataChangeRequestTransfer);
            }
        }

        return (new CustomerDataChangeResponseTransfer())
            ->addError((new ErrorTransfer())->setMessage('No strategy found for the customer data change request.'));
    }
}
