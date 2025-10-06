<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Communication\Plugin\Permission;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * Use this plugin for checking if customer is allowed to edit orders from same company.
 *
 * @method \Spryker\Zed\CompanySalesConnector\CompanySalesConnectorConfig getConfig()
 * @method \Spryker\Zed\CompanySalesConnector\Business\CompanySalesConnectorFacadeInterface getFacade()
 */
class EditCompanyOrdersPermissionPlugin extends AbstractPlugin implements PermissionPluginInterface
{
    /**
     * @var string
     */
    protected const KEY = 'EditCompanyOrdersPermissionPlugin';

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
