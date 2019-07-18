<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductReplacementStorageTransfer;
use Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface;
use Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig;

class ProductReplacementPublisher implements ProductReplacementPublisherInterface
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
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishAbstractReplacements(array $productIds): void
    {
        $this->storeAbstractReplacements($productIds);
        $this->storeConcreteReplacementsByAbstract($productIds);
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishConcreteReplacements(array $productIds): void
    {
        $indexedSkus = $this->productAlternativeStorageRepository->getIndexedProductConcreteIdToSkusByProductIds($productIds);
        $this->storeConcreteProductData($indexedSkus);
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    protected function storeAbstractReplacements(array $productIds): void
    {
        $productAbstractIndexedSkus = $this->productAlternativeStorageRepository->getIndexedProductAbstractIdToSkusByProductIds($productIds);
        $this->storeAbstractProductData($productAbstractIndexedSkus);
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    protected function storeConcreteReplacementsByAbstract(array $productIds): void
    {
        $productConcreteIndexedSkusPerAbstract = $this->productAlternativeStorageRepository->getIndexedProductConcreteIdToSkusByProductAbstractIds($productIds);
        $this->storeConcreteProductData($productConcreteIndexedSkusPerAbstract);
    }

    /**
     * @param array $indexedSkus
     *
     * @return void
     */
    protected function storeAbstractProductData(array $indexedSkus): void
    {
        foreach ($indexedSkus as $idProductAbstract => $productAbstractData) {
            $replacementIds = $this->productAlternativeStorageRepository->getReplacementsByAbstractProductId($idProductAbstract);
            $sku = $productAbstractData[ProductAbstractTransfer::SKU];
            $productReplacementStorageEntity = $this->productAlternativeStorageRepository->findProductReplacementStorageEntitiesBySku($sku);
            $this->storeDataSet($sku, $replacementIds, $productReplacementStorageEntity);
        }
    }

    /**
     * @param array $indexedSkus
     *
     * @return void
     */
    protected function storeConcreteProductData(
        array $indexedSkus
    ): void {
        foreach ($indexedSkus as $idProduct => $productConcreteData) {
            $replacementIds = array_merge(
                $this->productAlternativeStorageRepository->getReplacementsByConcreteProductId($idProduct),
                $this->productAlternativeStorageRepository->getReplacementsByAbstractProductId($productConcreteData[ProductConcreteTransfer::FK_PRODUCT_ABSTRACT])
            );
            $sku = $productConcreteData[ProductConcreteTransfer::SKU];
            $productReplacementStorageEntity = $this->productAlternativeStorageRepository->findProductReplacementStorageEntitiesBySku($sku);
            $this->storeDataSet($sku, $replacementIds, $productReplacementStorageEntity);
        }
    }

    /**
     * @param string $sku
     * @param int[] $replacementIds
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage|null $productReplacementStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(
        string $sku,
        array $replacementIds,
        ?SpyProductReplacementForStorage $productReplacementStorageEntity
    ): void {
        if (empty($replacementIds) && $productReplacementStorageEntity) {
            $this->productAlternativeStorageEntityManager->deleteProductReplacementForStorage($productReplacementStorageEntity);

            return;
        }

        if (!$productReplacementStorageEntity) {
            $productReplacementStorageEntity = new SpyProductReplacementForStorage();
        }

        $productReplacementStorage = (new ProductReplacementStorageTransfer())
            ->setProductConcreteIds($replacementIds);

        $productReplacementStorageEntity
            ->setSku($sku)
            ->setData(
                $productReplacementStorage->toArray()
            );

        $productReplacementStorageEntity->setIsSendingToQueue($this->productAlternativeStorageConfig->isSendingToQueue());
        $this->productAlternativeStorageEntityManager->saveProductReplacementForStorage($productReplacementStorageEntity);
    }
}
