<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Suggest;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductSuggestionDetailsTransfer;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;
use Spryker\Zed\Product\ProductConfig;

class ProductSuggestionDetailsProvider implements ProductSuggestionDetailsProviderInterface
{
    /**
     * @var \Spryker\Zed\Product\ProductConfig $config
     */
    protected $config;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface
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
     *
     * @return \Generated\Shared\Transfer\ProductSuggestionDetailsTransfer
     */
    public function getSuggestionDetails(string $suggestion): ProductSuggestionDetailsTransfer
    {
        $limit = $this->config->getFilteredProductsLimitDefault();

        $productSuggestionDetailsTransfer = (new ProductSuggestionDetailsTransfer())
            ->setIsSuccessful(false);

        $productAbstract = $this->getIdProductAbstractBySuggestion($suggestion, $limit);
        if ($productAbstract) {
            return $productSuggestionDetailsTransfer
                ->setIsSuccessful(true)
                ->setIdProductAbstract($productAbstract);
        }

        $productConcrete = $this->getIdProductConcreteBySuggestion($suggestion, $limit);
        if ($productConcrete) {
            return $productSuggestionDetailsTransfer
                ->setIsSuccessful(true)
                ->setIdProductConcrete($productConcrete);
        }

        return $productSuggestionDetailsTransfer;
    }

    /**
     * TODO: Resolve the case when multiple products were found by name.
     * TODO: Add SKU rendering for the case above.
     *
     * @param string $suggestion
     * @param null|int $limit
     *
     * @return null|int
     */
    protected function getIdProductAbstractBySuggestion(string $suggestion, ?int $limit = null): ?int
    {
        $productAbstract = $this->productRepository
            ->filterProductAbstractByLocalizedName(
                $this->getCurrentLocale(),
                $suggestion,
                $limit
            );

        $productAbstract = reset($productAbstract);

        if (!empty($productAbstract) && isset($productAbstract[ProductConstants::KEY_FILTERED_PRODUCTS_ABSTRACT_ID])) {
            return $productAbstract[ProductConstants::KEY_FILTERED_PRODUCTS_ABSTRACT_ID];
        }

        $productAbstract = $this->productRepository
            ->filterProductAbstractBySku(
                $suggestion,
                $limit
            );

        if (!empty($productAbstract) && isset($productAbstract[ProductConstants::KEY_FILTERED_PRODUCTS_ABSTRACT_ID])) {
            return $productAbstract[ProductConstants::KEY_FILTERED_PRODUCTS_ABSTRACT_ID];
        }

        return null;
    }

    /**
     * TODO: Resolve the case when multiple products were found by name.
     *
     * @param string $suggestion
     * @param null|int $limit
     *
     * @return null|int
     */
    protected function getIdProductConcreteBySuggestion(string $suggestion, ?int $limit = null): ?int
    {
        $productConcrete = $this->productRepository
            ->filterProductConcreteByLocalizedName(
                $this->getCurrentLocale(),
                $suggestion,
                $limit
            );
        $productConcrete = reset($productConcrete);

        if (!empty($productConcrete) && isset($productConcrete[ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID])) {
            return $productConcrete[ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID];
        }

        $productConcrete = $this->productRepository->filterProductConcreteBySku(
            $suggestion,
            $limit
        );

        if (!empty($productConcrete) && isset($productConcrete[ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID])) {
            return $productConcrete[ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID];
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale(): LocaleTransfer
    {
        return $this->localeFacade->getCurrentLocale();
    }
}
