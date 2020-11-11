<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Zed\Customer\CustomerConfig;

class CustomerPasswordPolicyValidator implements CustomerPasswordPolicyValidatorInterface
{
    /**
     * @var \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface[]
     */
    protected $customerPasswordPolicies;

    /**
     * @var string[]
     */
    protected $customerPasswordAllowList = [];

    /**
     * @param \Spryker\Zed\Customer\CustomerConfig $customerConfig
     * @param \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface[] $customerPasswordPolicies
     */
    public function __construct(CustomerConfig $customerConfig, array $customerPasswordPolicies)
    {
        $this->customerPasswordAllowList = $customerConfig->getCustomerPasswordAllowList();
        $this->customerPasswordPolicies = $customerPasswordPolicies;
    }

    /**
     * @param string $password
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password): CustomerResponseTransfer
    {
        $customerResponseTransfer = (new CustomerResponseTransfer())
            ->setIsSuccess(true);

        if (in_array($password, $this->customerPasswordAllowList, true)) {
            return $customerResponseTransfer;
        }

        foreach ($this->customerPasswordPolicies as $customerPasswordPolicy) {
            $customerPasswordPolicy->validatePassword($password, $customerResponseTransfer);
        }

        return $customerResponseTransfer;
    }
}
