<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Permission;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * Use this plugin for checking if customer is allowed to edit orders from same business unit.
 *
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorConfig getConfig()
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacadeInterface getFacade()
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
