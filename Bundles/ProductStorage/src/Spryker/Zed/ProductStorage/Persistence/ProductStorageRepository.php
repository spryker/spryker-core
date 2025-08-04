<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Persistence;

use Generator;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStoragePersistenceFactory getFactory()
 */
class ProductStorageRepository extends AbstractRepository implements ProductStorageRepositoryInterface
{
    /**
     * @var string
     */
    protected const URL_RELATION = 'SpyProductAbstract.SpyUrl';

    /**
     * @var string
     */
    protected const COLUMN_URL = 'url';

    /**
     * @var string
     */
    protected const ENTITY_URL = 'SpyUrl';

    /**
     * @var string
     */
    protected const COL_PRODUCT_COUNT = 'productCount';

    /**
     * @var string
     */
    protected const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @var int
     */
    protected const SITEMAP_QUERY_LIMIT = 1000;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int|string|bool>
     */
    public function getProductAbstractsByIds(array $productAbstractIds): array
    {
        return $this->getFactory()->getProductAbstractLocalizedAttributesPropelQuery()
            ->joinWithLocale()
            ->joinWithSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->joinWithSpyProductAbstractStore()
                ->useSpyProductAbstractStoreQuery()
                    ->joinWithSpyStore()
                ->endUse()
            ->endUse()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY)
            ->join(static::URL_RELATION)
            ->addJoinCondition(static::ENTITY_URL, sprintf('%s = %s', SpyUrlTableMap::COL_FK_LOCALE, SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE))
            ->withColumn(SpyUrlTableMap::COL_URL, static::COLUMN_URL)
            ->find()
            ->getData();
    }

    /**
     * @param array<int> $idProductAbstracts
     *
     * @return array<array<string, int>>
     */
    public function getProductConcretesCountByIdProductAbstracts(array $idProductAbstracts): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $productConcretesCollection */
        $productConcretesCollection = $this->getFactory()->getProductPropelQuery()
            ->filterByFkProductAbstract_In($idProductAbstracts)
            ->filterByIsActive(true)
            ->withColumn('COUNT(*)', static::COL_PRODUCT_COUNT)
            ->select([static::COL_FK_PRODUCT_ABSTRACT, static::COL_PRODUCT_COUNT])
            ->groupByFkProductAbstract()
            ->find();

        return $productConcretesCollection->toArray();
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface::getSitemapGeneratorUrls()} instead.
     *
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function getSitemapUrls(string $storeName): array
    {
        $offset = 0;
        $productAbstractStorageQuery = $this->getFactory()
            ->createSpyProductAbstractStorageQuery()
            ->filterByStore($storeName)
            ->orderByIdProductAbstractStorage()
            ->limit(static::SITEMAP_QUERY_LIMIT)
            ->offset($offset);
        $sitemapUrlTransfers = [];
        $productStorageMapper = $this->getFactory()->createProductStorageMapper();

        do {
            $offset += static::SITEMAP_QUERY_LIMIT;
            $productAbstractStorageEntities = $productAbstractStorageQuery->find();
            $sitemapUrlTransfers[] = $productStorageMapper->mapProductAbstractStorageEntitiesToSitemapUrlTransfers($productAbstractStorageEntities);
            $productAbstractStorageQuery->offset($offset);
        } while ($productAbstractStorageEntities->count() === static::SITEMAP_QUERY_LIMIT);

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
        $productAbstractStorageQuery = $this->getFactory()
            ->createSpyProductAbstractStorageQuery()
            ->filterByStore($storeName)
            ->orderByIdProductAbstractStorage()
            ->limit($limit)
            ->offset($offset);
        $sitemapUrlTransfers = [];
        $productStorageMapper = $this->getFactory()->createProductStorageMapper();

        do {
            $offset += $limit;
            $productAbstractStorageEntities = $productAbstractStorageQuery->find();

            yield $productStorageMapper->mapProductAbstractStorageEntitiesToSitemapUrlTransfers($productAbstractStorageEntities);

            $productAbstractStorageQuery->offset($offset);
        } while ($productAbstractStorageEntities->count() === $limit);

        yield [];
    }

    /**
     * @module Product
     *
     * @param list<int> $productIds
     *
     * @return list<int>
     */
    public function getProductAbstractIdsByProductIds(array $productIds): array
    {
        return $this->getFactory()
            ->getProductPropelQuery()
            ->filterByIdProduct_In($productIds)
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->distinct()
            ->find()
            ->getData();
    }
}
