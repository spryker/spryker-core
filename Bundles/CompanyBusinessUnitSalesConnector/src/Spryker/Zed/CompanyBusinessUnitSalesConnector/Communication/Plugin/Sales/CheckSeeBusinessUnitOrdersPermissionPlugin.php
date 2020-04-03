<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Sales;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\CustomerOrderPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorConfig getConfig()
 */
class CheckSeeBusinessUnitOrdersPermissionPlugin extends AbstractPlugin implements CustomerOrderPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks SeeBusinessUnitOrdersPermissionPlugin for customer who retrieve customer order.
     * - Returns true if customer has SeeBusinessUnitOrdersPermissionPlugin, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function check(OrderTransfer $orderTransfer, CustomerTransfer $customerTransfer): bool
    {
        return $this->getFacade()->checkSeeBusinessUnitOrdersPermission($orderTransfer, $customerTransfer);
    }
}
