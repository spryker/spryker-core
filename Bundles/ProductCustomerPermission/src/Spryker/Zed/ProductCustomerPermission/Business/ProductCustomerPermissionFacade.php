<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCustomerPermission\Business\ProductCustomerPermissionBusinessFactory getFactory()
 */
class ProductCustomerPermissionFacade extends AbstractFacade implements ProductCustomerPermissionFacadeInterface
{
    /**
     * @api
     *
     * @param int $customerId
     * @param int $productId
     *
     * @return void
     */
    public function saveCustomerProductPermission(int $customerId, int $productId)
    {
        $this->getFactory()->createCustomerProductPermissionSaver($customerId)->saveProductPermission($productId);
    }

    /**
     * @api
     *
     * @param int $customerId
     * @param array $productIds
     *
     * @return void
     */
    public function saveCustomerProductPermissions(int $customerId, array $productIds)
    {
        $this->getFactory()->createCustomerProductPermissionSaver($customerId)->saveProductPermissions($productIds);
    }
}
