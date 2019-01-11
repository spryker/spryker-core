<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission\Plugin;

use Spryker\Client\CustomerExtension\Dependency\Plugin\CustomerSecuredPatternRulePluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\InfrastructuralPermissionPluginInterface;

/**
 * @method \Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionFactory getFactory()
 * @method \Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionConfig getConfig()
 */
class CustomerSecuredPatternRulePlugin extends AbstractPlugin implements InfrastructuralPermissionPluginInterface, CustomerSecuredPatternRulePluginInterface
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
