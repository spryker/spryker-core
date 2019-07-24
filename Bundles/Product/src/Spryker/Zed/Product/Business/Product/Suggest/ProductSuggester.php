<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Suggest;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer;
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
        $limit = $limit ?: $this->config->getFilteredProductsLimitDefault();

        return $this->productRepository->findProductAbstractDataBySkuOrLocalizedName(
            $suggestion,
            $this->getCurrentLocale(),
            $limit
        );
    }

    /**
     * @param string $suggestion
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer
     */
    public function getPaginatedProductAbstractSuggestions(string $suggestion, PaginationTransfer $paginationTransfer): ProductAbstractSuggestionCollectionTransfer
    {
        return $this->productRepository->getProductAbstractSuggestionCollectionBySkuOrLocalizedName(
            $suggestion,
            $paginationTransfer,
            $this->getCurrentLocale()
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
        $limit = $limit ?: $this->config->getFilteredProductsLimitDefault();

        return $this->productRepository->findProductConcreteDataBySkuOrLocalizedName(
            $suggestion,
            $this->getCurrentLocale(),
            $limit
        );
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale(): LocaleTransfer
    {
        return $this->localeFacade->getCurrentLocale();
    }
}
