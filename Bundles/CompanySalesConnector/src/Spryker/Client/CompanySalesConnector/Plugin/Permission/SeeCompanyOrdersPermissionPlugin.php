<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanySalesConnector\Plugin\Permission;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

/**
 * Use this plugin for checking if customer is allowed to see orders from same company.
 */
class SeeCompanyOrdersPermissionPlugin implements PermissionPluginInterface
{
    protected const KEY = 'SeeCompanyOrdersPermissionPlugin';

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
