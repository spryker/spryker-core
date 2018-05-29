<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business\Model;

use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductFacadeInterface;

class ProductSuggester implements ProductSuggesterInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @param \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface $productAlternativeFacade
     */
    public function __construct(
        ProductAlternativeGuiToProductFacadeInterface $productFacade,
        ProductAlternativeGuiToProductAlternativeFacadeInterface $productAlternativeFacade
    ) {
        $this->productFacade = $productFacade;
        $this->productAlternativeFacade = $productAlternativeFacade;
    }

    /**
     * @param string $productName
     * @param int $limit
     *
     * @return string[]
     */
    public function suggestProductNames(string $productName, int $limit = ProductConstants::PRODUCT_FILTER_LIMIT_DEFAULT): array
    {
        $abstractProducts = $this->collectFilteredResults(
            $this->productFacade->filterProductAbstractByLocalizedName($productName, $limit)
        );

        $concreteProducts = $this->collectFilteredResults(
            $this->productFacade->filterProductConcreteByLocalizedName($productName, $limit)
        );

        return array_unique(
            array_merge($abstractProducts, $concreteProducts)
        );
    }

    /**
     * @param string $productSku
     * @param int $limit
     *
     * @return string[]
     */
    public function suggestProductSkus(string $productSku, int $limit = ProductConstants::PRODUCT_FILTER_LIMIT_DEFAULT): array
    {
        $abstractProducts = $this->collectFilteredResults(
            $this->productFacade->filterProductAbstractBySku($productSku, $limit)
        );

        $concreteProducts = $this->collectFilteredResults(
            $this->productFacade->filterProductConcreteBySku($productSku, $limit)
        );

        return array_unique(
            array_merge($abstractProducts, $concreteProducts)
        );
    }

    /**
     * @param array $products
     *
     * @return array
     */
    protected function collectFilteredResults(array $products): array
    {
        $results = [];

        foreach ($products as $product) {
            $results[] = $product[ProductConstants::PRODUCT_FILTER_RESULT_KEY];
        }

        return $results;
    }
}
