<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilter\FacetUpdater;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

class FacetUpdaterByProductCategoryFilters implements FacetUpdaterInterface
{
    /**
     * @deprecated Use updateFromTransfer() instead.
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
        foreach ($updateCriteria as $criteria => $show) {
            if ($show && isset($facets[$criteria])) {
                $newFacets[$criteria] = $facets[$criteria];
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
        /** @var (\Generated\Shared\Transfer\ProductCategoryFilterItemTransfer|null)[] $filters */
        $filters = (array)$productCategoryFilterTransfer->getFilters();
        if (!$filters) {
            return $facets;
        }

        $newFacets = [];
        foreach ($filters as $productCategoryFilterItemTransfer) {
            if ($productCategoryFilterItemTransfer === null) {
                continue;
            }
            if ($productCategoryFilterItemTransfer->getIsActive() === true && isset($facets[$productCategoryFilterItemTransfer->getKey()])) {
                $newFacets[$productCategoryFilterItemTransfer->getKey()] = $facets[$productCategoryFilterItemTransfer->getKey()];
            }
        }

        return $newFacets;
    }
}
