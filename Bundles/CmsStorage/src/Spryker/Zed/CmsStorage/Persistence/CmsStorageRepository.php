<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\CmsStorage\Persistence;

use Generator;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CmsStorage\Persistence\CmsStoragePersistenceFactory getFactory()
 */
class CmsStorageRepository extends AbstractRepository implements CmsStorageRepositoryInterface
{
    /**
     * @var int
     */
    protected const SITEMAP_QUERY_LIMIT = 1000;

    /**
     * @deprecated Use {@link \Spryker\Zed\CmsStorage\Persistence\CmsStorageRepositoryInterface::getSitemapGeneratorUrls()} instead.
     *
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function getSitemapUrls(string $storeName): array
    {
        $offset = 0;
        $cmsPageStorageQuery = $this->getFactory()
            ->createSpyCmsStorageQuery()
            ->filterByStore($storeName)
            ->orderByIdCmsPageStorage()
            ->limit(static::SITEMAP_QUERY_LIMIT)
            ->offset($offset);
        $sitemapUrlTransfers = [];
        $cmsStorageMapper = $this->getFactory()->createCmsStorageMapper();

        do {
            $offset += static::SITEMAP_QUERY_LIMIT;
            $cmsPageStorageEntities = $cmsPageStorageQuery->find();
            $sitemapUrlTransfers[] = $cmsStorageMapper->mapCmsPageStorageEntitiesToSitemapUrlTransfers($cmsPageStorageEntities);
            $cmsPageStorageQuery->offset($offset);
        } while ($cmsPageStorageEntities->count() === static::SITEMAP_QUERY_LIMIT);

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
        $cmsPageStorageQuery = $this->getFactory()
            ->createSpyCmsStorageQuery()
            ->filterByStore($storeName)
            ->orderByIdCmsPageStorage()
            ->limit($limit)
            ->offset($offset);
        $sitemapUrlTransfers = [];
        $cmsStorageMapper = $this->getFactory()->createCmsStorageMapper();

        do {
            $offset += $limit;
            $cmsPageStorageEntities = $cmsPageStorageQuery->find();

            yield $cmsStorageMapper->mapCmsPageStorageEntitiesToSitemapUrlTransfers($cmsPageStorageEntities);

            $cmsPageStorageQuery->offset($offset);
        } while ($cmsPageStorageEntities->count() === $limit);

        yield [];
    }
}
