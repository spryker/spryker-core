<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductAbstract;

use Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface;

class ProductAbstractReader implements ProductAbstractReaderInterface
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
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array
    {
        return $this->productListStorageRepository->findProductAbstractIdsByProductConcreteIds($productConcreteIds);
    }

    /**
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByCategoryIds(array $categoryIds): array
    {
        return $this->productListStorageRepository->findProductAbstractIdsByCategoryIds($categoryIds);
    }
}
