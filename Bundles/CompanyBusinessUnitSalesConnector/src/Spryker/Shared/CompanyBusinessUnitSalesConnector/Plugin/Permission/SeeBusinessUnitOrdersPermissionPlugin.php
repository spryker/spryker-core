<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CompanyBusinessUnitSalesConnector\Plugin\Permission;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

/**
 * For Zed & Client PermissionDependencyProvider::getPermissionPlugins() registration
 */
class SeeBusinessUnitOrdersPermissionPlugin implements PermissionPluginInterface
{
    protected const KEY = 'SeeBusinessUnitOrdersPermissionPlugin';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}
