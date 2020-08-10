<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTypeTableMap;
use Orm\Zed\ProductRelationStorage\Persistence\Map\SpyProductAbstractRelationStorageTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStoragePersistenceFactory getFactory()
 */
class ProductRelationStorageRepository extends AbstractRepository implements ProductRelationStorageRepositoryInterface
{
    /**
     * @param int[] $relationIds
     *
     * @return array
     */
    public function getProductRelationsWithProductAbstractByIdRelationIn(array $relationIds): array
    {
        return $this->getFactory()
            ->getProductRelationProductAbstractQuery()
            ->filterByFkProductRelation_In($relationIds)
            ->joinWithSpyProductRelation()
            ->useSpyProductRelationQuery()
                ->joinWithSpyProductRelationType()
            ->endUse()
            ->joinWithSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->joinWithSpyProductAbstractLocalizedAttributes()
            ->endUse()
            ->select([
                SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE,
                SpyProductRelationProductAbstractTableMap::COL_ORDER,
                SpyProductRelationTypeTableMap::COL_KEY,
            ])
            ->find()
            ->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findProductRelationStorageDataTransferByIds(FilterTransfer $filterTransfer, array $ids): array
    {
        $filterTransfer->setOrderBy(SpyProductAbstractRelationStorageTableMap::COL_ID_PRODUCT_ABSTRACT_RELATION_STORAGE);

        $query = $this->getFactory()->createSpyProductAbstractRelationStorageQuery();

        if ($ids !== []) {
            $query->filterByIdProductAbstractRelationStorage_In($ids);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string[]
     */
    public function getStoresByIdProductAbstractFromStorage(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createSpyProductAbstractRelationStorageQuery()
            ->select([
                SpyProductAbstractRelationStorageTableMap::COL_STORE,
            ])
            ->filterByFkProductAbstract($idProductAbstract)
            ->find()
            ->getData();
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOrderBy(SpyProductAbstractRelationStorageTableMap::COL_ID_PRODUCT_ABSTRACT_RELATION_STORAGE)
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
