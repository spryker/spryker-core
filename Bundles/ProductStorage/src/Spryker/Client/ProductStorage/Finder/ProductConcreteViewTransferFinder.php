<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Finder;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface;
use Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface;

class ProductConcreteViewTransferFinder implements ProductViewTransferFinderInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface
     */
    protected $productConcreteStorage;

    /**
     * @var \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface
     */
    protected $ProductStorageDataMapperInterface;

    /**
     * @param \Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface $productAbstractStorage
     * @param \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface $ProductStorageDataMapperInterface
     */
    public function __construct(ProductConcreteStorageReaderInterface $productAbstractStorage, ProductStorageDataMapperInterface $ProductStorageDataMapperInterface)
    {
        $this->productConcreteStorage = $productAbstractStorage;
        $this->ProductStorageDataMapperInterface = $ProductStorageDataMapperInterface;
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
        $data = $this->productConcreteStorage->findProductConcreteStorageData($idProductAbstract, $localeName);
        if (!$data) {
            return null;
        }
        return $this->ProductStorageDataMapperInterface->mapProductStorageData($localeName, $data, $selectedAttributes);
    }
}
