<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Finder;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface;
use Spryker\Client\ProductStorage\Storage\ProductAbstractStorageReaderInterface;

class ProductAbstractViewTransferFinder implements ProductViewTransferFinderInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\Storage\ProductAbstractStorageReaderInterface
     */
    protected $productAbstractStorage;

    /**
     * @var \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface
     */
    protected $productStorageDataMapper;

    /**
     * @param \Spryker\Client\ProductStorage\Storage\ProductAbstractStorageReaderInterface $productAbstractStorage
     * @param \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface $productStorageDataMapper
     */
    public function __construct(ProductAbstractStorageReaderInterface $productAbstractStorage, ProductStorageDataMapperInterface $productStorageDataMapper)
    {
        $this->productAbstractStorage = $productAbstractStorage;
        $this->productStorageDataMapper = $productStorageDataMapper;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductViewTransfer(int $idProductAbstract, string $localeName, array $selectedAttributes): ?ProductViewTransfer
    {
        $data = $this->productAbstractStorage->findProductAbstractStorageData($idProductAbstract, $localeName);
        if (!$data) {
            return null;
        }
        return $this->productStorageDataMapper->mapProductStorageData($localeName, $data, $selectedAttributes);
    }
}
