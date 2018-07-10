<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Item;

use Generated\Shared\Transfer\ItemCollectionTransfer;

interface ItemSanitizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $items
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function removeSumPrices(ItemCollectionTransfer $items);
}
