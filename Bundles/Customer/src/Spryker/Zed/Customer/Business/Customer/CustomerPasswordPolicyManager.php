<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer;

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
     * @return \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function validate(
        string $password,
        array $customerPasswordPolicyConfig,
        array $customerPasswordWhiteList
    ): CustomerPasswordPolicyResultTransfer {
        $result = new CustomerPasswordPolicyResultTransfer();
        $result->setIsSuccessful(true);

        if (!in_array($password, $customerPasswordWhiteList)) {
            foreach ($this->customerPasswordPolicyPlugins as $customerPasswordPolicyPlugin) {
                $passwordPolicyPluginName = $customerPasswordPolicyPlugin->getName();
                $passwordPolicyPluginConfig = isset($customerPasswordPolicyConfig[$passwordPolicyPluginName]) ?
                    $customerPasswordPolicyConfig[$customerPasswordPolicyPlugin->getName()] :
                    null;
                if ($passwordPolicyPluginConfig) {
                    $customerPasswordPolicyPlugin->validate(
                        $password,
                        $result,
                        $passwordPolicyPluginConfig
                    );
                }
            }
        }

        return $result;
    }
}
