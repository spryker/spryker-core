<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business\Model\Order\Item;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;

class ItemTransformer implements ItemTransformerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function transformItem(ItemTransfer $itemTransfer): ArrayObject
    {
        return new ArrayObject([$itemTransfer]);
    }
}
