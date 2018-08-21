<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Business\PriceProductStorage;

use Spryker\Zed\PriceProductResourceAliasStorage\Persistence\PriceProductResourceAliasStorageEntityManagerInterface;
use Spryker\Zed\PriceProductResourceAliasStorage\Persistence\PriceProductResourceAliasStorageRepositoryInterface;

class PriceProductAbstractStorageWriter implements PriceProductAbstractStorageWriterInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Zed\PriceProductResourceAliasStorage\Persistence\PriceProductResourceAliasStorageRepositoryInterface
     */
    protected $priceProductResourceAliasStorageRepository;

    /**
     * @var \Spryker\Zed\PriceProductResourceAliasStorage\Persistence\PriceProductResourceAliasStorageEntityManagerInterface
     */
    protected $priceProductResourceAliasStorageEntityManager;

    /**
     * PriceProductAbstractStorageWriter constructor.
     *
     * @param \Spryker\Zed\PriceProductResourceAliasStorage\Persistence\PriceProductResourceAliasStorageRepositoryInterface $priceProductResourceAliasStorageRepository
     * @param \Spryker\Zed\PriceProductResourceAliasStorage\Persistence\PriceProductResourceAliasStorageEntityManagerInterface $priceProductResourceAliasStorageEntityManager
     */
    public function __construct(
        PriceProductResourceAliasStorageRepositoryInterface $priceProductResourceAliasStorageRepository,
        PriceProductResourceAliasStorageEntityManagerInterface $priceProductResourceAliasStorageEntityManager
    ) {
        $this->priceProductResourceAliasStorageRepository = $priceProductResourceAliasStorageRepository;
        $this->priceProductResourceAliasStorageEntityManager = $priceProductResourceAliasStorageEntityManager;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function updatePriceProductAbstractStorageSkus(array $productAbstractIds): void
    {
        $priceProductAbstractStorageEntities = $this->priceProductResourceAliasStorageRepository
            ->getPriceProductAbstractStorageEntities($productAbstractIds);
        $productAbstractData = $this->priceProductResourceAliasStorageRepository
            ->getProductAbstractSkuList($productAbstractIds);

        foreach ($priceProductAbstractStorageEntities as $priceProductAbstractStorageEntity) {
            $productAbstractSku = $productAbstractData[$priceProductAbstractStorageEntity->getFkProductAbstract()][static::KEY_SKU];
            if ($productAbstractSku === $priceProductAbstractStorageEntity->getSku()) {
                continue;
            }
            if ($priceProductAbstractStorageEntity->getSku()) {
                $priceProductAbstractStorageEntity->syncUnpublishedMessageForMappingResource();
            }

            $priceProductAbstractStorageEntity->setSku($productAbstractSku);
            $this->priceProductResourceAliasStorageEntityManager->savePriceProductAbstractStorageEntity($priceProductAbstractStorageEntity);
        }
    }
}
