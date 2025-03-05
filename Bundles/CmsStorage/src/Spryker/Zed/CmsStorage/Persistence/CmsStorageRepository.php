<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\CmsStorage\Persistence;

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
}
