<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointAvailability\Business\Filter;

use ArrayObject;

interface SellableItemRequestFilterInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SellableItemRequestTransfer> $sellableItemRequestTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\SellableItemRequestTransfer>
     */
    public function filterSellableItemRequestTransfersWithProductOfferReferenceAndServicePoint(
        ArrayObject $sellableItemRequestTransfers
    ): ArrayObject;
}
