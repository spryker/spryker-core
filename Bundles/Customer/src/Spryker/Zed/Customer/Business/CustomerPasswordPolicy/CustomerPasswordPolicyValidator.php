<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Zed\Customer\CustomerConfig;

class CustomerPasswordPolicyValidator implements CustomerPasswordPolicyValidatorInterface
{
    protected const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_BLACK_LIST = 'customer.password.error.black_list';

    /**
     * @var \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface[]
     */
    protected $customerPasswordPolicies;

    /**
     * @var string[]
     */
    protected $customerPasswordWhitelist = [];

    /**
     * @var string[]
     */
    protected $customerPasswordBlacklist = [];

    /**
     * @param \Spryker\Zed\Customer\CustomerConfig $customerConfig
     * @param \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface[] $customerPasswordPolicies
     */
    public function __construct(CustomerConfig $customerConfig, array $customerPasswordPolicies)
    {
        $this->customerPasswordWhitelist = $customerConfig->getCustomerPasswordWhiteList();
        $this->customerPasswordBlacklist = $customerConfig->getCustomerPasswordBlackList();
        $this->customerPasswordPolicies = $customerPasswordPolicies;
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        if (in_array($password, $this->customerPasswordWhitelist)) {
            return $customerResponseTransfer;
        }

        if (in_array($password, $this->customerPasswordBlacklist)) {
            return $this->addPasswordInBlacklistError($customerResponseTransfer);
        }
        foreach ($this->customerPasswordPolicies as $customerPasswordPolicy) {
            $customerPasswordPolicy->validatePassword($password, $customerResponseTransfer);
        }

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function addPasswordInBlacklistError(CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        $customerErrorTransfer = (new CustomerErrorTransfer())
            ->setMessage(static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_BLACK_LIST);

        return $customerResponseTransfer
            ->setIsSuccess(false)
            ->addError($customerErrorTransfer);
    }
}
