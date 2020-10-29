<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class CustomerPasswordPolicyManager implements CustomerPasswordPolicyManagerInterface
{
    /**
     * @var \Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerPasswordPolicyPluginInterface[]
     */
    protected $customerPasswordPolicyPlugins = [];

    /**
     * @param \Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerPasswordPolicyPluginInterface[] $policyPlugins
     *
     * @return void
     */
    public function setPlugins(array $policyPlugins): void
    {
        $this->customerPasswordPolicyPlugins = $policyPlugins;
    }

    /**
     * @param string $password
     * @param string[][] $customerPasswordPolicyConfig
     * @param string[] $customerPasswordWhiteList
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validate(
        string $password,
        array $customerPasswordPolicyConfig,
        array $customerPasswordWhiteList
    ): CustomerResponseTransfer {
        $customerResponseTransfer = (new CustomerResponseTransfer())
            ->setIsSuccess(true);
        if (in_array($password, $customerPasswordWhiteList, true)) {
            return $customerResponseTransfer;
        }

        foreach ($this->customerPasswordPolicyPlugins as $customerPasswordPolicyPlugin) {
            $passwordPolicyPluginName = $customerPasswordPolicyPlugin->getName();
            $passwordPolicyPluginConfig = isset($customerPasswordPolicyConfig[$passwordPolicyPluginName]) ?
                $customerPasswordPolicyConfig[$customerPasswordPolicyPlugin->getName()] :
                null;
            if (!$passwordPolicyPluginConfig) {
                return $customerResponseTransfer;
            }

            $customerPasswordPolicyPlugin->validate(
                $password,
                $customerResponseTransfer,
                $passwordPolicyPluginConfig
            );
        }

        return $customerResponseTransfer;
    }
}
