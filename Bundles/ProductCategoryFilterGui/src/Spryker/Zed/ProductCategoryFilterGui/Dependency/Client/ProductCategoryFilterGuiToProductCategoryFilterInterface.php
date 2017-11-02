<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Dependency\Client;

interface ProductCategoryFilterGuiToProductCategoryFilterInterface
{
    /**
     * @param array $facets
     * @param int $categoryId
     * @param string $localeName
     *
     * @return array
     */
    public function updateFacetsByCategory($facets, $categoryId, $localeName);
}
