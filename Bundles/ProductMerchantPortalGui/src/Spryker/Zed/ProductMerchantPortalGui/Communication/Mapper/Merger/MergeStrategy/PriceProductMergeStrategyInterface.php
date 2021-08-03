<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductMergeStrategyInterface
{
    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return bool
     */
    public function isApplicable(
        PriceProductTransfer $newPriceProductTransfer,
        ArrayObject $priceProductTransfers
    ): bool;

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function merge(
        PriceProductTransfer $newPriceProductTransfer,
        ArrayObject $priceProductTransfers
    ): ArrayObject;
}
