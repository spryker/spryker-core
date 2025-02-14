<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Business\Mail;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestConfig;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToMailFacadeInterface;

class CustomerDataChangeRequestMailSender implements CustomerDataChangeRequestMailSenderInterface
{
    /**
     * @param \Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToMailFacadeInterface $mailFacade
     */
    public function __construct(
        protected CustomerDataChangeRequestToMailFacadeInterface $mailFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string $verificationLink
     *
     * @return void
     */
    public function sendEmailChangeVerificationToken(CustomerTransfer $customerTransfer, string $verificationLink): void
    {
        $mailTransfer = new MailTransfer();

        $mailTransfer->setType(CustomerDataChangeRequestConfig::CUSTOMER_EMAIL_CHANGE_VERIFICATION_MAIL_TYPE);
        $mailTransfer->setCustomer($customerTransfer);
        $mailTransfer->setLocale($customerTransfer->getLocale());
        $mailTransfer->setStoreName($customerTransfer->getStoreName());
        $mailTransfer->setVerificationLink($verificationLink);

        $this->mailFacade->handleMail($mailTransfer);
    }
}
