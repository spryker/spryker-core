<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher;

use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;
use Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface;
use Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig;

class ProductAlternativePublisher implements ProductAlternativePublisherInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface
     */
    protected $productAlternativeStorageRepository;

    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface
     */
    protected $productAlternativeStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig
     */
    protected $productAlternativeStorageConfig;

    /**
     * @param \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface $productAlternativeStorageRepository
     * @param \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface $productAlternativeStorageEntityManager
     * @param \Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig $productAlternativeStorageConfig
     */
    public function __construct(
        ProductAlternativeStorageRepositoryInterface $productAlternativeStorageRepository,
        ProductAlternativeStorageEntityManagerInterface $productAlternativeStorageEntityManager,
        ProductAlternativeStorageConfig $productAlternativeStorageConfig
    ) {
        $this->productAlternativeStorageRepository = $productAlternativeStorageRepository;
        $this->productAlternativeStorageEntityManager = $productAlternativeStorageEntityManager;
        $this->productAlternativeStorageConfig = $productAlternativeStorageConfig;
    }

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publish(array $productIds): void
    {
        $indexedProductAlternativeEntityTransfers = $this->findIndexedProductAlternativeStorageEntities($productIds);

        foreach ($productIds as $idProduct) {
            if (!isset($indexedProductAlternativeEntityTransfers[$idProduct])) {
                $indexedProductAlternativeEntityTransfers[$idProduct] = new SpyProductAlternativeStorage();
            }

            $this->saveStorageEntity($indexedProductAlternativeEntityTransfers[$idProduct], $idProduct);
        }
    }

    /**
     * @param array<int> $productIds
     *
     * @return array<\Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage>
     */
    protected function findIndexedProductAlternativeStorageEntities(array $productIds): array
    {
        $productAlternativeStorageEntities = $this->productAlternativeStorageRepository
            ->findProductAlternativeStorageEntities($productIds);

        $indexedProductAlternativeStorageEntities = [];
        foreach ($productAlternativeStorageEntities as $productAlternativeStorageEntity) {
            $indexedProductAlternativeStorageEntities[$productAlternativeStorageEntity->getFkProduct()] = $productAlternativeStorageEntity;
        }

        return $indexedProductAlternativeStorageEntities;
    }

    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage $productAlternativeStorageEntity
     * @param int $productId
     *
     * @return void
     */
    protected function saveStorageEntity(
        SpyProductAlternativeStorage $productAlternativeStorageEntity,
        int $productId
    ): void {
        $abstractAlternatives = $this->productAlternativeStorageRepository->findAbstractAlternativesIdsByConcreteProductId($productId);
        $concreteAlternatives = $this->productAlternativeStorageRepository->findConcreteAlternativesIdsByConcreteProductId($productId);
        $sku = $this->productAlternativeStorageRepository->findProductSkuById($productId);

        if (!count($concreteAlternatives) && !count($abstractAlternatives)) {
            $this->productAlternativeStorageEntityManager->deleteProductAlternativeStorageEntity($productAlternativeStorageEntity);

            return;
        }

        $productAlternativeStorageEntity
            ->setFkProduct($productId)
            ->setSku($sku)
            ->setData($this->getStorageEntityData($abstractAlternatives, $concreteAlternatives));

        $productAlternativeStorageEntity->setIsSendingToQueue($this->productAlternativeStorageConfig->isSendingToQueue());
        $this->productAlternativeStorageEntityManager->saveProductAlternativeStorageEntity($productAlternativeStorageEntity);
    }

    /**
     * @param array<int> $alternativeAbstractIds
     * @param array<int> $alternativeConcreteIds
     *
     * @return array
     */
    protected function getStorageEntityData(array $alternativeAbstractIds, array $alternativeConcreteIds): array
    {
        return (new ProductAlternativeStorageTransfer())
            ->setProductAbstractIds($alternativeAbstractIds)
            ->setProductConcreteIds($alternativeConcreteIds)
            ->toArray();
    }
}
