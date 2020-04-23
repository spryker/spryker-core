<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Permission;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

class SeeBusinessUnitOrdersPermissionPlugin implements PermissionPluginInterface
{
    protected const KEY = 'SeeBusinessUnitOrdersPermissionPlugin';

    /**
     * {@inheritDoc}
     * - Checks if customer is allowed to see orders from same company business unit.
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
