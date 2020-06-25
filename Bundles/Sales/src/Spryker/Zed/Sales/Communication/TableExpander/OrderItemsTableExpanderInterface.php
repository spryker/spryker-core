<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\TableExpander;

use ArrayObject;

interface OrderItemsTableExpanderInterface
{
    /**
     * @return array
     */
    public function getColumnHeaders();

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject $itemTransfers
     *
     * @return array
     */
    public function getColumnCellsContent(ArrayObject $itemTransfers): array;
}
