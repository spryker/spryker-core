<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Suggest;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;
use Spryker\Zed\Product\ProductConfig;

class ProductSuggester implements ProductSuggesterInterface
{
    /**
     * @var \Spryker\Zed\Product\ProductConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Product\ProductConfig $config
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductConfig $config,
        ProductRepositoryInterface $productRepository,
        ProductToLocaleInterface $localeFacade
    ) {
        $this->config = $config;
        $this->productRepository = $productRepository;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param string $suggestion
     * @param int|null $limit
     *
     * @return string[]
     */
    public function suggestProductAbstract(string $suggestion, ?int $limit = null): array
    {
        return array_unique(
            array_merge(
                $this->suggestProductAbstractName($suggestion),
                $this->suggestProductAbstractSku($suggestion)
            )
        );
    }

    /**
     * @param string $suggestion
     * @param int|null $limit
     *
     * @return string[]
     */
    public function suggestProductConcrete(string $suggestion, ?int $limit = null): array
    {
        return array_unique(
            array_merge(
                $this->suggestProductConcreteName($suggestion),
                $this->suggestProductConcreteSku($suggestion)
            )
        );
    }

    /**
     * @param string $productName
     * @param null|int $limit
     *
     * @return string[]
     */
    protected function suggestProductAbstractName(string $productName, ?int $limit = null): array
    {
        $limit = $limit ?? $this->config->getFilteredProductsLimitDefault();

        $abstractProducts = $this->collectFilteredResults(
            $this->productRepository
                ->filterProductAbstractByLocalizedName(
                    $this->getCurrentLocale(),
                    $productName,
                    $limit
                )
        );

        return $abstractProducts;
    }

    /**
     * @param string $productSku
     * @param null|int $limit
     *
     * @return string[]
     */
    protected function suggestProductAbstractSku(string $productSku, ?int $limit = null): array
    {
        $limit = $limit ?? $this->config->getFilteredProductsLimitDefault();

        $abstractProducts = $this->collectFilteredResults(
            $this->productRepository
                ->filterProductAbstractBySku(
                    $productSku,
                    $limit
                )
        );

        return $abstractProducts;
    }

    /**
     * @param string $productName
     * @param null|int $limit
     *
     * @return string[]
     */
    protected function suggestProductConcreteName(string $productName, ?int $limit = null): array
    {
        $limit = $limit ?? $this->config->getFilteredProductsLimitDefault();

        $concreteProducts = $this->collectFilteredResults(
            $this->productRepository
                ->filterProductConcreteByLocalizedName(
                    $this->getCurrentLocale(),
                    $productName,
                    $limit
                )
        );

        return $concreteProducts;
    }

    /**
     * @param string $productSku
     * @param null|int $limit
     *
     * @return string[]
     */
    protected function suggestProductConcreteSku(string $productSku, ?int $limit = null): array
    {
        $limit = $limit ?? $this->config->getFilteredProductsLimitDefault();

        $concreteProducts = $this->collectFilteredResults(
            $this->productRepository
                ->filterProductConcreteBySku($productSku, $limit)
        );

        return $concreteProducts;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale(): LocaleTransfer
    {
        return $this->localeFacade
            ->getCurrentLocale();
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
            $results[] = $product[ProductConstants::KEY_FILTERED_PRODUCTS_RESULT];
        }

        return $results;
    }
}
