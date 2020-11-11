<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Zed\Customer\CustomerConfig;

class DenyListCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    protected const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_DENY_LIST = 'customer.password.error.deny_list';

    /**
     * @var string[]
     */
    protected $customerPasswordDenyList = [];

    /**
     * @param \Spryker\Zed\Customer\CustomerConfig $customerConfig
     */
    public function __construct(CustomerConfig $customerConfig)
    {
        $this->customerPasswordDenyList = $customerConfig->getCustomerPasswordDenyList();
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        if (!in_array($password, $this->customerPasswordDenyList, true)) {
            return $customerResponseTransfer;
        }

        return $this->addPasswordInDenyListError($customerResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function addPasswordInDenyListError(CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        $customerErrorTransfer = (new CustomerErrorTransfer())
            ->setMessage(static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_DENY_LIST);

        return $customerResponseTransfer
            ->setIsSuccess(false)
            ->addError($customerErrorTransfer);
    }
}
