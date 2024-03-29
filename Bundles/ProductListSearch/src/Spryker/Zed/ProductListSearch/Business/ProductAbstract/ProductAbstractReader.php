<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Business\ProductAbstract;

use Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface;

class ProductAbstractReader implements ProductAbstractReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface
     */
    protected $productListSearchRepository;

    /**
     * @param \Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface $productListSearchRepository
     */
    public function __construct(
        ProductListSearchRepositoryInterface $productListSearchRepository
    ) {
        $this->productListSearchRepository = $productListSearchRepository;
    }

    /**
     * @param array<int> $concreteIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByConcreteIds(array $concreteIds): array
    {
        return $this->productListSearchRepository->getProductAbstractIdsByConcreteIds($concreteIds);
    }

    /**
     * @param array<int> $categoryIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array
    {
        return $this->productListSearchRepository->getProductAbstractIdsByCategoryIds($categoryIds);
    }
}
