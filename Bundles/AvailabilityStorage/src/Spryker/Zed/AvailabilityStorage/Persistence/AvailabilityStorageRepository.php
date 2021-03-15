<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Persistence;

use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;

/**
 * @method \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStoragePersistenceFactory getFactory()
 */
class AvailabilityStorageRepository extends AbstractRepository implements AvailabilityStorageRepositoryInterface
{
    use InstancePoolingTrait;

    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function getAvailabilityAbstractIdsByProductAbstractIds(array $productAbstractIds): array
    {
        $isInstancePoolingEnabled = $this->isInstancePoolingEnabled();
        $this->disableInstancePooling();

        $availabilityAbstractIds = $this->getFactory()->getProductAbstractPropelQuery()
            ->select([SpyAvailabilityAbstractEntityTransfer::ID_AVAILABILITY_ABSTRACT])
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->addJoin(SpyProductAbstractTableMap::COL_SKU, SpyAvailabilityAbstractTableMap::COL_ABSTRACT_SKU, Criteria::INNER_JOIN)
            ->withColumn(SpyAvailabilityAbstractTableMap::COL_ID_AVAILABILITY_ABSTRACT, SpyAvailabilityAbstractEntityTransfer::ID_AVAILABILITY_ABSTRACT)
            ->find()
            ->getData();

        if ($isInstancePoolingEnabled) {
            $this->enableInstancePooling();
        }

        return $availabilityAbstractIds;
    }
}
