<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission\Plugin\Customer;

use Spryker\Client\CustomerExtension\Dependency\Plugin\CustomerSecuredPatternRulePluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionFactory getFactory()
 * @method \Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionConfig getConfig()
 */
class CustomerAccessSecuredPatternRulePlugin extends AbstractPlugin implements CustomerSecuredPatternRulePluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if customer is logged in.
     * - Returns false if customer is logged out.
     *
     * @api
     *
     * @return bool
     */
    public function isApplicable(): bool
    {
        return !$this->getFactory()
            ->getCustomerClient()
            ->isLoggedIn();
    }

    /**
     * {@inheritDoc}
     * - Modifies secured pattern by configured customer access for unauthenticated users.
     *
     * @api
     *
     * @param string $securedPattern
     *
     * @return string
     */
    public function execute(string $securedPattern): string
    {
        $unauthenticatedCustomerAccess = $this->getFactory()->getCustomerAccessStorageClient()->getUnauthenticatedCustomerAccess();

        return $this->getFactory()
            ->createCustomerAccess()
            ->applyCustomerAccessOnCustomerSecuredPattern($unauthenticatedCustomerAccess, $securedPattern);
    }
}
