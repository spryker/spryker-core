<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Suggest;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Persistence\ProductRepository;
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
        $limit = $limit ?? $this->config->getFilteredProductsLimitDefault();

        $productAbstractNames = $this->collectFilteredResults(
            $this->productRepository
                ->findProductAbstractDataBySkuOrLocalizedName(
                    $suggestion,
                    $this->getCurrentLocale(),
                    $limit
                )
        );

        return $productAbstractNames;
    }

    /**
     * @param string $suggestion
     * @param int|null $limit
     *
     * @return string[]
     */
    public function suggestProductConcrete(string $suggestion, ?int $limit = null): array
    {
        $limit = $limit ?? $this->config->getFilteredProductsLimitDefault();

        $productConcreteNames = $this->collectFilteredResults(
            $this->productRepository
                ->findProductConcreteDataBySkuOrLocalizedName(
                    $suggestion,
                    $this->getCurrentLocale(),
                    $limit
                )
        );

        return $productConcreteNames;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale(): LocaleTransfer
    {
        return $this->localeFacade->getCurrentLocale();
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
            $results[$product[ProductRepository::KEY_FILTERED_PRODUCTS_RESULT]] = $product[ProductRepository::KEY_FILTERED_PRODUCTS_PRODUCT_NAME];
        }

        return $results;
    }
}
