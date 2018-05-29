<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Dependency\Facade;

use Spryker\Shared\Product\ProductConstants;

class ProductAlternativeGuiToProductFacadeBridge implements ProductAlternativeGuiToProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $sku
     * @param int $limit
     *
     * @return array
     */
    public function filterProductAbstractBySku(string $sku, int $limit = ProductConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array
    {
        return $this->productFacade->filterProductAbstractBySku($sku, $limit);
    }

    /**
     * @param string $localizedName
     * @param int $limit
     *
     * @return array
     */
    public function filterProductAbstractByLocalizedName(string $localizedName, int $limit = ProductConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array
    {
        return $this->productFacade->filterProductAbstractByLocalizedName($localizedName, $limit);
    }

    /**
     * @param string $sku
     * @param int $limit
     *
     * @return array
     */
    public function filterProductConcreteBySku(string $sku, int $limit = ProductConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array
    {
        return $this->productFacade->filterProductConcreteBySku($sku, $limit);
    }

    /**
     * @param string $localizedName
     * @param int $limit
     *
     * @return array
     */
    public function filterProductConcreteByLocalizedName(string $localizedName, int $limit = ProductConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array
    {
        return $this->productFacade->filterProductConcreteByLocalizedName($localizedName, $limit);
    }
}
