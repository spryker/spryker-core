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
     * - Returns formatted facets based on product category filters from array
     *
     * @api
     *
     * @deprecated Use {@link updateCategoryFacets()} instead.
     *
     * @param array $facets
     * @param array $productCategoryFilters
     *
     * @return array
     */
    public function updateFacetsByCategory(array $facets, array $productCategoryFilters);

    /**
     * Specification:
     * - Returns formatted facets based on product category filters from category id and locale name
     *
     * @api
     *
     * @param array $facets
     * @param int $idCategory
     * @param string $localeName
     *
     * @return array
     */
    public function updateCategoryFacets(array $facets, $idCategory, $localeName);

    /**
     * Specification:
     * - Returns saved product category filters from storage
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * Specification:
     * - Returns product category filters from storage based on category id and locale
     *
     * @param int $categoryId
     * @param string $localeName
     *
     * @return array
     */
    public function getProductCategoryFiltersForCategoryByLocale($categoryId, $localeName);
}
