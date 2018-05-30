<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business\Product;

use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductFacadeInterface;
use Spryker\Zed\ProductAlternativeGui\ProductAlternativeGuiConfig;

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
     * @var \Spryker\Zed\ProductAlternativeGui\ProductAlternativeGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface $productAlternativeFacade
     * @param \Spryker\Zed\ProductAlternativeGui\ProductAlternativeGuiConfig $config
     */
    public function __construct(
        ProductAlternativeGuiToProductFacadeInterface $productFacade,
        ProductAlternativeGuiToProductAlternativeFacadeInterface $productAlternativeFacade,
        ProductAlternativeGuiConfig $config
    ) {
        $this->productFacade = $productFacade;
        $this->productAlternativeFacade = $productAlternativeFacade;
        $this->config = $config;
    }

    /**
     * @param string $searchName
     * @param int|null $limit
     *
     * @return array
     */
    public function suggestProduct(string $searchName, ?int $limit = null): array
    {
        return array_unique(
            array_merge(
                $this->suggestProductName($searchName),
                $this->suggestProductSku($searchName)
            )
        );
    }

    /**
     * @param string $productName
     * @param null|int $limit
     *
     * @return string[]
     */
    protected function suggestProductName(string $productName, ?int $limit = null): array
    {
        $limit = $limit ?? $this->config->getFilteredProductsLimitDefault();

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
     * @param null|int $limit
     *
     * @return string[]
     */
    protected function suggestProductSku(string $productSku, ?int $limit = null): array
    {
        $limit = $limit ?? $this->config->getFilteredProductsLimitDefault();

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
            $results[] = $product[ProductConstants::FILTERED_PRODUCTS_RESULT_KEY];
        }

        return $results;
    }
}
