<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;

abstract class AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    /**
     * @var \Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy\CustomerPasswordPolicyInterface
     */
    protected $nextCustomerPasswordPolicy;

    protected const PASSWORD_POLICY_ATTRIBUTE_REQUIRED = 'required';

    /**
     * @param \Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy\CustomerPasswordPolicyInterface $customerPasswordPolicy
     *
     * @return void
     */
    public function addPolicy(CustomerPasswordPolicyInterface $customerPasswordPolicy): void
    {
        if (!isset($this->nextCustomerPasswordPolicy)) {
            $this->nextCustomerPasswordPolicy = $customerPasswordPolicy;
        } else {
            $this->nextCustomerPasswordPolicy->addPolicy($customerPasswordPolicy);
        }
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function proceed(
        string $password,
        CustomerResponseTransfer $customerResponseTransfer
    ): CustomerResponseTransfer {
        return isset($this->nextCustomerPasswordPolicy) ?
            $this->nextCustomerPasswordPolicy->validatePassword($password, $customerResponseTransfer) :
            $customerResponseTransfer;
    }

    /**
     * Adds error MessageTransfer to CustomerResponseTransfer.
     *
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function addError(
        CustomerResponseTransfer $customerResponseTransfer,
        string $errorMessage
    ): CustomerResponseTransfer {
        $customerErrorTransfer = (new CustomerErrorTransfer())
            ->setMessage($errorMessage);

        return $customerResponseTransfer
            ->setIsSuccess(false)
            ->addError($customerErrorTransfer);
    }
}
