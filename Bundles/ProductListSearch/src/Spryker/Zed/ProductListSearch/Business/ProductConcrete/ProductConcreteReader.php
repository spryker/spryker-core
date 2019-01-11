<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Business\ProductConcrete;

use Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface
     */
    protected $productListSearchRepository;

    /**
     * @param \Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface $productListSearchRepository
     */
    public function __construct(ProductListSearchRepositoryInterface $productListSearchRepository)
    {
        $this->productListSearchRepository = $productListSearchRepository;
    }

    /**
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function findProductConcreteIdsByCategoryIds(array $categoryIds): array
    {
        return $this->productListSearchRepository->findProductConcreteIdsByCategoryIds($categoryIds);
    }
}
