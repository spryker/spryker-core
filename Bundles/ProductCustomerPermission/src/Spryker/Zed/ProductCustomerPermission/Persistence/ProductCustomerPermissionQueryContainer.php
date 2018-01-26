<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionPersistenceFactory getFactory()
 */
class ProductCustomerPermissionQueryContainer extends AbstractQueryContainer implements ProductCustomerPermissionQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $customerId
     *
     * @return \Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermissionQuery
     */
    public function queryProductCustomerPermissionByCustomer(int $customerId)
    {
        return $this->getFactory()->createProductCustomerPermissionQuery()
            ->filterByFkCustomer($customerId);
    }

    /**
     * @param int $customerId
     * @param array $productIds
     *
     * @return $this|\Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermissionQuery
     */
    public function queryProductCustomerPermissionByCustomerAndProducts(int $customerId, array $productIds)
    {
        return $this->getFactory()->createProductCustomerPermissionQuery()
            ->filterByFkCustomer($customerId)
            ->filterByFkProductAbstract_In($productIds);
    }

    /**
     * @param array $entityIds
     *
     * @return \Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermissionQuery
     */
    public function queryProductCustomerPermissionByIds(array $entityIds)
    {
        return $this->getFactory()->createProductCustomerPermissionQuery()
            ->filterByIdProductCustomerPermission_In($entityIds);
    }
}
