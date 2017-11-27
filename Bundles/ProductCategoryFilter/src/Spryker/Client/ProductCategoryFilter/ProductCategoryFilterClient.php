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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer[] $facets
     * @param array|null $productCategoryFilters
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function updateFacetsByCategory($facets, $productCategoryFilters)
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
     * @param int $categoryId
     * @param string $localeName
     *
     * @return array
     */
    public function getProductCategoryFiltersForCategoryByLocale($categoryId, $localeName)
    {
        return $this->getFactory()->getStorageClient()->get(
            $this->getFactory()->createProductCategoryFilterKeyBuilder()->generateKey($categoryId, $localeName)
        );
    }
}
