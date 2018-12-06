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
    protected $productStorageDataMapper;

    /**
     * @param \Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface $productConcreteStorage
     * @param \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface $productStorageDataMapper
     */
    public function __construct(ProductConcreteStorageReaderInterface $productConcreteStorage, ProductStorageDataMapperInterface $productStorageDataMapper)
    {
        $this->productConcreteStorage = $productConcreteStorage;
        $this->productStorageDataMapper = $productStorageDataMapper;
    }

    /**
     * @param int $idProductConcrete
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductViewTransfer(int $idProductConcrete, string $localeName, array $selectedAttributes): ?ProductViewTransfer
    {
        $data = $this->productConcreteStorage->findProductConcreteStorageData($idProductConcrete, $localeName);
        if (!$data) {
            return null;
        }
        return $this->productStorageDataMapper->mapProductStorageData($localeName, $data, $selectedAttributes);
    }
}
