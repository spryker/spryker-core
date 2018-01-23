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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $facets
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return array
     */
    public function updateFacetsByProductCategoryFilterTransfer(array $facets, ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        return $this->getFactory()
            ->createFacetUpdaterByProductCategoryFilters()
            ->updateFromTransfer($facets, $productCategoryFilterTransfer->getFilters());
    }

    /**
     * {@inheritdoc}
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
     * @param $categoryId
     * @param $localeName
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function getProductCategoryFiltersTransferForCategoryByLocale($categoryId, $localeName)
    {
        $productCategoryFilters = $this->getProductCategoryFiltersFromStorage($categoryId, $localeName);
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
