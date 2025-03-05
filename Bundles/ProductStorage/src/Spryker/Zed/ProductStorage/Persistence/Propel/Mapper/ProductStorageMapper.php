<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SitemapUrlTransfer;
use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage;
use Propel\Runtime\Collection\Collection;

class ProductStorageMapper
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
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage> $productAbstractStorageEntities
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function mapProductAbstractStorageEntitiesToSitemapUrlTransfers(Collection $productAbstractStorageEntities): array
    {
        $sitemapUrlTransfers = [];

        foreach ($productAbstractStorageEntities as $productAbstractStorageEntity) {
            $productAbstractStorageData = $productAbstractStorageEntity->getData();

            if (!isset($productAbstractStorageData[static::COLUMN_URL])) {
                continue;
            }

            $sitemapUrlTransfers[] = $this->mapProductAbstractStorageEntityToSitemapUrlTransfer($productAbstractStorageEntity);
        }

        return $sitemapUrlTransfers;
    }

    /**
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage $productAbstractStorageEntity
     *
     * @return \Generated\Shared\Transfer\SitemapUrlTransfer
     */
    protected function mapProductAbstractStorageEntityToSitemapUrlTransfer(SpyProductAbstractStorage $productAbstractStorageEntity): SitemapUrlTransfer
    {
        return (new SitemapUrlTransfer())
            ->setUrl($productAbstractStorageEntity->getData()[static::COLUMN_URL])
            ->setUpdatedAt($productAbstractStorageEntity->getUpdatedAt(static::DATE_FORMAT))
            ->setLanguageCode($productAbstractStorageEntity->getLocale())
            ->setStoreName($productAbstractStorageEntity->getStore())
            ->setIdEntity($productAbstractStorageEntity->getFkProductAbstract());
    }
}
