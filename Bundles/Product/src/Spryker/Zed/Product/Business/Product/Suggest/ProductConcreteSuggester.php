<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Suggest;

use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;
use Spryker\Zed\Product\ProductConfig;

class ProductConcreteSuggester extends AbstractProductSuggester implements ProductConcreteSuggesterInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @param \Spryker\Zed\Product\ProductConfig $config
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     */
    public function __construct(
        ProductConfig $config,
        ProductConcreteManagerInterface $productConcreteManager
    ) {
        $this->config = $config;
        $this->productConcreteManager = $productConcreteManager;
    }

    /**
     * @param string $searchName
     * @param int|null $limit
     *
     * @return array
     */
    public function suggestProductConcrete(string $searchName, ?int $limit = null): array
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

        $concreteProducts = $this->collectFilteredResults(
            $this->productConcreteManager->filterProductConcreteByLocalizedName($productName, $limit)
        );

        return $concreteProducts;
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

        $concreteProducts = $this->collectFilteredResults(
            $this->productConcreteManager->filterProductConcreteBySku($productSku, $limit)
        );

        return $concreteProducts;
    }
}
