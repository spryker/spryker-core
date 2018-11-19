<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorageQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductPackagingUnitStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array productAbstractIds
     *
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorageQuery
     */
    public function queryProductAbstractPackagingStorageEntitiesByProductAbstractIds(array $productAbstractIds): AvailabilityStoragePersistenceFactory;
}