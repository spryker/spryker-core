<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Business\Customer\Strategy;

use Generated\Shared\Transfer\CustomerCriteriaTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestConditionsTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Generated\Shared\Transfer\CustomerDataChangeResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Shared\CustomerDataChangeRequest\Enum\CustomerDataChangeRequestStatusEnum;
use Spryker\Shared\CustomerDataChangeRequest\Enum\CustomerDataChangeRequestTypeEnum;
use Spryker\Zed\CustomerDataChangeRequest\Business\Logger\AuditLoggerInterface;
use Spryker\Zed\CustomerDataChangeRequest\Business\Notifier\NotificationEmailSenderInterface;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToCustomerFacadeInterface;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToGlossaryFacadeInterface;
use Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestEntityManagerInterface;
use Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestRepositoryInterface;

class EmailConfirmCustomerDataChangeRequestStrategy implements ConfirmCustomerDataChangeRequestStrategyInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_CUSTOMER_DATA_CHANGE_REQUEST = 'customer.data_change_request.invalid';

    /**
     * @param \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestRepositoryInterface $customerDataChangeRequestRepository
     * @param \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestEntityManagerInterface $customerDataChangeRequestEntityManager
     * @param \Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\CustomerDataChangeRequest\Business\Logger\AuditLoggerInterface $auditLogger
     * @param \Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\CustomerDataChangeRequest\Business\Notifier\NotificationEmailSenderInterface $notificationEmailSender
     */
    public function __construct(
        protected CustomerDataChangeRequestRepositoryInterface $customerDataChangeRequestRepository,
        protected CustomerDataChangeRequestEntityManagerInterface $customerDataChangeRequestEntityManager,
        protected CustomerDataChangeRequestToCustomerFacadeInterface $customerFacade,
        protected AuditLoggerInterface $auditLogger,
        protected CustomerDataChangeRequestToGlossaryFacadeInterface $glossaryFacade,
        protected NotificationEmailSenderInterface $notificationEmailSender
    ) {
    }

    /**
     * Specification:
     * - Checks if the strategy is applicable for the customer data change request.
     *
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): bool
    {
        return $customerDataChangeRequestTransfer->getTypeOrFail() === CustomerDataChangeRequestTypeEnum::EMAIL->value;
    }

    /**
     * Specification:
     * - Executes the strategy to confirm customer data change request.
     *
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeResponseTransfer
     */
    public function execute(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): CustomerDataChangeResponseTransfer
    {
        $customerDataChangeResponseTransfer = new CustomerDataChangeResponseTransfer();

        $customerDataChangeRequestTransfer = $this->findValidChangeRequest($customerDataChangeRequestTransfer);
        if ($customerDataChangeRequestTransfer === null) {
            return $customerDataChangeResponseTransfer
                ->addError((new ErrorTransfer())->setMessage($this->glossaryFacade->translate(static::GLOSSARY_KEY_INVALID_CUSTOMER_DATA_CHANGE_REQUEST)));
        }
        $customerEmail = $this->customerFacade->getCustomerByCriteria(
            (new CustomerCriteriaTransfer())->setIdCustomer($customerDataChangeRequestTransfer->getIdCustomerOrFail()),
        )->getCustomerTransferOrFail()->getEmailOrFail();

        $customerTransfer = $this->updateCustomerEmail($customerDataChangeRequestTransfer);

        $this->customerFacade->updateCustomer($customerTransfer);
        $this->auditLogger->addSuccessfulEmailUpdateAuditLog();

        $this->markCustomerDataChangeRequestAsCompleted($customerDataChangeRequestTransfer);

        $this->notificationEmailSender->send($customerTransfer->setEmail($customerEmail));

        return $customerDataChangeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function updateCustomerEmail(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): CustomerTransfer
    {
        $customerTransfer = $this->customerFacade->getCustomerByCriteria(
            (new CustomerCriteriaTransfer())->setIdCustomer($customerDataChangeRequestTransfer->getIdCustomerOrFail()),
        )->getCustomerTransferOrFail();

        $customerTransfer->setEmail($customerDataChangeRequestTransfer->getDataOrFail());
        $customerTransfer->setChangeRequest($customerDataChangeRequestTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer|null
     */
    protected function findValidChangeRequest(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): ?CustomerDataChangeRequestTransfer
    {
        $customerDataChangeRequests = $this->customerDataChangeRequestRepository->get(
            (new CustomerDataChangeRequestCriteriaTransfer())
                ->setCustomerDataChangeRequestConditions(
                    (new CustomerDataChangeRequestConditionsTransfer())
                        ->setVerificationToken($customerDataChangeRequestTransfer->getVerificationTokenOrFail())
                        ->addStatus(CustomerDataChangeRequestStatusEnum::PENDING->value)
                        ->setIsExpired(false)
                        ->addType(CustomerDataChangeRequestTypeEnum::EMAIL->value),
                ),
        )->getCustomerDataChangeRequests();

        return $customerDataChangeRequests->offsetExists(0) ? $customerDataChangeRequests->offsetGet(0) : null;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return void
     */
    protected function markCustomerDataChangeRequestAsCompleted(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): void
    {
        $this->customerDataChangeRequestEntityManager->saveEmailCustomerDataChangeRequest(
            $customerDataChangeRequestTransfer->setStatus(CustomerDataChangeRequestStatusEnum::COMPLETED->value),
        );
    }
}
