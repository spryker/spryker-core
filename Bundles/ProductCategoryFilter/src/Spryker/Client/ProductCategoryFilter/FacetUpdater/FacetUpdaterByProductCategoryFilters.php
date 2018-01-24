<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilter\FacetUpdater;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Shared\ProductCategoryFilter\ProductCategoryFilterConfig;

class FacetUpdaterByProductCategoryFilters implements FacetUpdaterInterface
{
    /**
     * @deprecated use updateFromTransfer()
     *
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer[]|\Generated\Shared\Transfer\RangeSearchResultTransfer[] $facets
     * @param array $updateCriteria
     *
     * @return \Generated\Shared\Transfer\FacetSearchResultTransfer[]|\Generated\Shared\Transfer\RangeSearchResultTransfer[]
     */
    public function update(array $facets, array $updateCriteria)
    {
        if (empty($updateCriteria)) {
            return $facets;
        }

        $newFacets = [];
        foreach ($updateCriteria as $facetKey => $facetConfig) {
            if ($facetConfig[ProductCategoryFilterConfig::IS_ACTIVE_FLAG] && isset($facets[$facetKey])) {
                $newFacets[$facetKey] = $facets[$facetKey];
            }
        }

        return $newFacets;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer[]|\Generated\Shared\Transfer\RangeSearchResultTransfer[] $facets
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\FacetSearchResultTransfer[]|\Generated\Shared\Transfer\RangeSearchResultTransfer[]
     */
    public function updateFromTransfer(array $facets, ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        if (empty($productCategoryFilterTransfer->getFilters())) {
            return $facets;
        }

        $newFacets = [];
        foreach ($productCategoryFilterTransfer->getFilters() as $productCategoryFilterItemTransfer) {
            if ($productCategoryFilterItemTransfer->getIsActive() === true && isset($facets[$productCategoryFilterItemTransfer->getKey()])) {
                $newFacets[$productCategoryFilterItemTransfer->getKey()] = $facets[$productCategoryFilterItemTransfer->getKey()];
            }
        }

        return $newFacets;
    }
}
