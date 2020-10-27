<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer;

interface CustomerPasswordPolicyManagerInterface
{
    /**
     * @param \Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerPasswordPolicyPluginInterface[] $plugins
     *
     * @return void
     */
    public function setPlugins(array $plugins): void;

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
    ): CustomerPasswordPolicyResultTransfer;
}
