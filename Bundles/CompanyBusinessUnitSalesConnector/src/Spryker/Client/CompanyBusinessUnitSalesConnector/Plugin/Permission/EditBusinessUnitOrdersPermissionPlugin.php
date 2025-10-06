<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyBusinessUnitSalesConnector\Plugin\Permission;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

/**
 * Use this plugin for checking if customer is allowed to edit orders from the same business unit.
 */
class EditBusinessUnitOrdersPermissionPlugin extends AbstractPlugin implements PermissionPluginInterface
{
    /**
     * @var string
     */
    protected const KEY = 'EditBusinessUnitOrdersPermissionPlugin';

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
