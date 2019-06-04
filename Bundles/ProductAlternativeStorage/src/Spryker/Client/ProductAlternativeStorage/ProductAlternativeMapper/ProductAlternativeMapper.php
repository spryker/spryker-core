<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAlternativeStorage\ProductAlternativeMapper;

use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductAlternativeStorage\Dependency\Client\ProductAlternativeStorageToProductStorageClientInterface;
use Spryker\Client\ProductAlternativeStorage\ProductAlternativeStorageConfig;
use Spryker\Client\ProductAlternativeStorage\Storage\ProductAlternativeStorageReaderInterface;

class ProductAlternativeMapper implements ProductAlternativeMapperInterface
{
    protected const PRODUCT_CONCRETE_IDS = 'product_concrete_ids';

    /**
     * @var \Spryker\Client\ProductAlternativeStorage\Dependency\Client\ProductAlternativeStorageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Client\ProductAlternativeStorage\Storage\ProductAlternativeStorageReaderInterface
     */
    protected $productAlternativeStorageReader;

    /**
     * @param \Spryker\Client\ProductAlternativeStorage\Storage\ProductAlternativeStorageReaderInterface $productAlternativeStorageReader
     * @param \Spryker\Client\ProductAlternativeStorage\Dependency\Client\ProductAlternativeStorageToProductStorageClientInterface $productStorageClient
     */
    public function __construct(
        ProductAlternativeStorageReaderInterface $productAlternativeStorageReader,
        ProductAlternativeStorageToProductStorageClientInterface $productStorageClient
    ) {
        $this->productAlternativeStorageReader = $productAlternativeStorageReader;
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getConcreteAlternativeProducts(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        $productReplacementForStorage = $this->productAlternativeStorageReader
            ->findProductAlternativeStorage($productViewTransfer->getSku());
        if (!$productReplacementForStorage) {
            return [];
        }

        $productViewTransferList = [];
        $productConcreteIds = $productReplacementForStorage->getProductConcreteIds();
        $productConcreteIds = array_merge(
            $productConcreteIds,
            $this->findConcreteProductIdsByAbstractProductIds(
                $productReplacementForStorage->getProductAbstractIds(),
                $localeName
            )
        );
        foreach ($productConcreteIds as $idProduct) {
            $productViewTransferList[] = $this->findConcreteProductViewTransfer($idProduct, $localeName);
        }

        return array_filter($productViewTransferList);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getAlternativeProducts(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        if ($productViewTransfer->getIdProductConcrete()) {
            return $this->getAlternativeProductsByConcreteProductSku($productViewTransfer->getSku(), $localeName);
        }

        return $this->getAlternativeProductsByAbstractProductSku($productViewTransfer, $localeName);
    }

    /**
     * @param string $concreteSku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getAlternativeProductsByConcreteProductSku(string $concreteSku, string $localeName): array
    {
        $productAlternativeStorage = $this->productAlternativeStorageReader->findProductAlternativeStorage($concreteSku);
        if (!$productAlternativeStorage) {
            return [];
        }

        return $this->mapProductAlternativeStorageToProductView($productAlternativeStorage, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getAlternativeProductsByAbstractProductSku(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        $productAlternativeStorage = $this->findProductAlternativeStorageForAbstractProduct($productViewTransfer);
        if (!$productAlternativeStorage) {
            return [];
        }

        return $this->mapProductAlternativeStorageToProductView($productAlternativeStorage, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeStorageTransfer $productAlternativeStorage
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function mapProductAlternativeStorageToProductView(
        ProductAlternativeStorageTransfer $productAlternativeStorage,
        string $localeName
    ): array {
        $productViewTransferList = [];
        foreach ($productAlternativeStorage->getProductAbstractIds() as $idProductAbstract) {
            $productViewTransferList[] = $this->findAbstractProductViewTransfer($idProductAbstract, $localeName);
        }

        foreach ($productAlternativeStorage->getProductConcreteIds() as $idProduct) {
            $productViewTransferList[] = $this->findConcreteProductViewTransfer($idProduct, $localeName);
        }

        return array_filter($productViewTransferList);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeStorageTransfer|null
     */
    protected function findProductAlternativeStorageForAbstractProduct(ProductViewTransfer $productViewTransfer): ?ProductAlternativeStorageTransfer
    {
        $attributeMap = $productViewTransfer->getAttributeMap();
        if (!$attributeMap) {
            return null;
        }
        $productAlternativeStorageTransfer = new ProductAlternativeStorageTransfer();
        $productAbstractIds = [];
        $productConcreteIds = [];
        foreach (array_keys($attributeMap->getProductConcreteIds()) as $concreteSku) {
            $concreteProductAlternativeStorageTransfer = $this->productAlternativeStorageReader->findProductAlternativeStorage($concreteSku);
            if (!$concreteProductAlternativeStorageTransfer) {
                return null;
            }
            $productAbstractIds = array_merge($productAbstractIds, $concreteProductAlternativeStorageTransfer->getProductAbstractIds());
            $productConcreteIds = array_merge($productConcreteIds, $concreteProductAlternativeStorageTransfer->getProductConcreteIds());
        }

        return $productAlternativeStorageTransfer
            ->setProductAbstractIds(array_unique($productAbstractIds))
            ->setProductConcreteIds(array_unique($productConcreteIds));
    }

    /**
     * @param int $idProduct
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    protected function findConcreteProductViewTransfer(int $idProduct, string $localeName): ?ProductViewTransfer
    {
        $productViewTransfer = $this->productStorageClient
            ->findProductConcreteViewTransfer($idProduct, $localeName);

        if ($productViewTransfer && $productViewTransfer->getAvailable()) {
            return $productViewTransfer;
        }

        return null;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    protected function findAbstractProductViewTransfer(int $idProductAbstract, string $localeName): ?ProductViewTransfer
    {
        return $this->productStorageClient->findProductAbstractViewTransfer($idProductAbstract, $localeName);
    }

    /**
     * @param int[] $abstractProductIds
     * @param string $localeName
     *
     * @return int[]
     */
    protected function findConcreteProductIdsByAbstractProductIds(array $abstractProductIds, string $localeName): array
    {
        $productConcreteIds = [];
        $productAbstractStorageDataCollection = $this
            ->productStorageClient
            ->getBulkProductAbstractStorageDataByProductAbstractIdsAndLocaleName($abstractProductIds, $localeName);

        foreach ($productAbstractStorageDataCollection as $productAbstractStorageData) {
            $productConcreteIds = array_merge(
                $productConcreteIds,
                $productAbstractStorageData[ProductAlternativeStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::PRODUCT_CONCRETE_IDS] ?? []
            );
        }

        return array_values($productConcreteIds);
    }
}
