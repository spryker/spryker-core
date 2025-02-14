<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Business\Verifier;

use Generated\Shared\Transfer\CustomerCriteriaTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\VerificationTokenCustomerChangeDataResponseTransfer;
use Spryker\Zed\CustomerDataChangeRequest\Business\Mail\CustomerDataChangeRequestMailSenderInterface;
use Spryker\Zed\CustomerDataChangeRequest\Business\Writer\CustomerDataChangeRequestWriterInterface;
use Spryker\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestConfig;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToCustomerFacadeInterface;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToUtilTextServiceInterface;

class VerificationEmailSender implements VerificationEmailSenderInterface
{
    /**
     * @var int
     */
    protected const VERIFICATION_TOKEN_LENGTH = 32;

    /**
     * @param \Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\CustomerDataChangeRequest\Business\Mail\CustomerDataChangeRequestMailSenderInterface $customerDataChangeRequestMailSender
     * @param \Spryker\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestConfig $customerDataChangeRequestConfig
     * @param \Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Zed\CustomerDataChangeRequest\Business\Writer\CustomerDataChangeRequestWriterInterface $customerDataChangeRequestWriter
     */
    public function __construct(
        protected CustomerDataChangeRequestToCustomerFacadeInterface $customerFacade,
        protected CustomerDataChangeRequestMailSenderInterface $customerDataChangeRequestMailSender,
        protected CustomerDataChangeRequestConfig $customerDataChangeRequestConfig,
        protected CustomerDataChangeRequestToUtilTextServiceInterface $utilTextService,
        protected CustomerDataChangeRequestWriterInterface $customerDataChangeRequestWriter
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\VerificationTokenCustomerChangeDataResponseTransfer
     */
    public function send(CustomerTransfer $customerTransfer): VerificationTokenCustomerChangeDataResponseTransfer
    {
        $verificationTokenCustomerChangeDataResponseTransfer = (new VerificationTokenCustomerChangeDataResponseTransfer())->setIsSent(false);

        $customerResponseTransfer = $this->customerFacade->getCustomerByCriteria(
            (new CustomerCriteriaTransfer())->setIdCustomer($customerTransfer->getIdCustomer()),
        );

        if (
            !$customerResponseTransfer->getCustomerTransfer() ||
            $customerTransfer->getEmail() === $customerResponseTransfer->getCustomerTransfer()->getEmail()
        ) {
            return $verificationTokenCustomerChangeDataResponseTransfer;
        }

        $verificationToken = $this->generateKey();

        $this->customerDataChangeRequestWriter->saveEmailChangeRequest($customerTransfer, $verificationToken);

        $this->customerDataChangeRequestMailSender->sendEmailChangeVerificationToken(
            $customerTransfer,
            $this->customerDataChangeRequestConfig->getEmailChaneTokenUrl($verificationToken),
        );

        $customerTransfer->setEmail($customerResponseTransfer->getCustomerTransfer()->getEmail());

        return $verificationTokenCustomerChangeDataResponseTransfer->setIsSent(true);
    }

    /**
     * @return string
     */
    protected function generateKey(): string
    {
        return $this->utilTextService->generateRandomString(static::VERIFICATION_TOKEN_LENGTH);
    }
}
