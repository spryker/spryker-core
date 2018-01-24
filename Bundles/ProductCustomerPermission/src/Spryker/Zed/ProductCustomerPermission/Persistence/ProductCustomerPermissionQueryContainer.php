<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Persistence;

use Orm\Zed\ProductCustomerPermission\Persistence\Base\SpyProductCustomerPermissionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionPersistenceFactory getFactory()
 */
class ProductCustomerPermissionQueryContainer extends AbstractQueryContainer implements ProductCustomerPermissionQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermissionQuery
     */
    public function queryProductCustomerPermission()
    {
        return SpyProductCustomerPermissionQuery::create();
    }
}
