<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Finder;

use Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface;
use Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface;

class ProductConcreteViewTransferFinder extends AbstractProductViewTransferFinder
{
    /**
     * @var \Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface
     */
    protected $productConcreteStorage;

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
}
