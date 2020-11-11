<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Zed\Customer\CustomerConfig;

class SequenceCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    protected const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_SEQUENCE = 'customer.password.error.sequence';

    /**
     * @var int
     */
    protected $customerPasswordSequenceLimit;

    /**
     * @param \Spryker\Zed\Customer\CustomerConfig $customerConfig
     */
    public function __construct(CustomerConfig $customerConfig)
    {
        $this->customerPasswordSequenceLimit = $customerConfig->getCustomerPasswordSequenceLimit();
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(
        string $password,
        CustomerResponseTransfer $customerResponseTransfer
    ): CustomerResponseTransfer {
        if (!$this->customerPasswordSequenceLimit) {
            return $customerResponseTransfer;
        }
        $counter = 1;
        $prevChar = '';
        foreach (mb_str_split($password) as $char) {
            $counter = $char === $prevChar ? ++$counter : $counter = 1;
            if ($this->customerPasswordSequenceLimit < $counter) {
                $customerErrorTransfer = (new CustomerErrorTransfer())
                    ->setMessage(static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_SEQUENCE);

                return $customerResponseTransfer->setIsSuccess(false)
                    ->addError($customerErrorTransfer);
            }
            $prevChar = $char;
        }

        return $customerResponseTransfer;
    }
}
