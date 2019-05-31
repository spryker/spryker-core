<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Finder;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\Exception\NotFoundProductViewTransferCacheException;
use Spryker\Client\ProductStorage\Exception\NotSpecifiedProductIdKeyException;
use Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface;
use Spryker\Client\ProductStorage\ProductStorageConfig;

abstract class AbstractProductViewTransferFinder implements ProductViewTransferFinderInterface
{
    protected const ERROR_MESSAGE_PRODUCT_VIEW_TRANSFER_NOT_FOUND_IN_CACHE = 'There is no ProductViewTransfer in cache with provided product id and local name';
    protected const KEY_ID_PRODUCT = null;
    protected const ERROR_MESSAGE_PRODUCT_ID_KEY_NOT_SPECIFIED = 'You should specify product id key in the implementation';

    /**
     * @var \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface
     */
    protected $productStorageDataMapper;

    /**
     * @var array Should be added to a not abstract class in order to have separate cache storage
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

        $data = $this->findProductStorageData($idProduct, $localeName);
        if ($data === null) {
            return null;
        }

        if (!isset($data[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP])) {
            $data[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP] = [];
        }

        $productViewTransfer = $this->productStorageDataMapper
            ->mapProductStorageData($localeName, $data, $selectedAttributes);
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
    public function findProductViewTransfers(array $productIds, string $localeName, array $selectedAttributes = []): array
    {
        $cachedProductViewTransfers = $this->getProductViewTransfersFromCache($productIds, $localeName);

        $ids = array_diff($productIds, array_keys($cachedProductViewTransfers));
        $productData = $this->findBulkProductStorageData($ids, $localeName);
        $productViewTransfers = $this->mapProductData($productData, $localeName, $selectedAttributes);

        return array_merge($cachedProductViewTransfers, $productViewTransfers);
    }

    /**
     * @param array $productData
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function mapProductData(array $productData, string $localeName, array $selectedAttributes = []): array
    {
        $productViewTransfers = [];
        foreach ($productData as $data) {
            if (!isset($data[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP])) {
                $data[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP] = [];
            }

            $productViewTransfer = $this
                ->productStorageDataMapper
                ->mapProductStorageData($localeName, $data, $this->findProductSelectedAttributes($data, $selectedAttributes));
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
     * @throws \Spryker\Client\ProductStorage\Exception\NotFoundProductViewTransferCacheException
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function getProductViewTransferFromCache(int $idProduct, string $localeName): ProductViewTransfer
    {
        if (!$this->hasProductViewTransferCache($idProduct, $localeName)) {
            throw new NotFoundProductViewTransferCacheException(static::ERROR_MESSAGE_PRODUCT_VIEW_TRANSFER_NOT_FOUND_IN_CACHE);
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
     * @throws \Spryker\Client\ProductStorage\Exception\NotSpecifiedProductIdKeyException
     *
     * @return array
     */
    protected function findProductSelectedAttributes(array $productData, array $selectedAttributes): array
    {
        if (!static::KEY_ID_PRODUCT) {
            throw new NotSpecifiedProductIdKeyException(static::ERROR_MESSAGE_PRODUCT_ID_KEY_NOT_SPECIFIED);
        }
        return $selectedAttributes[$productData[static::KEY_ID_PRODUCT]] ?? [];
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
    abstract protected function findBulkProductStorageData(array $productIds, string $localeName): array;
}
