<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business;

use Generated\Shared\Transfer\ProductConcreteTransfer;

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
     * - Persists product alternatives stored in product concrete transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternatives(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;
}
