<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generator;
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
     * @var int
     */
    protected const SITEMAP_QUERY_LIMIT = 1000;

    /**
     * @param int $offset
     * @param int $limit
     * @param array<int> $categoryNodeIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getCategoryNodeStorageSynchronizationDataTransfersByCategoryNodeIds(int $offset, int $limit, array $categoryNodeIds): array
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
     * @param array<int> $categoryTreeStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getCategoryTreeStorageSynchronizationDataTransfersByCategoryTreeStorageIds(int $offset, int $limit, array $categoryTreeStorageIds): array
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
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepository::getSitemapGeneratorUrls()} instead.
     *
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function getSitemapUrls(string $storeName): array
    {
        $offset = 0;
        $categoryNodeStorageQuery = $this->getFactory()
            ->createSpyCategoryNodeStorageQuery()
            ->filterByStore($storeName)
            ->orderByIdCategoryNodeStorage()
            ->limit(static::SITEMAP_QUERY_LIMIT)
            ->offset($offset);
        $sitemapUrlTransfers = [];
        $categoryNodeStorageMapper = $this->getFactory()->createCategoryNodeStorageMapper();

        do {
            $offset += static::SITEMAP_QUERY_LIMIT;
            $categoryNodeStorageEntities = $categoryNodeStorageQuery->find();
            $sitemapUrlTransfers[] = $categoryNodeStorageMapper->mapCategoryNodeStorageEntitiesToSitemapUrlTransfers($categoryNodeStorageEntities);
            $categoryNodeStorageQuery->offset($offset);
        } while ($categoryNodeStorageEntities->count() === static::SITEMAP_QUERY_LIMIT);

        return array_merge(...$sitemapUrlTransfers);
    }

    /**
     * @param string $storeName
     * @param int $limit
     *
     * @return \Generator
     */
    public function getSitemapGeneratorUrls(string $storeName, int $limit): Generator
    {
        $offset = 0;
        $categoryNodeStorageQuery = $this->getFactory()
            ->createSpyCategoryNodeStorageQuery()
            ->filterByStore($storeName)
            ->orderByIdCategoryNodeStorage()
            ->limit($limit)
            ->offset($offset);
        $sitemapUrlTransfers = [];
        $categoryNodeStorageMapper = $this->getFactory()->createCategoryNodeStorageMapper();

        do {
            $offset += $limit;
            $categoryNodeStorageEntities = $categoryNodeStorageQuery->find();

            yield $categoryNodeStorageMapper->mapCategoryNodeStorageEntitiesToSitemapUrlTransfers($categoryNodeStorageEntities);

            $categoryNodeStorageQuery->offset($offset);
        } while ($categoryNodeStorageEntities->count() === $limit);

        yield [];
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
