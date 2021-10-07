<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;

interface PriceProductMapperInterface
{
    /**
     * @param array<mixed> $newPriceProducts
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mapTableRowsToPriceProductTransfers(
        array $newPriceProducts,
        ArrayObject $priceProductTransfers
    ): ArrayObject;
}
