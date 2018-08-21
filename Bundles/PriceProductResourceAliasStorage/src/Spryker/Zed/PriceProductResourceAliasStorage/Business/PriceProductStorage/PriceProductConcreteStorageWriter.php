<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Business\PriceProductStorage;

use Spryker\Zed\PriceProductResourceAliasStorage\Persistence\PriceProductResourceAliasStorageEntityManagerInterface;
use Spryker\Zed\PriceProductResourceAliasStorage\Persistence\PriceProductResourceAliasStorageRepositoryInterface;

class PriceProductConcreteStorageWriter implements PriceProductConcreteStorageWriterInterface
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
     * @param array $productConcreteIds
     *
     * @return void
     */
    public function updatePriceProductConcreteStorageSkusByProductConcreteIds(array $productConcreteIds): void
    {
        $priceProductConcreteStorageEntities = $this->priceProductResourceAliasStorageRepository
            ->getPriceProductConcreteStorageEntities($productConcreteIds);
        $productConcreteData = $this->priceProductResourceAliasStorageRepository
            ->getProductConcreteSkuList($productConcreteIds);

        $this->updatePriceProductConcreteStorageSkus($priceProductConcreteStorageEntities, $productConcreteData);
    }

    /**
     * @param array $priceProductStoreIds
     *
     * @return void
     */
    public function updatePriceProductConcreteStorageSkusByStoreIds(array $priceProductStoreIds): void
    {
        $productConcreteData = $this->priceProductResourceAliasStorageRepository
            ->getProductConcreteSkuListByPriceProductStoreIds($priceProductStoreIds);
        $priceProductConcreteStorageEntities = $this->priceProductResourceAliasStorageRepository
            ->getPriceProductConcreteStorageEntities(array_keys($productConcreteData));

        $this->updatePriceProductConcreteStorageSkus($priceProductConcreteStorageEntities, $productConcreteData);
    }

    /**
     * @param array $priceProductConcreteStorageEntities
     * @param array $productConcreteData
     *
     * @return void
     */
    protected function updatePriceProductConcreteStorageSkus(array $priceProductConcreteStorageEntities, array $productConcreteData): void
    {
        foreach ($priceProductConcreteStorageEntities as $priceProductConcreteStorageEntity) {
            $productConcreteSku = $productConcreteData[$priceProductConcreteStorageEntity->getFkProduct()][static::KEY_SKU];
            if ($productConcreteSku === $priceProductConcreteStorageEntity->getSku()) {
                continue;
            }
            if ($priceProductConcreteStorageEntity->getSku()) {
                $priceProductConcreteStorageEntity->syncUnpublishedMessageForMappingResource();
            }

            $priceProductConcreteStorageEntity->setSku($productConcreteSku);
            $this->priceProductResourceAliasStorageEntityManager->savePriceProductConcreteStorageEntity($priceProductConcreteStorageEntity);
        }
    }
}
