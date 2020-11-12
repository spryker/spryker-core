<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Customer\CustomerConfig;

class LengthCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    protected const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_MIN = 'customer.password.error.min_length';

    protected const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_MAX = 'customer.password.error.max_length';

    protected const GLOSSARY_PARAM_VALIDATION_LENGTH = '{{ limit }}';

    /**
     * @var int
     */
    protected $customerPasswordMaxLength;

    /**
     * @var int
     */
    protected $customerPasswordMinLength;

    /**
     * @param \Spryker\Zed\Customer\CustomerConfig $customerConfig
     */
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
            return $this->addErrorMessage($customerResponseTransfer, static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_MIN, $this->customerPasswordMinLength);
        }

        if ($this->customerPasswordMaxLength && $passwordLength > $this->customerPasswordMaxLength) {
            return $this->addErrorMessage($customerResponseTransfer, static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_MAX, $this->customerPasswordMaxLength);
        }

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param string $errorMessage
     * @param int $messageParameter
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function addErrorMessage(
        CustomerResponseTransfer $customerResponseTransfer,
        string $errorMessage,
        int $messageParameter
    ): CustomerResponseTransfer {
        $messageTransfer = (new MessageTransfer())
            ->setMessage($errorMessage)
            ->setParameters([static::GLOSSARY_PARAM_VALIDATION_LENGTH => $messageParameter]);
        $customerResponseTransfer->setIsSuccess(false)
            ->setMessage($messageTransfer);

        return $customerResponseTransfer;
    }
}
