<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

class CustomerPasswordPolicyProvider implements CustomerPasswordPolicyProviderInterface
{
    /**
     * @var \Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy\CustomerPasswordPolicyInterface
     */
    protected $customerPasswordPolicy;

    /**
     * @param \Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy\CustomerPasswordPolicyInterface[] $customerPasswordPolicies
     */
    public function __construct(array $customerPasswordPolicies)
    {
        $defaultCustomerPasswordPolicy = new CustomerPasswordPolicyDefault();
        foreach ($customerPasswordPolicies as $customerPasswordPolicy) {
            $defaultCustomerPasswordPolicy->addPolicy($customerPasswordPolicy);
        }
        $this->customerPasswordPolicy = $defaultCustomerPasswordPolicy;
    }

    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy\CustomerPasswordPolicyInterface
     */
    public function getCustomerPasswordPolicy(): CustomerPasswordPolicyInterface
    {
        return $this->customerPasswordPolicy;
    }
}
