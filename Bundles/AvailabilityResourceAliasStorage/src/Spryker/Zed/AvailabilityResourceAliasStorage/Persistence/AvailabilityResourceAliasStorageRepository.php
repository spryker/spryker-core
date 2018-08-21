<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityResourceAliasStorage\Persistence;

use Orm\Zed\AvailabilityStorage\Persistence\Map\SpyAvailabilityStorageTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AvailabilityResourceAliasStorage\Persistence\AvailabilityResourceAliasStoragePersistenceFactory getFactory()
 */
class AvailabilityResourceAliasStorageRepository extends AbstractRepository implements AvailabilityResourceAliasStorageRepositoryInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @param int[] $availabilityIds
     *
     * @return \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage[]
     */
    public function getAvailabilityStorageEntities(array $availabilityIds): array
    {
        return $this->getFactory()
            ->getAvailabilityStoragePropelQuery()
            ->filterByIdAvailabilityStorage_In($availabilityIds)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $availabilityIds
     *
     * @return string[]
     */
    public function getProductAbstractSkuList(array $availabilityIds): array
    {
        $productAbstractIds = $this->getFactory()
            ->getAvailabilityStoragePropelQuery()
            ->filterByIdAvailabilityStorage_In($availabilityIds)
            ->select([SpyAvailabilityStorageTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->find()
            ->toArray();

        return $this->getFactory()
            ->getProductAbstractPropelQuery()
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductAbstractTableMap::COL_SKU => static::KEY_SKU])
            ->find()
            ->toArray(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }
}
