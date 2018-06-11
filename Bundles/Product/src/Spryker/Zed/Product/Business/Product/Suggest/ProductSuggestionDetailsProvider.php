<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Suggest;

use Generated\Shared\Transfer\ProductSuggestionDetailsTransfer;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface;
use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;
use Spryker\Zed\Product\ProductConfig;

class ProductSuggestionDetailsProvider implements ProductSuggestionDetailsProviderInterface
{
    /**
     * @var \Spryker\Zed\Product\ProductConfig $config
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    protected $productAbstractManager;

    /**
     * @param \Spryker\Zed\Product\ProductConfig $config
     * @param \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface $productAbstractManager
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     */
    public function __construct(
        ProductConfig $config,
        ProductAbstractManagerInterface $productAbstractManager,
        ProductConcreteManagerInterface $productConcreteManager
    ) {
        $this->config = $config;
        $this->productAbstractManager = $productAbstractManager;
        $this->productConcreteManager = $productConcreteManager;
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
     *
     * @param string $suggestion
     * @param int $limit
     *
     * @return null|int
     */
    protected function getIdProductAbstractBySuggestion(string $suggestion, int $limit): ?int
    {
        /** @var null|array $productAbstract */
        $productAbstract = $this
            ->productAbstractManager
            ->filterProductAbstractByLocalizedName(
                $suggestion,
                $limit
            );

        $productAbstract = reset($productAbstract);

        if (!empty($productAbstract) && isset($productAbstract[ProductConstants::KEY_FILTERED_PRODUCTS_ABSTRACT_ID])) {
            return $productAbstract[ProductConstants::KEY_FILTERED_PRODUCTS_ABSTRACT_ID];
        }

        $productAbstract = $this
            ->productAbstractManager
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
     * @param int $limit
     *
     * @return null|int
     */
    protected function getIdProductConcreteBySuggestion(string $suggestion, int $limit): ?int
    {
        /** @var null|array $productConcrete */
        $productConcrete = $this
            ->productConcreteManager
            ->filterProductConcreteByLocalizedName(
                $suggestion,
                $limit
            );
        $productConcrete = reset($productConcrete);

        if (!empty($productConcrete) && isset($productConcrete[ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID])) {
            return $productConcrete[ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID];
        }

        $productConcrete = $this
            ->productConcreteManager
            ->filterProductConcreteBySku(
                $suggestion,
                $limit
            );

        if (!empty($productConcrete) && isset($productConcrete[ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID])) {
            return $productConcrete[ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID];
        }

        return null;
    }
}
