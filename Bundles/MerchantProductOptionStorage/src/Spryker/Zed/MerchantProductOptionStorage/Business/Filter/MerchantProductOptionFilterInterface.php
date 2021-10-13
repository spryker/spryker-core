<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Business\Filter;

interface MerchantProductOptionFilterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductOptionTransfer> $productOptionTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductOptionTransfer>
     */
    public function filterProductOptions(array $productOptionTransfers): array;
}
