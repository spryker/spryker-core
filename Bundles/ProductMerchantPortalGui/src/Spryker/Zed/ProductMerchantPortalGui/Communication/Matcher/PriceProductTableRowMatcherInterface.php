<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Matcher;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductTableRowMatcherInterface
{
    /**
     * @param array<mixed> $initialDataRow
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array $propertyPath
     *
     * @return bool
     */
    public function isPriceProductInRow(
        array $initialDataRow,
        PriceProductTransfer $priceProductTransfer,
        array $propertyPath
    ): bool;
}
