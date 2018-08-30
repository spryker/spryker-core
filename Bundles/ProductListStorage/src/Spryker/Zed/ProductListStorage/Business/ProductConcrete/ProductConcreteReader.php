<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductConcrete;

use Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface
     */
    protected $productListStorageRepository;

    /**
     * @param \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface $productListStorageRepository
     */
    public function __construct(
        ProductListStorageRepositoryInterface $productListStorageRepository
    ) {
        $this->productListStorageRepository = $productListStorageRepository;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function findProductConcreteIdsByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->productListStorageRepository->findProductConcreteIdsByProductAbstractIds($productAbstractIds);
    }
}
