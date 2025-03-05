<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SitemapUrlTransfer;
use Orm\Zed\ProductSetStorage\Persistence\SpyProductSetStorage;
use Propel\Runtime\Collection\Collection;

class ProductSetStorageMapper
{
    /**
     * @var string
     */
    protected const COLUMN_URL = 'url';

    /**
     * @var string
     */
    protected const DATE_FORMAT = 'Y-m-d';

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\ProductSetStorage\Persistence\SpyProductSetStorage> $productSetStorageEntities
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function mapProductSetStorageEntitiesToSitemapUrlTransfers(Collection $productSetStorageEntities, string $storeName): array
    {
        $sitemapUrlTransfers = [];

        foreach ($productSetStorageEntities as $productSetStorageEntity) {
            $categoryNodeStorageData = $productSetStorageEntity->getData();

            if (!isset($categoryNodeStorageData[static::COLUMN_URL])) {
                continue;
            }

            $sitemapUrlTransfers[] = $this->mapProductSetStorageEntityToSitemapUrlTransfer($productSetStorageEntity, $storeName);
        }

        return $sitemapUrlTransfers;
    }

    /**
     * @param \Orm\Zed\ProductSetStorage\Persistence\SpyProductSetStorage $productSetStorageEntity
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\SitemapUrlTransfer
     */
    protected function mapProductSetStorageEntityToSitemapUrlTransfer(SpyProductSetStorage $productSetStorageEntity, string $storeName): SitemapUrlTransfer
    {
        return (new SitemapUrlTransfer())
            ->setUrl($productSetStorageEntity->getData()[static::COLUMN_URL])
            ->setUpdatedAt($productSetStorageEntity->getUpdatedAt(static::DATE_FORMAT))
            ->setLanguageCode($productSetStorageEntity->getLocale())
            ->setStoreName($storeName)
            ->setIdEntity($productSetStorageEntity->getFkProductSet());
    }
}
