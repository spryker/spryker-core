<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\CategoryStorage\Persistence\Map\SpyCategoryNodeStorageTableMap;
use Orm\Zed\CategoryStorage\Persistence\Map\SpyCategoryTreeStorageTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStoragePersistenceFactory getFactory()
 */
class CategoryStorageRepository extends AbstractRepository implements CategoryStorageRepositoryInterface
{
    /**
     * @param int $offset
     * @param int $limit
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findCategoryNodeStorageSynchronizationDataTransfersByCategoryNodeIds(int $offset, int $limit, array $categoryNodeIds): array
    {
        $filterTransfer = $this->createFilterTransfer($offset, $limit, SpyCategoryNodeStorageTableMap::COL_ID_CATEGORY_NODE_STORAGE);
        $query = $this->getFactory()->createSpyCategoryNodeStorageQuery();

        if ($categoryNodeIds) {
            $query->filterByFkCategoryNode_In($categoryNodeIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param int[] $categoryTreeStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findCategoryTreeStorageSynchronizationDataTransfersByCategoryTreeStorageIds(int $offset, int $limit, array $categoryTreeStorageIds): array
    {
        $filterTransfer = $this->createFilterTransfer($offset, $limit, SpyCategoryTreeStorageTableMap::COL_ID_CATEGORY_TREE_STORAGE);
        $query = $this->getFactory()->createSpyCategoryTreeStorageQuery();

        if ($categoryTreeStorageIds) {
            $query->filterByIdCategoryTreeStorage_In($categoryTreeStorageIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param string $orderByColumnName
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit, string $orderByColumnName): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOrderBy($orderByColumnName)
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
