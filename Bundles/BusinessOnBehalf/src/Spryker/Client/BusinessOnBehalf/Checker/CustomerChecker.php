<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\BusinessOnBehalf\Checker;

use Generated\Shared\Transfer\CustomerTransfer;

class CustomerChecker implements CustomerCheckerInterface
{
    /**
     * @var \Spryker\Client\BusinessOnBehalfExtension\Dependency\Plugin\CustomerChangeAllowedCheckPluginInterface[]
     */
    protected $customerChangeAllowedCheckPlugins;

    /**
     * @param \Spryker\Client\BusinessOnBehalfExtension\Dependency\Plugin\CustomerChangeAllowedCheckPluginInterface[] $customerChangeAllowedCheckPlugins
     */
    public function __construct(array $customerChangeAllowedCheckPlugins)
    {
        $this->customerChangeAllowedCheckPlugins = $customerChangeAllowedCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function isCustomerChangeAllowed(CustomerTransfer $customerTransfer): bool
    {
        return $this->executeCustomerChangeAllowedCheckPlugins($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function executeCustomerChangeAllowedCheckPlugins(CustomerTransfer $customerTransfer): bool
    {
        foreach ($this->customerChangeAllowedCheckPlugins as $customerChangeAllowedCheckPlugin) {
            if (!$customerChangeAllowedCheckPlugin->check($customerTransfer)) {
                return false;
            }
        }

        return true;
    }
}
