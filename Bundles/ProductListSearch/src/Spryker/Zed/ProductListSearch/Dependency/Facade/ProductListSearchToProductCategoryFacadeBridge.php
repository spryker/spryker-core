<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Dependency\Facade;

class ProductListSearchToProductCategoryFacadeBridge implements ProductListSearchToProductCategoryFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface
     */
    protected $productCategoryFacade;

    /**
     * @param \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface $productCategoryFacade
     */
    public function __construct($productCategoryFacade)
    {
        $this->productCategoryFacade = $productCategoryFacade;
    }

    /**
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByCategoryIds(array $categoryIds): array
    {
        return $this->productCategoryFacade->getProductConcreteIdsByCategoryIds($categoryIds);
    }
}
