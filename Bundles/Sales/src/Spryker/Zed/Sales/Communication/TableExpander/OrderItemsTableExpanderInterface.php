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
     * @return array<string>
     */
    public function getColumnHeaders(): array;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, array<string>>
     */
    public function getColumnCellsContent(ArrayObject $itemTransfers): array;
}
