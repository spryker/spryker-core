<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilter;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductCategoryFilter\ProductCategoryFilterFactory getFactory()
 */
class ProductCategoryFilterClient extends AbstractClient implements ProductCategoryFilterClientInterface
{
    /**
     * @api
     *
     * @param array $facets
     * @param int $categoryId
     * @param string $localeName
     *
     * @return array
     */
    public function updateFacetsByCategory($facets, $categoryId, $localeName)
    {
        $productCategoryFilters = $this->getFactory()->getStorageClient()->get(
            $this->getFactory()->createProductCategoryFilterKeyBuilder()->generateKey($categoryId, $localeName)
        );

        if (empty($productCategoryFilters)) {
            return $facets;
        }

        return $this->getNewFacetsBasedOnCategory($productCategoryFilters, $facets);
    }

    /**
     * @param array $productCategoryFilters
     * @param array $oldFacets
     *
     * @return array
     */
    protected function getNewFacetsBasedOnCategory($productCategoryFilters, $oldFacets)
    {
        $newFacets = [];
        foreach ($productCategoryFilters as $filterName => $showFilter) {
            if ($showFilter && isset($facets[$filterName])) {
                $newFacets[$filterName] = $oldFacets[$filterName];
            }
        }

        return $newFacets;
    }
}
