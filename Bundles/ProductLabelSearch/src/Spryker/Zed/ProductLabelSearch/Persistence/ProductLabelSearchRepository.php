<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Persistence;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchPersistenceFactory getFactory()
 */
class ProductLabelSearchRepository extends AbstractRepository implements ProductLabelSearchRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductLabelEntityTransfer>
     */
    public function getProductLabelsByIdProductAbstractIn(array $productAbstractIds): array
    {
        $query = $this->getFactory()
            ->getPropelProductLabelQuery()
            ->filterByIsActive(true)
            ->innerJoinWithSpyProductLabelProductAbstract()
            ->useSpyProductLabelProductAbstractQuery()
                ->filterByFkProductAbstract_In($productAbstractIds)
            ->endUse();

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @param array<int> $productLabelIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByProductLabelIds(array $productLabelIds): array
    {
        $productAbstractIds = $this->getFactory()
            ->createSpyProductLabelProductAbstractQuery()
            ->filterByFkProductLabel_In($productLabelIds)
            ->select(SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->find()
            ->getData();

        return array_unique($productAbstractIds);
    }

    /**
     * @param array<int, int> $productLabelIdsTimestampMap
     *
     * @return array<int, int>
     */
    public function getProductAbstractIdTimestampMap(array $productLabelIdsTimestampMap): array
    {
        $productAbstractIdTimestampMap = [];

        $productLabelData = $this->getFactory()
            ->createSpyProductLabelProductAbstractQuery()
            ->filterByFkProductLabel_In(array_keys($productLabelIdsTimestampMap))
            ->select([SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_LABEL])
            ->find()
            ->getData();

        foreach ($productLabelData as $productLabel) {
            $productAbstractIdTimestampMap[(int)$productLabel[SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT]] =
                $productLabelIdsTimestampMap[$productLabel[SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_LABEL]];
        }

        return $productAbstractIdTimestampMap;
    }
}
