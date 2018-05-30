<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business\Product;

interface ProductSuggesterInterface
{
    /**
     * @param string $productName
     * @param null|int $limit
     *
     * @return string[]
     */
    public function suggestProductName(string $productName, ?int $limit = null): array;

    /**
     * @param string $productSku
     * @param null|int $limit
     *
     * @return string[]
     */
    public function suggestProductSku(string $productSku, ?int $limit = null): array;
}
