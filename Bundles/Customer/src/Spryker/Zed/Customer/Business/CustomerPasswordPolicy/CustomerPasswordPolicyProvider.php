<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerPasswordPolicy;

use Spryker\Zed\Customer\CustomerConfig;

class CustomerPasswordPolicyProvider implements CustomerPasswordPolicyProviderInterface
{
    /**
     * @var \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface
     */
    protected $customerPasswordPolicy;

    /**
     * @var \Spryker\Zed\Customer\CustomerConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Customer\CustomerConfig $config
     * @param \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface $defaultPolicy
     * @param \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface[] $customerPasswordPolicies
     */
    public function __construct(
        CustomerConfig $config,
        CustomerPasswordPolicyInterface $defaultPolicy,
        array $customerPasswordPolicies
    ) {
        $this->customerPasswordPolicy = $defaultPolicy;
        if ($config->isCustomerPasswordCheckEnabledOnRestorePassword())
        foreach ($customerPasswordPolicies as $customerPasswordPolicy) {
            $this->customerPasswordPolicy->addPolicy($customerPasswordPolicy);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface
     */
    public function getCustomerPasswordPolicy(): CustomerPasswordPolicyInterface
    {
        return $this->customerPasswordPolicy;
    }
}
