<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Dependency\Client;

class ProductCategoryFilterGuiToProductCategoryFilterBridge implements ProductCategoryFilterGuiToProductCategoryFilterInterface
{
    /**
     * @var \Spryker\Client\ProductCategoryFilter\ProductCategoryFilterClient
     */
    protected $productCategoryFilterClient;

    /**
     * @param \Spryker\Client\ProductCategoryFilter\ProductCategoryFilterClientInterface $productCategoryFilterClient
     */
    public function __construct($productCategoryFilterClient)
    {
        $this->productCategoryFilterClient = $productCategoryFilterClient;
    }

    /**
     * @param array $facets
     * @param int $categoryId
     * @param string $localeName
     *
     * @return array
     */
    public function updateFacetsByCategory($facets, $categoryId, $localeName)
    {
        return $this->productCategoryFilterClient->updateFacetsByCategory($facets, $categoryId, $localeName);
    }
}
