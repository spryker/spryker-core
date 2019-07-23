<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Finder;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface;
use Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface;

class ProductConcreteViewTransferFinder extends AbstractProductViewTransferFinder
{
    protected const KEY_ID_PRODUCT = 'id_product_concrete';

    /**
     * @var \Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface
     */
    protected $productConcreteStorage;

    /**
     * @var array
     */
    protected static $productViewTransfersCache = [];

    /**
     * @param \Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface $productConcreteStorage
     * @param \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface $productStorageDataMapper
     */
    public function __construct(ProductConcreteStorageReaderInterface $productConcreteStorage, ProductStorageDataMapperInterface $productStorageDataMapper)
    {
        parent::__construct($productStorageDataMapper);
        $this->productConcreteStorage = $productConcreteStorage;
    }

    /**
     * @param int $idProductConcrete
     * @param string $localeName
     *
     * @return array|null
     */
    protected function findProductStorageData(int $idProductConcrete, string $localeName): ?array
    {
        return $this->productConcreteStorage->findProductConcreteStorageData($idProductConcrete, $localeName);
    }

    /**
     * @param int[] $productIds
     * @param string $localeName
     *
     * @return array
     */
    protected function getBulkProductStorageData(array $productIds, string $localeName): array
    {
        return $this
            ->productConcreteStorage
            ->getBulkProductConcreteStorageDataByProductConcreteIdsAndLocaleName($productIds, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return int
     */
    protected function getProductId(ProductViewTransfer $productViewTransfer): int
    {
        return $productViewTransfer->getIdProductConcrete();
    }

    /**
     * @param array $productData
     *
     * @return int
     */
    protected function getProductDataProductId(array $productData): int
    {
        return $productData[static::KEY_ID_PRODUCT];
    }
}
