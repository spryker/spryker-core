<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilter;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductCategoryFilter\ProductCategoryFilterFactory getFactory()
 */
class ProductCategoryFilterClient extends AbstractClient implements ProductCategoryFilterClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer[] $facets
     * @param array $productCategoryFilters
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function updateFacetsByCategory(array $facets, array $productCategoryFilters)
    {
        return $this->getFactory()
            ->createFacetUpdaterByProductCategoryFilters()
            ->update($facets, $productCategoryFilters);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $facets
     * @param int $idCategory
     * @param string $localeName
     *
     * @return array
     */
    public function updateCategoryFacets(array $facets, $idCategory, $localeName)
    {
        return $this->getFactory()
            ->createFacetUpdaterByProductCategoryFilters()
            ->updateFromTransfer(
                $facets,
                $this->getProductCategoryFiltersTransferForCategoryByLocale($idCategory, $localeName)
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $categoryId
     * @param string $localeName
     *
     * @return array
     */
    public function getProductCategoryFiltersForCategoryByLocale($categoryId, $localeName)
    {
        return $this->getProductCategoryFiltersFromStorage($categoryId, $localeName);
    }

    /**
     * @api
     *
     * @param int $idCategory
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function getProductCategoryFiltersTransferForCategoryByLocale($idCategory, $localeName)
    {
        $productCategoryFilterTransfer = new ProductCategoryFilterTransfer();
        $productCategoryFilterTransfer->fromArray(
            $this->getProductCategoryFiltersFromStorage($idCategory, $localeName)
        );

        return $productCategoryFilterTransfer;
    }

    /**
     * @param int $categoryId
     * @param string $localeName
     *
     * @return array
     */
    protected function getProductCategoryFiltersFromStorage($categoryId, $localeName)
    {
        $productCategoryFilters = $this->getFactory()->getStorageClient()->get(
            $this->getFactory()->createProductCategoryFilterKeyBuilder()->generateKey($categoryId, $localeName)
        );

        if (!$productCategoryFilters) {
            return [];
        }

        return $productCategoryFilters;
    }
}
