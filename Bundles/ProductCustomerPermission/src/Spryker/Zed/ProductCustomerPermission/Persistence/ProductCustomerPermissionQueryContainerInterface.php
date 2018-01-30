<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

/**
 * @method \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionPersistenceFactory getFactory()
 */
interface ProductCustomerPermissionQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param int $customerId
     *
     * @return \Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermissionQuery
     */
    public function queryProductCustomerPermissionByCustomer(int $customerId);

    /**
     * @api
     *
     * @param int $customerId
     * @param array $productIds
     *
     * @return \Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermissionQuery
     */
    public function queryProductCustomerPermissionByCustomerAndProducts(int $customerId, array $productIds);
}
