<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Finder;

use Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface;
use Spryker\Client\ProductStorage\Storage\ProductAbstractStorageReaderInterface;

class ProductAbstractViewTransferFinder extends AbstractProductViewTransferFinder
{
    /**
     * @var \Spryker\Client\ProductStorage\Storage\ProductAbstractStorageReaderInterface
     */
    protected $productAbstractStorageReader;

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
}
