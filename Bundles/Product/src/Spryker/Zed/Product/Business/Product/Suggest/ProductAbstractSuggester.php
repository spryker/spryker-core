<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Suggest;

use Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface;
use Spryker\Zed\Product\ProductConfig;

class ProductAbstractSuggester extends AbstractProductSuggester implements ProductAbstractSuggesterInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    protected $productAbstractManager;

    /**
     * @param \Spryker\Zed\Product\ProductConfig $config
     * @param \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface $productAbstractManager
     */
    public function __construct(
        ProductConfig $config,
        ProductAbstractManagerInterface $productAbstractManager
    ) {
        $this->config = $config;
        $this->productAbstractManager = $productAbstractManager;
    }

    /**
     * @param string $searchName
     * @param int|null $limit
     *
     * @return array
     */
    public function suggestProductAbstract(string $searchName, ?int $limit = null): array
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
            $this->productAbstractManager->filterProductAbstractByLocalizedName($productName, $limit)
        );

        return $abstractProducts;
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
            $this->productAbstractManager->filterProductAbstractBySku($productSku, $limit)
        );

        return $abstractProducts;
    }
}
