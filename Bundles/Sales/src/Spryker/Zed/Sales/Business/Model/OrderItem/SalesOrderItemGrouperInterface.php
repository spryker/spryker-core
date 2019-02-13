<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\OrderItem;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;

interface SalesOrderItemGrouperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getUniqueOrderItems(ArrayObject $itemTransfers): ItemCollectionTransfer;
}
