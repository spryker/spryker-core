<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Finder;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\Exception\ProductViewTransferCacheNotFoundException;
use Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface;
use Spryker\Client\ProductStorage\ProductStorageConfig;

abstract class AbstractProductViewTransferFinder implements ProductViewTransferFinderInterface
{
    protected const ERROR_MESSAGE_PRODUCT_VIEW_TRANSFER_NOT_FOUND_IN_CACHE = 'There is no `ProductViewTransfer` in the cache with provided product id and local name.';

    /**
     * @var \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface
     */
    protected $productStorageDataMapper;

    /**
     * @var array
     */
    protected static $productViewTransfersCache = [];

    /**
     * @param \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface $productStorageDataMapper
     */
    public function __construct(ProductStorageDataMapperInterface $productStorageDataMapper)
    {
        $this->productStorageDataMapper = $productStorageDataMapper;
    }

    /**
     * @param int $idProduct
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductViewTransfer(int $idProduct, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer
    {
        if ($this->hasProductViewTransferCache($idProduct, $localeName)) {
            return $this->getProductViewTransferFromCache($idProduct, $localeName);
        }

        $productStorageData = $this->findProductStorageData($idProduct, $localeName);
        if ($productStorageData === null) {
            return null;
        }

        if (!isset($productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP])) {
            $productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP] = [];
        }

        $productViewTransfer = $this->productStorageDataMapper
            ->mapProductStorageData($localeName, $productStorageData, $selectedAttributes);
        $this->cacheProductViewTransfer($productViewTransfer, $localeName);

        return $productViewTransfer;
    }

    /**
     * @param int[] $productIds
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getProductViewTransfers(array $productIds, string $localeName, array $selectedAttributes = []): array
    {
        $cachedProductViewTransfers = $this->getProductViewTransfersFromCache($productIds, $localeName);

        $productIds = array_diff($productIds, array_keys($cachedProductViewTransfers));
        if (!$productIds) {
            return $cachedProductViewTransfers;
        }

        $productStorageDataCollection = $this->getBulkProductStorageData($productIds, $localeName);
        $productViewTransfers = $this->mapProductData($productStorageDataCollection, $localeName, $selectedAttributes);

        return array_merge($cachedProductViewTransfers, $productViewTransfers);
    }

    /**
     * @param array $productStorageDataCollection
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function mapProductData(array $productStorageDataCollection, string $localeName, array $selectedAttributes = []): array
    {
        $productViewTransfers = [];
        foreach ($productStorageDataCollection as $productStorageData) {
            if (!isset($productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP])) {
                $productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP] = [];
            }

            $productViewTransfer = $this
                ->productStorageDataMapper
                ->mapProductStorageData($localeName, $productStorageData, $this->getProductSelectedAttributes($productStorageData, $selectedAttributes));
            $this->cacheProductViewTransfer($productViewTransfer, $localeName);

            $productViewTransfers[$this->getProductId($productViewTransfer)] = $productViewTransfer;
        }

        return $productViewTransfers;
    }

    /**
     * @param int $idProduct
     * @param string $localeName
     *
     * @return bool
     */
    protected function hasProductViewTransferCache(int $idProduct, string $localeName): bool
    {
        return isset(static::$productViewTransfersCache[$idProduct][$localeName]);
    }

    /**
     * @param int $idProduct
     * @param string $localeName
     *
     * @throws \Spryker\Client\ProductStorage\Exception\ProductViewTransferCacheNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function getProductViewTransferFromCache(int $idProduct, string $localeName): ProductViewTransfer
    {
        if (!$this->hasProductViewTransferCache($idProduct, $localeName)) {
            throw new ProductViewTransferCacheNotFoundException(static::ERROR_MESSAGE_PRODUCT_VIEW_TRANSFER_NOT_FOUND_IN_CACHE);
        }

        return static::$productViewTransfersCache[$idProduct][$localeName];
    }

    /**
     * @param int[] $productIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getProductViewTransfersFromCache(array $productIds, string $localeName): array
    {
        $cachedProductAbstractData = [];
        foreach ($productIds as $idProduct) {
            if ($this->hasProductViewTransferCache($idProduct, $localeName)) {
                $cachedProductAbstractData[$idProduct] = $this->getProductViewTransferFromCache($idProduct, $localeName);
            }
        }

        return $cachedProductAbstractData;
    }

    /**
     * @param array $productData
     * @param array $selectedAttributes
     *
     * @return array
     */
    protected function getProductSelectedAttributes(array $productData, array $selectedAttributes): array
    {
        return $selectedAttributes[$this->getProductDataProductId($productData)] ?? [];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return void
     */
    protected function cacheProductViewTransfer(ProductViewTransfer $productViewTransfer, string $localeName): void
    {
        static::$productViewTransfersCache[$this->getProductId($productViewTransfer)][$localeName] = $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return int
     */
    abstract protected function getProductId(ProductViewTransfer $productViewTransfer): int;

    /**
     * @param array $productData
     *
     * @return int
     */
    abstract protected function getProductDataProductId(array $productData): int;

    /**
     * @param int $idProduct
     * @param string $localeName
     *
     * @return array|null
     */
    abstract protected function findProductStorageData(int $idProduct, string $localeName): ?array;

    /**
     * @param int[] $productIds
     * @param string $localeName
     *
     * @return array
     */
    abstract protected function getBulkProductStorageData(array $productIds, string $localeName): array;
}
