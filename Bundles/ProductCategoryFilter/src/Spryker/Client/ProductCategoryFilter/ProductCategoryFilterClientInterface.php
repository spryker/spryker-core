<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilter;

interface ProductCategoryFilterClientInterface
{
    /**
     * Specification:
     * - Returns formatted facets based on product category filters
     *
     * @api
     *
     * @param array $facets
     * @param array $productCategoryFilters
     *
     * @return array
     */
    public function updateFacetsByCategory($facets, $productCategoryFilters);

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
