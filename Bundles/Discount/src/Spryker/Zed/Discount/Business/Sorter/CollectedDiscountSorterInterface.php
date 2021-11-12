<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Sorter;

interface CollectedDiscountSorterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\CollectedDiscountTransfer> $collectedDiscountTransfers
     *
     * @return array<\Generated\Shared\Transfer\CollectedDiscountTransfer>
     */
    public function sort(array $collectedDiscountTransfers): array;
}
