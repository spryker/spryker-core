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
     * @return string[]
     */
    public function getColumnHeaders(): array;

    /**
     * @phpstan-return array<int, string[]>
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject $itemTransfers
     *
     * @return string[]
     */
    public function getColumnCellsContent(ArrayObject $itemTransfers): array;
}
