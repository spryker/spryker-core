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
     * @param int $idCustomer
     *
     * @return \Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermissionQuery
     */
    public function queryProductCustomerPermissionByCustomer(int $idCustomer);

    /**
     * @api
     *
     * @param int $idCustomer
     * @param int[] $idProductAbstracts
     *
     * @return \Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermissionQuery
     */
    public function queryProductCustomerPermissionByCustomerAndProducts(int $idCustomer, array $idProductAbstracts);
}
