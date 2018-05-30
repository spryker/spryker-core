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
     * - Suggests product by name or SKU.
     *
     * @api
     *
     * @param string $searchName
     *
     * @return string[]
     */
    public function suggestProduct(string $searchName): array;
}
