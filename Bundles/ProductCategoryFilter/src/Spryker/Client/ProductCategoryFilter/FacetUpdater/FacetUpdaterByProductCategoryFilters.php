<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilter\FacetUpdater;

use Spryker\Shared\ProductCategoryFilter\ProductCategoryFilterConfig;

class FacetUpdaterByProductCategoryFilters implements FacetUpdaterInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer[] $facets
     * @param array $updateCriteria
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
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
}
