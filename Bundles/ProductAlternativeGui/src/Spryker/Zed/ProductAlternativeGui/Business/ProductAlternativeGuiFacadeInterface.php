<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business;

use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;

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

    /**
     * Specification:
     * - Creates alternative product accordingly to $searchName.
     * - $searchName can be abstract/concrete product name or SKU.
     *
     * @api
     *
     * @param string $searchName
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductAlternative(string $searchName): ProductAlternativeResponseTransfer;
}
