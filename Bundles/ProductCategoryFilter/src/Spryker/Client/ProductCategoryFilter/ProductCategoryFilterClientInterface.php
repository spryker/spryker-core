<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilter;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

interface ProductCategoryFilterClientInterface
{
    /**
     * @deprecated use updateFacetsByProductCategoryFilterTransfer
     *
     * Specification:
     * - Returns formatted facets based on product category filters from array
     *
     * @api
     *
     * @param array $facets
     * @param array $productCategoryFilters
     *
     * @return array
     */
    public function updateFacetsByCategory(array $facets, array $productCategoryFilters);

    /**
     * Specification:
     * - Returns formatted facets based on product category filters from transfer
     *
     * @api
     *
     * @param array $facets
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return array
     */
    public function updateFacetsByProductCategoryFilterTransfer(array $facets, ProductCategoryFilterTransfer $productCategoryFilterTransfer);

    /**
     * Specification:
     * - Returns product category filters from storage based on category id and locale
     *
     * @api
     *
     * @param int $categoryId
     * @param string $localeName
     *
     * @return array
     */
    public function getProductCategoryFiltersForCategoryByLocale($categoryId, $localeName);
}
