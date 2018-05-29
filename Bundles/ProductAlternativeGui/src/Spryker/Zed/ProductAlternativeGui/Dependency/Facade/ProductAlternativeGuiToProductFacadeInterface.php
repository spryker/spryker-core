<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Dependency\Facade;

use Spryker\Shared\Product\ProductConstants;

interface ProductAlternativeGuiToProductFacadeInterface
{
    /**
     * @param string $sku
     * @param int $limit
     *
     * @return array
     */
    public function filterProductAbstractBySku(string $sku, int $limit = ProductConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array;

    /**
     * @param string $localizedName
     * @param int $limit
     *
     * @return array
     */
    public function filterProductAbstractByLocalizedName(string $localizedName, int $limit = ProductConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array;

    /**
     * @param string $sku
     * @param int $limit
     *
     * @return array
     */
    public function filterProductConcreteBySku(string $sku, int $limit = ProductConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array;

    /**
     * @param string $localizedName
     * @param int $limit
     *
     * @return array
     */
    public function filterProductConcreteByLocalizedName(string $localizedName, int $limit = ProductConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array;
}
