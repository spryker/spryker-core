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
     * @var int|null
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
        if (!$this->customerPasswordSequenceLimit || $this->customerPasswordSequenceLimit < 0) {
            return $customerResponseTransfer;
        }

        $regularExpression = $this->getSequenceRegularExpression($this->customerPasswordSequenceLimit);
        if (!preg_match($regularExpression, $password)) {
            return $customerResponseTransfer;
        }

        $customerErrorTransfer = (new CustomerErrorTransfer())
            ->setMessage(static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_SEQUENCE);
        $customerResponseTransfer->setIsSuccess(false)
            ->addError($customerErrorTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param int $sequenceLimit
     *
     * @return string
     */
    protected function getSequenceRegularExpression(int $sequenceLimit): string
    {
        return '/(.)' . str_repeat('\1', $sequenceLimit) . '/';
    }
}
