<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\CategoryPageSearch\Persistence\Map\SpyCategoryNodePageSearchTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchPersistenceFactory getFactory()
 */
class CategoryPageSearchRepository extends AbstractRepository implements CategoryPageSearchRepositoryInterface
{
    /**
     * @param int $offset
     * @param int $limit
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findSynchronizationDataTransfersByIds(int $offset, int $limit, array $categoryNodeIds): array
    {
        $filterTransfer = $this->createFilterTransfer($offset, $limit, SpyCategoryNodePageSearchTableMap::COL_ID_CATEGORY_NODE_PAGE_SEARCH);
        $query = $this->getFactory()->createSpyCategoryNodePageSearchQuery();

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
