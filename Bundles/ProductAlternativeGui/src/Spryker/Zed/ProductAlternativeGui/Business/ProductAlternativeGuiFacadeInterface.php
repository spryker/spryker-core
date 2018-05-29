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
     * - Suggests product names.
     *
     * @api
     *
     * @param string $productName
     *
     * @return string[]
     */
    public function suggestProductNames(string $productName): array;

    /**
     * Specification:
     * - Suggests product SKUs.
     *
     * @api
     *
     * @param string $productSku
     *
     * @return string[]
     */
    public function suggestProductSkus(string $productSku): array;
}
