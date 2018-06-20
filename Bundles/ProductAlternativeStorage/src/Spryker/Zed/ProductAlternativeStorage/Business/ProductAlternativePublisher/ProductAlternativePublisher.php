<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher;

use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;
use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface;

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
     * @param \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface $productAlternativeStorageRepository
     * @param \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface $productAlternativeStorageEntityManager
     */
    public function __construct(
        ProductAlternativeStorageRepositoryInterface $productAlternativeStorageRepository,
        ProductAlternativeStorageEntityManagerInterface $productAlternativeStorageEntityManager
    ) {
        $this->productAlternativeStorageRepository = $productAlternativeStorageRepository;
        $this->productAlternativeStorageEntityManager = $productAlternativeStorageEntityManager;
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publish(array $productIds): void
    {
        $mappedProductAlternativeEntityTransfers =
            $this->findMappedProductAlternativeStorageEntities($productIds);

        foreach ($productIds as $idProduct) {
            if (!isset($mappedProductAlternativeEntityTransfers[$idProduct])) {
                $mappedProductAlternativeEntityTransfers[$idProduct] = new SpyProductAlternativeStorageEntityTransfer();
            }

            $this->saveStorageEntity($mappedProductAlternativeEntityTransfers[$idProduct], $idProduct);
        }
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer[]
     */
    protected function findMappedProductAlternativeStorageEntities(array $productIds): array
    {
        $productAlternativeStorageEntityTransfers = $this->productAlternativeStorageRepository
            ->findProductAlternativeStorageEntities($productIds);

        $mappedProductAlternativeStorageEntityTransfers = [];
        foreach ($productAlternativeStorageEntityTransfers as $productAlternativeStorageEntityTransfer) {
            $mappedProductAlternativeStorageEntityTransfers[$productAlternativeStorageEntityTransfer->getFkProduct()] = $productAlternativeStorageEntityTransfer;
        }

        return $mappedProductAlternativeStorageEntityTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntity
     * @param int $productId
     *
     * @return void
     */
    protected function saveStorageEntity(
        SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntity,
        int $productId
    ): void {

        $abstractAlternatives = $this->productAlternativeStorageRepository->findAbstractAlternativesIdsByConcreteProductId($productId);
        $concreteAlternatives = $this->productAlternativeStorageRepository->findConcreteAlternativesIdsByConcreteProductId($productId);
        $sku = $this->productAlternativeStorageRepository->findProductSkuById($productId);

        $productAlternativeStorageEntity
            ->setFkProduct($productId)
            ->setSku($sku)
            ->setData($this->getStorageEntityData($abstractAlternatives, $concreteAlternatives));

        $this->productAlternativeStorageEntityManager->saveProductAlternativeStorageEntity($productAlternativeStorageEntity);
    }

    /**
     * @param int[] $alternativeAbstractIds
     * @param int[] $alternativeConcreteIds
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
