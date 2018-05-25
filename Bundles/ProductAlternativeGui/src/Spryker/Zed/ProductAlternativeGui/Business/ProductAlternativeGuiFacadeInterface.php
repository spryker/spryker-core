<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business;

interface ProductAlternativeGuiFacadeInterface
{
    /**
     * Specification:
     * - Suggests product name.
     *
     * @api
     *
     * @param string $productName
     *
     * @return string[]
     */
    public function suggestProductName(string $productName): array;

    /**
     * Specification:
     * - Suggests product sku.
     *
     * @api
     *
     * @param string $productSku
     *
     * @return string[]
     */
    public function suggestProductSku(string $productSku): array;
}
