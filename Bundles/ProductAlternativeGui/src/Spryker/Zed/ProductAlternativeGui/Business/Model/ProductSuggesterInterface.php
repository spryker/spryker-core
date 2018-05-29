<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business\Model;

use Spryker\Shared\ProductAlternativeGui\ProductAlternativeGuiConstants;

interface ProductSuggesterInterface
{
    /**
     * @param string $productName
     * @param int $limit
     *
     * @return string[]
     */
    public function suggestProductName(string $productName, int $limit = ProductAlternativeGuiConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array;

    /**
     * @param string $productSku
     * @param int $limit
     *
     * @return string[]
     */
    public function suggestProductSku(string $productSku, int $limit = ProductAlternativeGuiConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array;
}
