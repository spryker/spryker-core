<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Finder;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface;
use Spryker\Client\ProductStorage\Storage\ProductAbstractStorageReaderInterface;

class ProductAbstractViewTransferFinder extends AbstractProductViewTransferFinder
{
    protected const KEY_ID_PRODUCT = 'id_product_abstract';

    /**
     * @var \Spryker\Client\ProductStorage\Storage\ProductAbstractStorageReaderInterface
     */
    protected $productAbstractStorageReader;

    /**
     * @var array
     */
    protected static $productViewTransfersCache = [];

    /**
     * @param \Spryker\Client\ProductStorage\Storage\ProductAbstractStorageReaderInterface $productAbstractStorage
     * @param \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface $productStorageDataMapper
     */
    public function __construct(ProductAbstractStorageReaderInterface $productAbstractStorage, ProductStorageDataMapperInterface $productStorageDataMapper)
    {
        parent::__construct($productStorageDataMapper);
        $this->productAbstractStorageReader = $productAbstractStorage;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array|null
     */
    protected function findProductStorageData(int $idProductAbstract, string $localeName): ?array
    {
        return $this->productAbstractStorageReader->findProductAbstractStorageData($idProductAbstract, $localeName);
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
            ->productAbstractStorageReader
            ->getBulkProductAbstractStorageDataByProductAbstractIdsAndLocaleName($productIds, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return int
     */
    protected function getProductId(ProductViewTransfer $productViewTransfer): int
    {
        return $productViewTransfer->getIdProductAbstract();
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
