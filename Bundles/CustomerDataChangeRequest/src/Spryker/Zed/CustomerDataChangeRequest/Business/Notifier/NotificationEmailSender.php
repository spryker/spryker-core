<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Business\Notifier;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\VerificationTokenCustomerChangeDataResponseTransfer;
use Spryker\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestConfig;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToMailFacadeInterface;

class NotificationEmailSender implements NotificationEmailSenderInterface
{
    /**
     * @param \Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestConfig $customerDataChangeRequestConfig
     */
    public function __construct(
        protected CustomerDataChangeRequestToMailFacadeInterface $mailFacade,
        protected CustomerDataChangeRequestConfig $customerDataChangeRequestConfig
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

        $mailTransfer = new MailTransfer();
        $mailTransfer->setType(CustomerDataChangeRequestConfig::CUSTOMER_EMAIL_CHANGE_NOTIFICATION_MAIL_TYPE);
        $mailTransfer->setCustomer($customerTransfer);
        $mailTransfer->setLocale($customerTransfer->getLocale());
        $mailTransfer->setStoreName($customerTransfer->getStoreName());

        $this->mailFacade->handleMail($mailTransfer);

        return $verificationTokenCustomerChangeDataResponseTransfer->setIsSent(true);
    }
}
