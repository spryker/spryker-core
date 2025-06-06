<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\ProductSetStorage\Persistence;

use Generator;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStoragePersistenceFactory getFactory()
 */
class ProductSetStorageRepository extends AbstractRepository implements ProductSetStorageRepositoryInterface
{
    /**
     * @var int
     */
    protected const SITEMAP_QUERY_LIMIT = 1000;

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageRepository::getSitemapGeneratorUrls()} instead.
     *
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function getSitemapUrls(string $storeName): array
    {
        $offset = 0;
        $productSetStorageQuery = $this->getFactory()
            ->createSpyProductSetStorageQuery()
            ->orderByIdProductSetStorage()
            ->limit(static::SITEMAP_QUERY_LIMIT)
            ->offset($offset);
        $sitemapUrlTransfers = [];
        $productSetStorageMapper = $this->getFactory()->createProductSetStorageMapper();

        do {
            $offset += static::SITEMAP_QUERY_LIMIT;
            $productSetStorageEntities = $productSetStorageQuery->find();
            $sitemapUrlTransfers[] = $productSetStorageMapper->mapProductSetStorageEntitiesToSitemapUrlTransfers($productSetStorageEntities, $storeName);
            $productSetStorageQuery->offset($offset);
        } while ($productSetStorageEntities->count() === static::SITEMAP_QUERY_LIMIT);

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
        $productSetStorageQuery = $this->getFactory()
            ->createSpyProductSetStorageQuery()
            ->orderByIdProductSetStorage()
            ->limit($limit)
            ->offset($offset);
        $sitemapUrlTransfers = [];
        $productSetStorageMapper = $this->getFactory()->createProductSetStorageMapper();

        do {
            $offset += $limit;
            $productSetStorageEntities = $productSetStorageQuery->find();

            yield $productSetStorageMapper->mapProductSetStorageEntitiesToSitemapUrlTransfers($productSetStorageEntities, $storeName);

            $productSetStorageQuery->offset($offset);
        } while ($productSetStorageEntities->count() === $limit);

        yield [];
    }
}
