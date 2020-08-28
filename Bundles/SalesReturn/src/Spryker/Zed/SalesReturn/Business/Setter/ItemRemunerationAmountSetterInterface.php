<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Setter;

use Generated\Shared\Transfer\ItemTransfer;

interface ItemRemunerationAmountSetterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function setOrderItemRemunerationAmount(ItemTransfer $itemTransfer): void;
}
