<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer\Checker;

use DateTime;
use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Spryker\Zed\Customer\CustomerConfig;

class PasswordResetExpirationChecker implements PasswordResetExpirationCheckerInterface
{
    /**
     * @var \Spryker\Zed\Customer\CustomerConfig
     */
    protected CustomerConfig $customerConfig;

    /**
     * @param \Spryker\Zed\Customer\CustomerConfig $customerConfig
     */
    public function __construct(CustomerConfig $customerConfig)
    {
        $this->customerConfig = $customerConfig;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer$customerEntity
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function checkPasswordResetExpiration(
        SpyCustomer $customerEntity,
        CustomerResponseTransfer $customerResponseTransfer
    ): CustomerResponseTransfer {
        if (!$this->customerConfig->isCustomerPasswordResetExpirationEnabled()) {
            return $customerResponseTransfer
                ->setIsSuccess(true);
        }

        /** @var \DateTime|string|null $restorePasswordDate */
        $restorePasswordDate = $customerEntity->getRestorePasswordDate();

        if (!$restorePasswordDate) {
            return $customerResponseTransfer;
        }

        if (is_string($restorePasswordDate)) {
            $restorePasswordDate = new DateTime($restorePasswordDate);
        }

        $expirationDate = clone $restorePasswordDate;
        $expirationDate->modify($this->customerConfig->getCustomerPasswordResetExpirationPeriod());
        $now = new DateTime();

        if ($now < $expirationDate) {
            return $customerResponseTransfer;
        }

        $customerErrorTransfer = (new CustomerErrorTransfer())->setMessage(CustomerConfig::GLOSSARY_KEY_CONFIRM_EMAIL_LINK_INVALID_OR_USED);

        return $customerResponseTransfer
            ->setIsSuccess(false)
            ->addError($customerErrorTransfer);
    }
}
