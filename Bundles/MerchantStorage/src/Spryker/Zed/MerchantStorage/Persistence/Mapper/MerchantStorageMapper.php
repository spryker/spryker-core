<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Persistence\Mapper;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\SitemapUrlTransfer;
use Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage;
use Propel\Runtime\Collection\Collection;

class MerchantStorageMapper
{
    /**
     * @var string
     */
    protected const COLUMN_URL_COLLECTION = 'url_collection';

    /**
     * @var string
     */
    protected const DATE_FORMAT = 'Y-m-d';

    /**
     * @var string
     */
    protected const COLUMN_URL = 'url';

    /**
     * @var string
     */
    protected const COLUMN_LOCALE_NAME = 'locale_name';

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param \Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage $merchantStorageEntity
     *
     * @return \Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage
     */
    public function mapMerchantStorageTransferToMerchantStorageEntity(
        MerchantStorageTransfer $merchantStorageTransfer,
        SpyMerchantStorage $merchantStorageEntity
    ) {
        $merchantStorageEntity->fromArray($merchantStorageTransfer->modifiedToArray(false));

        return $merchantStorageEntity;
    }

    /**
     * @param \Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage $merchantStorageEntity
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    public function mapMerchantStorageEntityToMerchantStorageTransfer(
        SpyMerchantStorage $merchantStorageEntity,
        MerchantStorageTransfer $merchantStorageTransfer
    ) {
        return $merchantStorageTransfer->fromArray($merchantStorageEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage> $merchantStorageEntities
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function mapMerchantStorageEntitiesToSitemapUrlTransfers(Collection $merchantStorageEntities): array
    {
        $sitemapUrlTransfers = [];

        foreach ($merchantStorageEntities as $merchantStorageEntity) {
            $merchantStorageData = $merchantStorageEntity->getData();

            if (!isset($merchantStorageData[static::COLUMN_URL_COLLECTION])) {
                continue;
            }

            $sitemapUrlTransfers[] = $this->mapMerchantStorageEntityToSitemapUrlTransfer($merchantStorageEntity);
        }

        return array_merge(...$sitemapUrlTransfers);
    }

    /**
     * @param \Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage $merchantStorageEntity
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    private function mapMerchantStorageEntityToSitemapUrlTransfer(SpyMerchantStorage $merchantStorageEntity): array
    {
        $sitemapUrlTransfer = [];

        foreach ($merchantStorageEntity->getData()[static::COLUMN_URL_COLLECTION] as $urlCollectionItem) {
            $sitemapUrlTransfer[] = (new SitemapUrlTransfer())
                ->setUrl($urlCollectionItem[static::COLUMN_URL])
                ->setUpdatedAt($merchantStorageEntity->getUpdatedAt(static::DATE_FORMAT))
                ->setLanguageCode($urlCollectionItem[static::COLUMN_LOCALE_NAME])
                ->setStoreName($merchantStorageEntity->getStore())
                ->setIdEntity($merchantStorageEntity->getIdMerchant());
        }

        return $sitemapUrlTransfer;
    }
}
