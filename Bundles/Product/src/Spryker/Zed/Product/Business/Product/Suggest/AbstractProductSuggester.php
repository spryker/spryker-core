<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Suggest;

use Spryker\Shared\Product\ProductConstants;

class AbstractProductSuggester
{
    /**
     * @var \Spryker\Zed\Product\ProductConfig
     */
    protected $config;

    /**
     * @param array $products
     *
     * @return array
     */
    protected function collectFilteredResults(array $products): array
    {
        $results = [];

        foreach ($products as $product) {
            $results[] = $product[ProductConstants::FILTERED_PRODUCTS_RESULT_KEY];
        }

        return $results;
    }
}
