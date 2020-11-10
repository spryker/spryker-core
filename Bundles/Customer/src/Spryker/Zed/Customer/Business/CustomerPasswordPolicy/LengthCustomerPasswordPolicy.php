<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Zed\Customer\CustomerConfig;

class LengthCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    protected const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_MIN = 'customer.password.error.min_length';

    protected const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_MAX = 'customer.password.error.max_length';

    /** @var int */
    protected $customerPasswordMaxLength;

    /** @var int */
    protected $customerPasswordMinLength;

    public function __construct(CustomerConfig $customerConfig)
    {
        $this->customerPasswordMaxLength = $customerConfig->getCustomerPasswordMaxLength();
        $this->customerPasswordMinLength = $customerConfig->getCustomerPasswordMinLength();
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        $passwordLength = mb_strlen($password);
        if ($this->customerPasswordMinLength && $passwordLength < $this->customerPasswordMinLength) {
            return $this->addError($customerResponseTransfer, static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_MIN);
        }

        if ($this->customerPasswordMaxLength && $passwordLength > $this->customerPasswordMaxLength) {
            return $this->addError($customerResponseTransfer, static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_MAX);
        }

        return $customerResponseTransfer;
    }

    /**
     * @param CustomerResponseTransfer $customerResponseTransfer
     * @param string $errorMessage
     *
     * @return CustomerResponseTransfer
     */
    protected function addError(CustomerResponseTransfer $customerResponseTransfer, string $errorMessage): CustomerResponseTransfer
    {
        $customerErrorTransfer = (new CustomerErrorTransfer())
            ->setMessage($errorMessage);
        $customerResponseTransfer->setIsSuccess(false)
            ->addError($customerErrorTransfer);

        return $customerResponseTransfer;
    }
}
