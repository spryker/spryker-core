<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Persistence;

use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Formatter\ObjectFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStoragePersistenceFactory getFactory()
 */
class MerchantStorageRepository extends AbstractRepository implements MerchantStorageRepositoryInterface
{
    /**
     * @var int
     */
    protected const SITEMAP_QUERY_LIMIT = 1000;

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage>
     */
    public function getFilteredMerchantStorageEntityTransfers(
        MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer
    ): ObjectCollection {
        $merchantStorageQuery = $this->getFactory()->createMerchantStorageQuery();

        if ($merchantStorageCriteriaTransfer->getMerchantIds()) {
            $merchantStorageQuery->filterByIdMerchant_In($merchantStorageCriteriaTransfer->getMerchantIds());
        }

        if ($merchantStorageCriteriaTransfer->getFilter()) {
            $merchantStorageQuery = $this->buildQueryFromCriteria(
                $merchantStorageQuery,
                $merchantStorageCriteriaTransfer->getFilter(),
            )->setFormatter(ObjectFormatter::class);
        }

        return $merchantStorageQuery->find();
    }

    /**
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function getSitemapUrls(string $storeName): array
    {
        $offset = 0;
        $merchantStorageQuery = $this->getFactory()
            ->createMerchantStorageQuery()
            ->filterByStore($storeName)
            ->orderByIdMerchantStorage()
            ->limit(static::SITEMAP_QUERY_LIMIT)
            ->offset($offset);
        $sitemapUrlTransfers = [];
        $merchantStorageMapper = $this->getFactory()->createMerchantStorageMapper();

        do {
            $offset += static::SITEMAP_QUERY_LIMIT;
            $merchantStorageEntities = $merchantStorageQuery->find();
            $sitemapUrlTransfers[] = $merchantStorageMapper->mapMerchantStorageEntitiesToSitemapUrlTransfers($merchantStorageEntities);
            $merchantStorageQuery->offset($offset);
        } while ($merchantStorageEntities->count() === static::SITEMAP_QUERY_LIMIT);

        return array_merge(...$sitemapUrlTransfers);
    }
}
